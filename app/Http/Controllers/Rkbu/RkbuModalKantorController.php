<?php

namespace App\Http\Controllers\Rkbu;

use App\DataTables\Rkbu\RkbuModalKantorDataTable;
use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\Anggaran;
use App\Models\MasterBackend\SettingInput\Komponen;
use App\Models\MasterBackend\SettingInput\Ppn;
use App\Models\MasterBackend\SettingInput\UraianDua;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use App\Models\MasterBackend\SettingRkbu\KategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\Program;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\Rkbu\Rkbu;
use App\Models\Rkbu\RkbuDetailModal;
use App\Services\Workflow\WorkflowApprovalService;
use App\Support\Rkbu\RkbuListFilters;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class RkbuModalKantorController extends Controller
{
    protected $workflowApprovalService;

    public function __construct(
        WorkflowApprovalService $workflowApprovalService
    ) {
        $this->workflowApprovalService = $workflowApprovalService;
    }

    public function index(RkbuModalKantorDataTable $dataTable)
    {
        $rkbumodalkantor        = RkbuDetailModal::all();
        $uraian_satu            = UraianSatu::all();
        $uraian_dua             = UraianDua::all();
        $sumber_dana            = SumberDana::all();
        $ppn                    = Ppn::all();


        return $dataTable->render('rkbu.rkbu_modal.index', [
            'title'                 => 'Halaman RKBU Modal',
            'title2'                => 'RKBU Modal',
            'routePrefix'           => 'rkbu.modal_kantor',
            'rkbumodalkantor'       => $rkbumodalkantor,
            'uraian_satu'           => $uraian_satu,
            'uraian_dua'            => $uraian_dua,
            'sumber_dana'           => $sumber_dana,
            'ppn'                   => $ppn,
            'approval_statuses'     => RkbuListFilters::APPROVAL_STATUSES,
        ]);
    }

    public function print(RkbuModalKantorDataTable $dataTable)
    {
        $records = $dataTable->query(new RkbuDetailModal())->get();

        return Pdf::loadView('rkbu.rkbu_persediaan.pdf', [
            'records' => $records,
            'title' => 'RKBU Modal',
            'approvalStatuses' => RkbuListFilters::APPROVAL_STATUSES,
            'tahunAnggaran' => session('tahun_anggaran'),
            'printedAt' => now(),
        ])->setPaper('a4', 'landscape')
            ->stream('rkbu-modal-' . now()->format('Ymd-His') . '.pdf');
    }

    public function create()
    {
        $uraian_satu            = UraianSatu::all();
        $uraian_dua             = UraianDua::all();
        $sub_kategori_rkbu      = $this->subKategoriOptions()->get();
        $komponens               = Komponen::all();
        $ppn                    = Ppn::where('status', 'aktif')->first();
        $programs = Program::orderBy('nama_program')
            ->get();

        return view('rkbu.rkbu_modal.create', compact('uraian_satu', 'uraian_dua', 'sub_kategori_rkbu', 'ppn', 'komponens', 'programs'), [
            'title' => 'Halaman Tambah RKBU',
            'routePrefix'           => 'rkbu.modal_kantor',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_kategori_rkbu_id' => [
                'required',
                'uuid',
                Rule::in($this->subKategoriOptions()->pluck('id_sub_kategori_rkbu')->all()),
            ],
            'nama_barang'          => 'required',
            'volume_1'             => 'required|numeric|min:1',
            'harga_satuan'         => 'required',

            'upload_file_1' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
            'upload_file_2' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
            'upload_file_3' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
            'upload_file_4' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
            'upload_file_5' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
        ]);

        DB::beginTransaction();

        try {
            $subKategori = $this->subKategoriOptions()->with('kategori')
                ->findOrFail($request->sub_kategori_rkbu_id);
            $kategori = KategoriRkbu::findOrFail(
                $subKategori->id_kategori_rkbu
            );
            $tahunAnggaranId = Session::get('tahun_anggaran_id');
            $unitId = Session::get('unit_id');
            $sumberDana = SumberDana::firstOrFail();
            $jenisBelanja = JenisBelanja::where(
                'nama_jenis_belanja',
                'LIKE',
                '%Barang%'
            )->firstOrFail();


            $rkbu = Rkbu::create([
                'id' => Str::uuid()->toString(),
                'nomor_rkbu' => $this->generateNomorRkbu(),
                'tahun_anggaran_id' => $tahunAnggaranId,
                'unit_id' => $unitId,
                'sumber_dana_id' => null,
                'jenis_belanja_id' => $jenisBelanja->id_jenis_belanja,
                'jenis_kategori_rkbu_id'
                => $kategori->id_jenis_kategori_rkbu,
                'sub_kategori_rkbu_id'
                => $subKategori->id_sub_kategori_rkbu,
                'created_by' => auth()->id(),
                'workflow_instance_id' => null,
                'status' => 'draft',
            ]);
            $volume1 = (float) $request->volume_1;
            $volume2 = (float) ($request->volume_2 ?? 1);
            $jumlahVolume = $volume1 * $volume2;
            $hargaSatuan = (float) str_replace(
                '.',
                '',
                $request->harga_satuan
            );
            $ppn = (float) ($request->ppn ?? 0);
            $subtotal = $jumlahVolume * $hargaSatuan;
            $nilaiPpn = ($subtotal * $ppn) / 100;
            $totalAnggaran = $subtotal + $nilaiPpn;

            $uploadFile1 = null;
            $uploadFile2 = null;
            $uploadFile3 = null;
            $uploadFile4 = null;
            $uploadFile5 = null;

            if ($request->hasFile('upload_file_1')) {
                $uploadFile1 = $request->file('upload_file_1')
                    ->store('kap', 'public');
            }

            if ($request->hasFile('upload_file_2')) {
                $uploadFile2 = $request->file('upload_file_2')
                    ->store('kap', 'public');
            }

            if ($request->hasFile('upload_file_3')) {
                $uploadFile3 = $request->file('upload_file_3')
                    ->store('kap', 'public');
            }

            if ($request->hasFile('upload_file_4')) {
                $uploadFile4 = $request->file('upload_file_4')
                    ->store('kap', 'public');
            }

            if ($request->hasFile('upload_file_5')) {
                $uploadFile5 = $request->file('upload_file_5')
                    ->store('kap', 'public');
            }

            RkbuDetailModal::create([
                'id' => Str::uuid()->toString(),
                'rkbu_id' => $rkbu->id,
                'nama_barang' => $request->nama_barang,
                'spesifikasi' => $request->spesifikasi,
                'volume_1' => $volume1,
                'satuan_1' => $request->satuan_1,
                'volume_2' => $volume2,
                'satuan_2' => $request->satuan_2,
                'jumlah_volume' => $jumlahVolume,
                'harga_satuan' => $hargaSatuan,
                'rating' => $request->rating,
                'link_ekatalog'     => $request->link_ekatalog,
                'ppn' => $ppn,
                'total_anggaran' => $totalAnggaran,
                'penempatan' => $request->penempatan,
                'status_komponen' => 'draft',
                'sisa_vol_rkbu' => $jumlahVolume,
                'sisa_anggaran_rkbu' => $totalAnggaran,

                'upload_file_1' => $uploadFile1,
                'upload_file_2' => $uploadFile2,
                'upload_file_3' => $uploadFile3,
                'upload_file_4' => $uploadFile4,
                'upload_file_5' => $uploadFile5,
            ]);

            DB::commit();

            return redirect()
                ->route('rkbu.modal_kantor.index')
                ->with(
                    'success',
                    'Data RKBU berhasil disimpan.'
                );
        } catch (\Exception $e) {

            DB::rollBack();

            dd($e->getMessage());
        }
    }

    public function edit($id)
    {
        $uraian_satu            = UraianSatu::all();
        $uraian_dua             = UraianDua::all();
        $sub_kategori_rkbu      = $this->subKategoriOptions()->get();
        $komponens               = Komponen::all();
        $ppn                    = Ppn::where('status', 'aktif')->first();

        $barjas = RkbuDetailModal::with([
            'rkbu',
            'rkbu.subKegiatan',
            'rkbu.sumberDana'
        ])->findOrFail($id);

        $programs = collect();

        if (auth()->user()->roles->contains('nama_role', 'Administrator')) {

            $programs = Program::orderBy('nama_program')
                ->get();
        }

        return view(
            'rkbu.rkbu_modal.edit',
            compact(
                'barjas',
                'programs',
                'uraian_satu',
                'uraian_dua',
                'sub_kategori_rkbu',
                'ppn'
            ),
            ['routePrefix'           => 'rkbu.modal_kantor',]
        );
    }

    public function submit($id)
    {
        return $this->workflowApprovalService->submit($id);
    }

    public function bulkSubmit(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'required|string|distinct',
        ]);

        $details = RkbuDetailModal::whereIn('id', $validated['ids'])
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

            $response = $this->workflowApprovalService->submit($id);
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
        DB::beginTransaction();

        try {

            $detail = RkbuDetailModal::with('rkbu')
                ->findOrFail($id);

            $request->validate([
                'sub_kategori_rkbu_id' => [
                    'required',
                    'uuid',
                    Rule::in($this->subKategoriOptions()->pluck('id_sub_kategori_rkbu')->all()),
                ],
                'nama_barang'   => 'required',
                'volume_1'      => 'required|numeric|min:1',
                'volume_2'      => 'nullable|numeric|min:1',

                'upload_file_1' => 'nullable|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
                'upload_file_2' => 'nullable|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
                'upload_file_3' => 'nullable|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
                'upload_file_4' => 'nullable|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
                'upload_file_5' => 'nullable|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
            ]);

            $subKategori = $this->subKategoriOptions()->with('kategori')
                ->findOrFail($request->sub_kategori_rkbu_id);

            $volume1 = (float) $request->volume_1;
            $volume2 = (float) ($request->volume_2 ?? 1);

            $jumlahVolume = $volume1 * $volume2;

            $hargaSatuan = (float) str_replace(
                '.',
                '',
                $request->harga_satuan
            );

            $ppn = (float) ($request->ppn ?? 0);

            $subtotal = $jumlahVolume * $hargaSatuan;

            $nilaiPpn = ($subtotal * $ppn) / 100;

            $totalAnggaran = $subtotal + $nilaiPpn;

            $uploadFile1 = $detail->upload_file_1;
            $uploadFile2 = $detail->upload_file_2;
            $uploadFile3 = $detail->upload_file_3;
            $uploadFile4 = $detail->upload_file_4;
            $uploadFile5 = $detail->upload_file_5;

            if ($request->hasFile('upload_file_1')) {
                $uploadFile1 = $request->file('upload_file_1')
                    ->store('kap', 'public');
            }

            if ($request->hasFile('upload_file_2')) {
                $uploadFile2 = $request->file('upload_file_2')
                    ->store('kap', 'public');
            }

            if ($request->hasFile('upload_file_3')) {
                $uploadFile3 = $request->file('upload_file_3')
                    ->store('kap', 'public');
            }

            if ($request->hasFile('upload_file_4')) {
                $uploadFile4 = $request->file('upload_file_4')
                    ->store('kap', 'public');
            }

            if ($request->hasFile('upload_file_5')) {
                $uploadFile5 = $request->file('upload_file_5')
                    ->store('kap', 'public');
            }

            $detail->update([
                'nama_barang'       => $request->nama_barang,
                'spesifikasi'       => $request->spesifikasi,
                'volume_1'          => $volume1,
                'satuan_1'          => $request->satuan_1,
                'volume_2'          => $volume2,
                'satuan_2'          => $request->satuan_2,
                'jumlah_volume'     => $jumlahVolume,
                'harga_satuan'      => $hargaSatuan,
                'ppn'               => $ppn,
                'total_anggaran'    => $totalAnggaran,
                'rating'            => $request->rating,
                'link_ekatalog'     => $request->link_ekatalog,
                'penempatan'        => $request->penempatan,
                'standar_kebutuhan' => $request->standar_kebutuhan,
                'eksisting'         => $request->eksisting,
                'kondisi_baik'      => $request->kondisi_baik,
                'kondisi_rusak_berat' => $request->kondisi_rusak_berat,
                'upload_file_1'     => $uploadFile1,
                'upload_file_2'     => $uploadFile2,
                'upload_file_3'     => $uploadFile3,
                'upload_file_4'     => $uploadFile4,
                'upload_file_5'     => $uploadFile5,
                'keterangan_status' => $request->keterangan_status,

            ]);

            $detail->rkbu->update([
                'jenis_kategori_rkbu_id' => $subKategori->kategori->id_jenis_kategori_rkbu,
                'sub_kategori_rkbu_id' => $subKategori->id_sub_kategori_rkbu,
            ]);

            if (
                auth()->user()
                ->roles
                ->contains('nama_role', 'Administrator')
            ) {

                if ($request->filled('sub_kegiatan_id')) {

                    $subKegiatan = SubKegiatan::findOrFail(
                        $request->sub_kegiatan_id
                    );

                    $detail->rkbu->update([

                        'sub_kegiatan_id'
                        => $subKegiatan->id_sub_kegiatan,

                        'sumber_dana_id'
                        => $subKegiatan->id_sumber_dana,

                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('rkbu.modal_kantor.index')
                ->with(
                    'success',
                    'Data berhasil diperbarui'
                );
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function show($id)
    {
        $barjas = RkbuDetailModal::with(
            'rkbu.subKategori',
            'rkbu.unit',
            'rkbu.user'
        )
            ->findOrFail($id);

        return view('rkbu.rkbu_modal.show', compact('barjas'));
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
        return SubKategoriRkbu::query()->forJenisKategori('Modal');
    }
}
