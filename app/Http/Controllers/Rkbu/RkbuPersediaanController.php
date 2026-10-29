<?php

namespace App\Http\Controllers\Rkbu;

use App\DataTables\Rkbu\RkbuPersediaanDataTable;
use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\Komponen;
use App\Models\MasterBackend\SettingInput\Ppn;
use App\Models\MasterBackend\SettingInput\UraianDua;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use App\Models\MasterBackend\SettingRkbu\KategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\Rkbu\Rkbu;
use App\Models\Rkbu\RkbuDetailPersediaan;
use App\Services\Workflow\WorkflowApprovalService;
use App\Support\Rkbu\RkbuListFilters;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RkbuPersediaanImport;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class RkbuPersediaanController extends Controller
{
    protected $workflowApprovalService;

    public function __construct(
        WorkflowApprovalService $workflowApprovalService
    ) {
        $this->workflowApprovalService = $workflowApprovalService;
    }

    public function index(RkbuPersediaanDataTable $dataTable)
    {
        $rkbupersediaan         = RkbuDetailPersediaan::all();
        $uraian_satu            = UraianSatu::all();
        $uraian_dua             = UraianDua::all();
        $sumber_dana            = SumberDana::all();
        $ppn                    = Ppn::all();


        return $dataTable->render('rkbu.rkbu_persediaan.index', [
            'title'                 => 'Halaman RKBU Persediaan',
            'title2'                => 'RKBU Persediaan',
            'routePrefix'           => 'rkbu.persediaan',
            'rkbupersediaan'        => $rkbupersediaan,
            'uraian_satu'           => $uraian_satu,
            'uraian_dua'            => $uraian_dua,
            'sumber_dana'           => $sumber_dana,
            'sub_kategori_rkbu'     => RkbuListFilters::availableSubcategories($this->subKategoriOptions(), 'detailPersediaan'),
            'ppn'                   => $ppn,
            'approval_statuses'     => RkbuListFilters::APPROVAL_STATUSES,
        ]);
    }

    public function print(RkbuPersediaanDataTable $dataTable)
    {
        $persediaans = $dataTable->query(new RkbuDetailPersediaan())->get();

        $pdf = Pdf::loadView('rkbu.rkbu_persediaan.pdf', [
            'records' => $persediaans,
            'title' => 'RKBU Persediaan',
            'approvalStatuses' => RkbuListFilters::APPROVAL_STATUSES,
            'tahunAnggaran' => session('tahun_anggaran'),
            'printedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('rkbu-persediaan-' . now()->format('Ymd-His') . '.pdf');
    }

    public function downloadTemplate(): StreamedResponse
    {
        $this->authorizeBulkImport();
        $headers = ['kode_sub_kategori_rkbu', 'nama_barang', 'spesifikasi', 'satuan_1', 'stok', 'rata_rata_pemakaian', 'pengadaan_sebelumnya', 'kebutuhan_per_bulan', 'buffer', 'harga_satuan', 'is_ppn', 'rating', 'penempatan', 'link_ekatalog'];
        $filename = 'template-rkbu-persediaan.csv';

        return response()->streamDownload(function () use ($headers) {
            $output = fopen('php://output', 'w');
            fputcsv($output, $headers);
            fputcsv($output, ['ISI_KODE_SUBKATEGORI', 'Contoh nama barang', '', 'unit', 0, 0, 0, 0, 0, 0, 0, '', '', '']);
            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function import(Request $request)
    {
        $this->authorizeBulkImport();
        $request->validate(['file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240']);

        $rows = Excel::toCollection(new RkbuPersediaanImport(), $request->file('file'))->first();
        if (!$rows || $rows->count() < 2) {
            return back()->withErrors(['file' => 'File tidak memiliki baris data.']);
        }

        $headers = collect($rows->first())->map(fn ($value) => trim((string) $value))->all();
        $required = ['kode_sub_kategori_rkbu', 'nama_barang', 'stok', 'harga_satuan'];
        if (array_diff($required, $headers)) {
            return back()->withErrors(['file' => 'Kolom wajib tidak lengkap. Unduh dan gunakan template yang disediakan.']);
        }

        $map = collect($headers)->flip();
        $subKategoriMap = $this->subKategoriOptions()->get()->keyBy('kode_sub_kategori_rkbu');
        $jenisBelanja = JenisBelanja::where('nama_jenis_belanja', 'LIKE', '%Barang%')->firstOrFail();
        $ppnAktif = Ppn::where('status', 'aktif')->firstOrFail();
        $accepted = [];
        $errors = [];

        foreach ($rows->skip(1) as $index => $row) {
            $values = [];
            foreach ($map as $header => $column) $values[$header] = $row[$column] ?? null;
            if (collect($values)->filter(fn ($value) => $value !== null && trim((string) $value) !== '')->isEmpty()) continue;
            $line = $index + 2;
            $subcategory = $subKategoriMap->get(trim((string) ($values['kode_sub_kategori_rkbu'] ?? '')));
            if (!$subcategory) { $errors[] = "Baris {$line}: kode subkategori tidak ditemukan."; continue; }
            if (trim((string) ($values['nama_barang'] ?? '')) === '' || !is_numeric($values['stok'] ?? null) || (int) $values['stok'] < 0 || !is_numeric($values['harga_satuan'] ?? null) || (float) $values['harga_satuan'] < 0) {
                $errors[] = "Baris {$line}: nama_barang, stok, dan harga_satuan harus diisi dengan nilai yang valid."; continue;
            }
            foreach (['rata_rata_pemakaian', 'pengadaan_sebelumnya', 'kebutuhan_per_bulan', 'buffer'] as $number) {
                if (isset($values[$number]) && $values[$number] !== '' && (!is_numeric($values[$number]) || (int) $values[$number] < 0)) $errors[] = "Baris {$line}: {$number} harus bilangan bulat >= 0.";
            }
            if (count($errors) && str_starts_with(end($errors), "Baris {$line}:")) continue;
            $accepted[] = [$values, $subcategory];
        }

        if ($errors) return back()->withErrors(['file' => implode(' ', array_slice($errors, 0, 8)) . (count($errors) > 8 ? ' Dan ' . (count($errors) - 8) . ' kesalahan lainnya.' : '')]);
        if (!$accepted) return back()->withErrors(['file' => 'Tidak ada data valid untuk diimpor.']);

        DB::transaction(function () use ($accepted, $jenisBelanja, $ppnAktif) {
            foreach ($accepted as [$row, $subcategory]) {
                $kategori = KategoriRkbu::findOrFail($subcategory->id_kategori_rkbu);
                $rkbu = Rkbu::create([
                    'nomor_rkbu' => $this->generateNomorRkbu(), 'tahun_anggaran_id' => Session::get('tahun_anggaran_id'),
                    'unit_id' => Session::get('unit_id'), 'sumber_dana_id' => SumberDana::query()->firstOrFail()->id_sumber_dana,
                    'jenis_belanja_id' => $jenisBelanja->id_jenis_belanja, 'jenis_kategori_rkbu_id' => $kategori->id_jenis_kategori_rkbu,
                    'sub_kategori_rkbu_id' => $subcategory->id_sub_kategori_rkbu, 'created_by' => auth()->id(), 'status' => 'draft',
                ]);
                $data = [
                    'sub_kategori_rkbu_id' => $subcategory->id_sub_kategori_rkbu, 'nama_barang' => trim($row['nama_barang']),
                    'spesifikasi' => $row['spesifikasi'] ?: null, 'satuan_1' => $row['satuan_1'] ?: null,
                    'stok' => (int) $row['stok'], 'rata_rata_pemakaian' => $row['rata_rata_pemakaian'] ?: 0,
                    'pengadaan_sebelumnya' => $row['pengadaan_sebelumnya'] ?: 0, 'kebutuhan_per_bulan' => $row['kebutuhan_per_bulan'] ?: 0,
                    'buffer' => $row['buffer'] ?: 0, 'harga_satuan' => (float) $row['harga_satuan'],
                    'rating' => in_array((string) ($row['rating'] ?? ''), ['1', '2', '3'], true) ? $row['rating'] : null,
                    'penempatan' => $row['penempatan'] ?: null, 'link_ekatalog' => $row['link_ekatalog'] ?: null,
                ];
                $ppn = in_array(strtolower((string) ($row['is_ppn'] ?? '0')), ['1', 'ya', 'yes', 'true'], true) ? (float) $ppnAktif->ppn : 0;
                RkbuDetailPersediaan::create(array_merge($this->detailValues($data, $ppn), ['rkbu_id' => $rkbu->id, 'status_komponen' => 'draft']));
            }
        });

        return redirect()->route('rkbu.persediaan.index')->with('success', count($accepted) . ' data RKBU berhasil diimpor sebagai draft.');
    }

    public function create()
    {
        $rkbupersediaan = new RkbuDetailPersediaan();
        $ppn = Ppn::where('status', 'aktif')->firstOrFail();
        $tahunAnggaran = (int) preg_replace('/[^0-9]/', '', (string) session('tahun_anggaran'));
        $angka_kurang_2 = $tahunAnggaran - 2;
        $angka_kurang_1 = $tahunAnggaran - 1;
        $angka_tahun = $tahunAnggaran;

        return view('rkbu.rkbu_persediaan.create', [
            'uraian_satu'           => UraianSatu::all(),
            'sub_kategori_rkbu'     => $this->subKategoriOptions()->get(),
            'sub_kegiatans'         => SubKegiatan::with('kegiatan')->orderBy('nama_sub_kegiatan')->get(),
            'komponens'             => Komponen::all(),
            'ppn'                   => $ppn,
            'angka_kurang_2'        => $angka_kurang_2,
            'angka_kurang_1'        => $angka_kurang_1,
            'angka_tahun'           => $angka_tahun,
            'rkbupersediaan'        => $rkbupersediaan,
            'title'                 => 'Halaman Tambah RKBU',
            'routePrefix'           => 'rkbu.persediaan',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateForm($request);

        DB::transaction(function () use ($request, $validated) {
            $subKategori = $this->subKategoriOptions()->findOrFail($validated['sub_kategori_rkbu_id']);
            $kategori = KategoriRkbu::findOrFail($subKategori->id_kategori_rkbu);
            $jenisBelanja = JenisBelanja::where('nama_jenis_belanja', 'LIKE', '%Barang%')
                ->firstOrFail();
            $subKegiatan = $this->selectedSubKegiatan($request);
            $sumberDanaId = $subKegiatan?->id_sumber_dana
                ?? SumberDana::query()->firstOrFail()->id_sumber_dana;
            $ppn = $this->ppnForRequest($request);

            $rkbu = Rkbu::create([
                'nomor_rkbu' => $this->generateNomorRkbu(),
                'tahun_anggaran_id' => Session::get('tahun_anggaran_id'),
                'unit_id' => Session::get('unit_id'),
                'sumber_dana_id' => null,
                'jenis_belanja_id' => $jenisBelanja->id_jenis_belanja,
                'jenis_kategori_rkbu_id' => $kategori->id_jenis_kategori_rkbu,
                'sub_kategori_rkbu_id' => $subKategori->id_sub_kategori_rkbu,
                'created_by' => auth()->id(),
                'workflow_instance_id' => null,
                'status' => 'draft',
            ]);

            if ($subKegiatan) {
                $rkbu->sub_kegiatan_id = $subKegiatan->id_sub_kegiatan;
                $rkbu->save();
            }

            $values = $this->detailValues($validated, $ppn);
            $values['rkbu_id'] = $rkbu->id;
            $values['status_komponen'] = 'draft';
            $values = array_merge($values, $this->storeUploads($request));

            RkbuDetailPersediaan::create($values);
        });

        return redirect()
            ->route('rkbu.persediaan.index')
            ->with('success', 'Data RKBU berhasil disimpan.');
    }

    public function edit($id)
    {
        $persediaan = RkbuDetailPersediaan::with([
            'rkbu',
            'rkbu.subKegiatan.kegiatan',
            'rkbu.sumberDana',
        ])->findOrFail($id);
        $tahunAnggaran = (int) preg_replace('/[^0-9]/', '', (string) session('tahun_anggaran'));

        return view('rkbu.rkbu_persediaan.edit', [
            'persediaan' => $persediaan,
            'uraian_satu' => UraianSatu::all(),
            'sub_kategori_rkbu' => $this->subKategoriOptions()->get(),
            'sub_kegiatans' => SubKegiatan::with('kegiatan')->orderBy('nama_sub_kegiatan')->get(),
            'ppn' => Ppn::where('status', 'aktif')->firstOrFail(),
            'angka_kurang_2' => $tahunAnggaran - 2,
            'angka_kurang_1' => $tahunAnggaran - 1,
            'angka_tahun' => $tahunAnggaran,
            'routePrefix' => 'rkbu.persediaan',
        ]);
    }

    public function submit($id)
    {
        return $this->workflowApprovalService->submit($id, RkbuDetailPersediaan::class);
    }

    public function bulkSubmit(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'required|string|distinct',
        ]);

        $details = RkbuDetailPersediaan::whereIn('id', $validated['ids'])
            ->whereHas('rkbu', function ($query) {
                $query->where('created_by', auth()->id())
                    ->whereIn('status', ['draft', 'revision', 'rejected']);
            })
            ->get()
            ->keyBy('id');

        $results = [];
        foreach ($validated['ids'] as $id) {
            if (!$details->has($id)) {
                $results[] = [
                    'id' => $id,
                    'success' => false,
                    'message' => 'Data tidak ditemukan, bukan milik Anda, atau statusnya tidak dapat diajukan.',
                ];
                continue;
            }

            $response = $this->workflowApprovalService->submit($id, RkbuDetailPersediaan::class);
            $payload = $response->getData(true);
            $results[] = [
                'id' => $id,
                'success' => $response->getStatusCode() < 400 && ($payload['success'] ?? false),
                'message' => $payload['message'] ?? 'Pengajuan gagal diproses.',
            ];
        }

        $successCount = collect($results)->where('success', true)->count();

        return response()->json([
            'success' => $successCount === count($results),
            'message' => "{$successCount} dari " . count($results) . ' RKBU berhasil diajukan.',
            'results' => $results,
        ], 200);
    }



    public function update(Request $request, $id)
    {
        $validated = $this->validateForm($request);
        $detail = RkbuDetailPersediaan::with('rkbu')
            ->findOrFail($id);

        DB::transaction(function () use ($request, $validated, $detail) {
            $subKategori = $this->subKategoriOptions()->findOrFail($validated['sub_kategori_rkbu_id']);
            $kategori = KategoriRkbu::findOrFail($subKategori->id_kategori_rkbu);
            $subKegiatan = $this->selectedSubKegiatan($request);
            $ppn = $this->ppnForRequest($request);

            $detail->update(array_merge(
                $this->detailValues($validated, $ppn),
                $this->storeUploads($request, $detail),
            ));

            $detail->rkbu->jenis_kategori_rkbu_id = $kategori->id_jenis_kategori_rkbu;
            $detail->rkbu->sub_kategori_rkbu_id = $subKategori->id_sub_kategori_rkbu;

            if ($subKegiatan) {
                $detail->rkbu->sub_kegiatan_id = $subKegiatan->id_sub_kegiatan;
                $detail->rkbu->sumber_dana_id = $subKegiatan->id_sumber_dana;
            } elseif (
                auth()->user()->roles->contains('nama_role', 'Administrator')
                && $request->exists('sub_kegiatan_id')
                && !$request->filled('sub_kegiatan_id')
            ) {
                $detail->rkbu->sub_kegiatan_id = null;
            }

            $detail->rkbu->save();
        });

        return redirect()
            ->route('rkbu.persediaan.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    private function validateForm(Request $request): array
    {
        $request->merge([
            'harga_satuan' => preg_replace(
                '/\D/',
                '',
                (string) $request->input('harga_satuan', '')
            ),
        ]);

        return $request->validate([
            'sub_kategori_rkbu_id'      => [
                'required',
                'uuid',
                Rule::in($this->subKategoriOptions()->pluck('id_sub_kategori_rkbu')->all()),
            ],
            'sub_kegiatan_id'           => 'nullable|exists:sub_kegiatans,id_sub_kegiatan',
            'nama_barang'               => 'required|string|max:255',
            'spesifikasi'               => 'nullable|string',
            'satuan_1'                  => 'nullable|string|max:255',
            'stok'                      => 'required|integer|min:0',
            'rata_rata_pemakaian'       => 'nullable|integer|min:0',
            'pengadaan_sebelumnya'      => 'nullable|integer|min:0',
            'kebutuhan_per_bulan'       => 'nullable|integer|min:0',
            'buffer'                    => 'nullable|integer|min:0',
            'harga_satuan'              => 'required|numeric|min:0',
            'ppn'                       => 'nullable|numeric|min:0|max:100',
            'rating'                    => 'nullable|in:1,2,3',
            'penempatan'                => 'nullable|string|max:255',
            'link_ekatalog'             => 'nullable|string|max:255',
            'upload_file_1'             => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
            'upload_file_2'             => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
            'upload_file_3'             => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
            'upload_file_4'             => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
            'upload_file_5'             => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
        ]);
    }

    private function authorizeBulkImport(): void
    {
        $user = auth()->user();
        $allowed = $user && ($user->roles->contains(fn ($role) => in_array(strtolower(trim($role->nama_role)), ['administrator', 'super admin'], true))
            || ($user->status_edit === 'aktif' && $user->roles->contains('nama_role', 'Operator')));
        abort_unless($allowed, 403);
    }

    private function selectedSubKegiatan(Request $request): ?SubKegiatan
    {
        if (
            !auth()->user()->roles->contains('nama_role', 'Administrator')
            || !$request->filled('sub_kegiatan_id')
        ) {
            return null;
        }

        return SubKegiatan::findOrFail($request->input('sub_kegiatan_id'));
    }

    private function ppnForRequest(Request $request): float
    {
        if (!$request->boolean('is_ppn')) {
            return 0;
        }

        return (float) Ppn::where('status', 'aktif')->firstOrFail()->ppn;
    }

    private function detailValues(array $validated, float $ppn): array
    {
        $stok               = (int) $validated['stok'];
        $rataRata           = (int) ($validated['rata_rata_pemakaian'] ?? 0);
        $pengadaan          = (int) ($validated['pengadaan_sebelumnya'] ?? 0);
        $kebutuhanBulanan   = (int) ($validated['kebutuhan_per_bulan'] ?? 0);
        $buffer             = (int) ($validated['buffer'] ?? 0);
        $kebutuhanTahun     = $rataRata * $kebutuhanBulanan;
        $proyeksiSisaStok   = $stok + $pengadaan - $kebutuhanTahun;
        $kebutuhanPlusBuffer = $kebutuhanTahun + ($rataRata * $buffer);
        $jumlahVolume       = max(0, $kebutuhanPlusBuffer - $proyeksiSisaStok);
        $hargaSatuan        = (float) $validated['harga_satuan'];
        $totalAnggaran      = round($jumlahVolume * $hargaSatuan * (1 + ($ppn / 100)), 2);

        return [
            'nama_barang'           => $validated['nama_barang'],
            'spesifikasi'           => $validated['spesifikasi'] ?? null,
            'satuan_1'              => $validated['satuan_1'] ?? null,
            'stok'                  => $stok,
            'rata_rata_pemakaian'   => $validated['rata_rata_pemakaian'] ?? null,
            'pengadaan_sebelumnya'  => $validated['pengadaan_sebelumnya'] ?? null,
            'kebutuhan_per_bulan'   => $validated['kebutuhan_per_bulan'] ?? null,
            'buffer'                => $validated['buffer'] ?? null,
            'proyeksi_sisa_stok'    => $proyeksiSisaStok,
            'kebutuhan_tahun_x1'    => $kebutuhanTahun,
            'kebutuhan_plus_buffer' => $kebutuhanPlusBuffer,
            'rencana_pengadaan_tahun_x1' => $jumlahVolume,
            'jumlah_volume'         => $jumlahVolume,
            'harga_satuan'          => $hargaSatuan,
            'ppn'                   => $ppn,
            'total_anggaran'        => $totalAnggaran,
            'rating'                => $validated['rating'] ?? null,
            'link_ekatalog'         => $validated['link_ekatalog'] ?? null,
            'penempatan'            => $validated['penempatan'] ?? null,
            'sisa_vol_rkbu'         => $jumlahVolume,
            'sisa_anggaran_rkbu'    => $totalAnggaran,
        ];
    }

    private function storeUploads(Request $request, ?RkbuDetailPersediaan $detail = null): array
    {
        $uploads = [];
        for ($i = 1; $i <= 5; $i++) {
            $field = "upload_file_{$i}";
            $uploads[$field] = $request->hasFile($field)
                ? $request->file($field)->store('kap', 'public')
                : $detail?->{$field};
        }

        return $uploads;
    }

    public function show($id)
    {
        $persediaan = RkbuDetailPersediaan::with(
            'rkbu.subKategori',
            'rkbu.unit',
            'rkbu.user',
            'rkbu.workflowInstances.approvals.validatorUser',
            'rkbu.workflowInstances.approvals.position',
            'rkbu.workflowInstances.approvals.workflowStep.position',
            'rkbu.workflowInstances.approvals.workflowStep.validatorRole',
            'rkbu.workflowInstances.approvals.logs.user',
            'rkbu.workflowInstances.approvals.logs.position'
        )
            ->findOrFail($id);

        app(\App\Services\Workflow\UsulanBelanjaBalanceService::class)->applyToDetail($persediaan);

        return view('rkbu.rkbu_persediaan.show', compact('persediaan'), [
            'title' => 'Detail RKBU Persediaan',
            'routePrefix'           => 'rkbu.persediaan',
        ]);
    }

    private function generateNomorRkbu()
    {
        $tahun = date('Y');

        $last = Rkbu::whereYear('created_at', $tahun)
            ->count();

        $urut = str_pad(
            $last + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        return "RKBU-{$tahun}-{$urut}";
    }

    private function subKategoriOptions()
    {
        return SubKategoriRkbu::query()->forJenisKategori('Belanja Barang Persediaan');
    }
}
