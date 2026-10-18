<?php

namespace App\Http\Controllers\Rkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\Komponen;
use App\Models\MasterBackend\SettingInput\Ppn;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\Rkbu\Rkbu;
use App\Models\Rkbu\RkbuDetailModal;
use App\Models\Rkbu\RkbuDetailPegawai;
use App\Models\Rkbu\RkbuDetailPersediaan;
use App\Support\Rkbu\RkbuListFilters;
use App\Services\Workflow\WorkflowApprovalService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RkbuModuleController extends Controller
{
    private const FILE_FIELDS = [
        'upload_file_1',
        'upload_file_2',
        'upload_file_3',
        'upload_file_4',
        'upload_file_5',
    ];

    private const MODULES = [
        'pegawai' => [
            'route' => 'pegawai',
            'title' => 'RKBU Pegawai',
            'model' => RkbuDetailPegawai::class,
            'quantity' => 'koefisien_remunerasi',
            'fields' => [
                ['name' => 'nama_pegawai', 'label' => 'Nama Pegawai', 'type' => 'text'],
                ['name' => 'tempat_lahir', 'label' => 'Tempat Lahir', 'type' => 'text'],
                ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date'],
                ['name' => 'pendidikan', 'label' => 'Pendidikan', 'type' => 'text'],
                ['name' => 'jabatan', 'label' => 'Jabatan', 'type' => 'text'],
                ['name' => 'status_kawin', 'label' => 'Status Kawin', 'type' => 'text'],
                ['name' => 'nomor_kontrak', 'label' => 'Nomor Kontrak', 'type' => 'text'],
                ['name' => 'tmt_pegawai', 'label' => 'TMT Pegawai', 'type' => 'text'],
                ['name' => 'bulan_tmt', 'label' => 'Bulan TMT', 'type' => 'text'],
                ['name' => 'tahun_tmt', 'label' => 'Tahun TMT', 'type' => 'text'],
                ['name' => 'gaji_pokok', 'label' => 'Gaji Pokok', 'type' => 'number'],
                ['name' => 'remunerasi', 'label' => 'Remunerasi', 'type' => 'number'],
                ['name' => 'koefisien_remunerasi', 'label' => 'Koefisien Remunerasi', 'type' => 'number', 'required' => true, 'min' => 0.01],
                ['name' => 'koefisien_gaji', 'label' => 'Koefisien Gaji', 'type' => 'number', 'required' => true, 'min' => 0.01],
                ['name' => 'bpjs_kesehatan', 'label' => 'BPJS Kesehatan', 'type' => 'number'],
                ['name' => 'bpjs_tk', 'label' => 'BPJS Ketenagakerjaan', 'type' => 'number'],
                ['name' => 'bpjs_jht', 'label' => 'BPJS JHT', 'type' => 'number'],
            ],
            'columns' => [
                'nama_pegawai' => 'Nama Pegawai',
                'jabatan' => 'Jabatan',
                'jumlah_volume' => 'Jumlah Bulan',
                'total_anggaran' => 'Total Anggaran',
            ],
        ],
        'persediaan' => [
            'route' => 'persediaan',
            'title' => 'RKBU Persediaan',
            'model' => RkbuDetailPersediaan::class,
            'quantity' => 'rencana_pengadaan_tahun_x1',
            'fields' => [
                ['name' => 'nama_barang', 'label' => 'Nama Barang', 'type' => 'text', 'required' => true],
                ['name' => 'spesifikasi', 'label' => 'Spesifikasi', 'type' => 'textarea'],
                ['name' => 'satuan_1', 'label' => 'Satuan', 'type' => 'text'],
                ['name' => 'harga_satuan', 'label' => 'Harga Satuan', 'type' => 'number', 'required' => true],
                ['name' => 'ppn', 'label' => 'PPN (%)', 'type' => 'number'],
                ['name' => 'rating', 'label' => 'Rating', 'type' => 'text'],
                ['name' => 'link_ekatalog', 'label' => 'Link E-Katalog', 'type' => 'url'],
                ['name' => 'penempatan', 'label' => 'Penempatan', 'type' => 'text'],
                ['name' => 'stok', 'label' => 'Stok', 'type' => 'number'],
                ['name' => 'rata_rata_pemakaian', 'label' => 'Rata-rata Pemakaian', 'type' => 'number'],
                ['name' => 'kebutuhan_per_bulan', 'label' => 'Kebutuhan per Bulan', 'type' => 'number'],
                ['name' => 'buffer', 'label' => 'Buffer', 'type' => 'number'],
                ['name' => 'pengadaan_sebelumnya', 'label' => 'Pengadaan Sebelumnya', 'type' => 'number'],
                ['name' => 'proyeksi_sisa_stok', 'label' => 'Proyeksi Sisa Stok', 'type' => 'number'],
                ['name' => 'kebutuhan_plus_buffer', 'label' => 'Kebutuhan + Buffer', 'type' => 'number'],
                ['name' => 'kebutuhan_tahun_x1', 'label' => 'Kebutuhan Tahun X1', 'type' => 'number'],
                ['name' => 'rencana_pengadaan_tahun_x1', 'label' => 'Rencana Pengadaan Tahun X1', 'type' => 'number', 'required' => true, 'min' => 1],
                ['name' => 'keterangan_status', 'label' => 'Keterangan Status', 'type' => 'text'],
                ['name' => 'upload_file_1', 'label' => 'Lampiran KAP 1', 'type' => 'file'],
                ['name' => 'upload_file_2', 'label' => 'Lampiran KAP 2', 'type' => 'file'],
                ['name' => 'upload_file_3', 'label' => 'Lampiran KAP 3', 'type' => 'file'],
                ['name' => 'upload_file_4', 'label' => 'Lampiran KAP 4', 'type' => 'file'],
                ['name' => 'upload_file_5', 'label' => 'Lampiran KAP 5', 'type' => 'file'],
            ],
            'columns' => [
                'nama_barang' => 'Nama Barang',
                'jumlah_volume' => 'Jumlah Volume',
                'harga_satuan' => 'Harga Satuan',
                'total_anggaran' => 'Total Anggaran',
            ],
        ],
        'modal_kantor' => [
            'route' => 'modal-kantor',
            'title' => 'RKBU Modal Kantor',
            'model' => RkbuDetailModal::class,
            'category' => ['kantor', 'peralatan kantor'],
            'quantity' => 'volume',
            'fields' => self::MODAL_FIELDS,
            'columns' => [
                'nama_barang' => 'Nama Barang',
                'jumlah_volume' => 'Jumlah Volume',
                'harga_satuan' => 'Harga Satuan',
                'total_anggaran' => 'Total Anggaran',
            ],
        ],
        'modal_alkes' => [
            'route' => 'modal-alkes',
            'title' => 'RKBU Modal Alkes',
            'model' => RkbuDetailModal::class,
            'category' => ['alkes', 'alat kesehatan'],
            'quantity' => 'volume',
            'fields' => self::MODAL_FIELDS,
            'columns' => [
                'nama_barang' => 'Nama Barang',
                'jumlah_volume' => 'Jumlah Volume',
                'harga_satuan' => 'Harga Satuan',
                'total_anggaran' => 'Total Anggaran',
            ],
        ],
    ];

    private const MODAL_FIELDS = [
        ['name' => 'nama_barang', 'label' => 'Nama Barang', 'type' => 'text', 'required' => true],
        ['name' => 'spesifikasi', 'label' => 'Spesifikasi', 'type' => 'textarea'],
        ['name' => 'volume_1', 'label' => 'Volume 1', 'type' => 'number', 'required' => true, 'min' => 1],
        ['name' => 'satuan_1', 'label' => 'Satuan 1', 'type' => 'text'],
        ['name' => 'volume_2', 'label' => 'Volume 2', 'type' => 'number', 'min' => 1],
        ['name' => 'satuan_2', 'label' => 'Satuan 2', 'type' => 'text'],
        ['name' => 'harga_satuan', 'label' => 'Harga Satuan', 'type' => 'number', 'required' => true],
        ['name' => 'ppn', 'label' => 'PPN (%)', 'type' => 'number'],
        ['name' => 'rating', 'label' => 'Rating', 'type' => 'text'],
        ['name' => 'link_ekatalog', 'label' => 'Link E-Katalog', 'type' => 'url'],
        ['name' => 'penempatan', 'label' => 'Penempatan', 'type' => 'text'],
        ['name' => 'standar_kebutuhan', 'label' => 'Standar Kebutuhan', 'type' => 'number'],
        ['name' => 'eksisting', 'label' => 'Eksisting', 'type' => 'number'],
        ['name' => 'kondisi_baik', 'label' => 'Kondisi Baik', 'type' => 'text'],
        ['name' => 'kondisi_rusak_berat', 'label' => 'Kondisi Rusak Berat', 'type' => 'text'],
        ['name' => 'keterangan_status', 'label' => 'Keterangan Status', 'type' => 'text'],
        ['name' => 'upload_file_1', 'label' => 'Lampiran KAP 1', 'type' => 'file'],
        ['name' => 'upload_file_2', 'label' => 'Lampiran KAP 2', 'type' => 'file'],
        ['name' => 'upload_file_3', 'label' => 'Lampiran KAP 3', 'type' => 'file'],
        ['name' => 'upload_file_4', 'label' => 'Lampiran KAP 4', 'type' => 'file'],
        ['name' => 'upload_file_5', 'label' => 'Lampiran KAP 5', 'type' => 'file'],
    ];

    public function index(Request $request)
    {
        $module = $this->module($request);
        $config = self::MODULES[$module];
        $detailRelation = match ($config['model']) {
            RkbuDetailPegawai::class => 'detailPegawai',
            RkbuDetailPersediaan::class => 'detailPersediaan',
            RkbuDetailModal::class => 'detailModal',
            default => 'detailBarjas',
        };
        $filters = $request->validate([
            'sub_kategori_rkbu_id' => ['nullable', 'uuid', 'exists:sub_kategori_rkbus,id_sub_kategori_rkbu'],
        ]);
        $subcategories = RkbuListFilters::availableSubcategories(
            $this->subcategories($module),
            $detailRelation
        );
        $selectedSubcategory = $filters['sub_kategori_rkbu_id'] ?? null;

        abort_if(
            $selectedSubcategory && ! $subcategories->contains('id_sub_kategori_rkbu', $selectedSubcategory),
            404
        );

        $model = $config['model'];

        $records = $this->visibleRecords($module)
            ->when($selectedSubcategory, fn(Builder $query) => $query->whereHas(
                'rkbu',
                fn(Builder $rkbuQuery) => $rkbuQuery->where('sub_kategori_rkbu_id', $selectedSubcategory)
            ))
            ->with('rkbu')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('rkbu.modules.index', compact('config', 'records', 'subcategories', 'selectedSubcategory'), [
            'title' => $config['title'],
        ]);
    }

    public function create(Request $request)
    {
        $module = $this->module($request);
        $config = self::MODULES[$module];
        $subcategories = $this->subcategories($module)->get();

        return view('rkbu.modules.form', [
            'config' => $config,
            'record' => null,
            'subcategories' => $subcategories,
            'action' => route('rkbu.' . $config['route'] . '.store'),
            'method' => 'POST',
            ...$this->formOptions($config),
        ]);
    }

    public function store(Request $request)
    {
        $module = $this->module($request);
        $config = self::MODULES[$module];
        $validated = $request->validate($this->rules($config) + [
            'sub_kategori_rkbu_id' => ['required', 'uuid', 'exists:sub_kategori_rkbus,id_sub_kategori_rkbu'],
        ]);

        $subcategory = $this->subcategories($module)
            ->findOrFail($validated['sub_kategori_rkbu_id']);
        $category = $subcategory->kategori;
        $categoryType = $category?->jenis_kategori_rkbu;

        abort_if(! $category || ! $categoryType, 422, 'Kategori RKBU tidak lengkap.');

        $record = DB::transaction(function () use ($request, $config, $subcategory, $category, $categoryType) {
            $sumberDana = SumberDana::query()->firstOrFail();
            $tahunAnggaranId = Session::get('tahun_anggaran_id');
            $unitId = Session::get('unit_id');

            abort_if(! $tahunAnggaranId || ! $unitId, 422, 'Tahun anggaran atau unit kerja belum dipilih.');

            $rkbu = Rkbu::create([
                'id' => (string) Str::uuid(),
                'nomor_rkbu' => $this->generateNomorRkbu(),
                'tahun_anggaran_id' => $tahunAnggaranId,
                'unit_id' => $unitId,
                'sumber_dana_id' => $sumberDana->id_sumber_dana,
                'jenis_belanja_id' => $categoryType->id_jenis_belanja,
                'jenis_kategori_rkbu_id' => $category->id_jenis_kategori_rkbu,
                'sub_kategori_rkbu_id' => $subcategory->id_sub_kategori_rkbu,
                'created_by' => auth()->id(),
                'status' => 'draft',
            ]);

            $values = $this->detailValues($request, $config, null);
            $values['id'] = (string) Str::uuid();
            $values['rkbu_id'] = $rkbu->id;

            return $config['model']::create($values);
        });

        return redirect()
            ->route('rkbu.' . $config['route'] . '.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(Request $request, string $id)
    {
        $module = $this->module($request);
        $config = self::MODULES[$module];
        $record = $this->visibleRecords($module)->with('rkbu')->findOrFail($id);

        return view('rkbu.modules.show', compact('config', 'record'));
    }

    public function edit(Request $request, string $id)
    {
        $module = $this->module($request);
        $config = self::MODULES[$module];
        $record = $this->editableRecord($module, $id);

        return view('rkbu.modules.form', [
            'config' => $config,
            'record' => $record,
            'subcategories' => collect(),
            'action' => route('rkbu.' . $config['route'] . '.update', $record->id),
            'method' => 'PUT',
            ...$this->formOptions($config),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $module = $this->module($request);
        $config = self::MODULES[$module];
        $record = $this->editableRecord($module, $id);
        $request->validate($this->rules($config));

        $previousFiles = [];
        foreach (self::FILE_FIELDS as $fileField) {
            $previousFiles[$fileField] = $record->{$fileField};
        }

        DB::transaction(function () use ($request, $record, $config) {
            $record->update($this->detailValues($request, $config));
        });

        foreach ($previousFiles as $fileField => $path) {
            if ($path && $record->{$fileField} !== $path) {
                Storage::disk('public')->delete($path);
            }
        }

        return redirect()
            ->route('rkbu.' . $config['route'] . '.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Request $request, string $id)
    {
        $module = $this->module($request);
        $config = self::MODULES[$module];
        $record = $this->editableRecord($module, $id);

        $files = collect(self::FILE_FIELDS)
            ->map(fn($field) => $record->{$field})
            ->filter()
            ->all();

        $record->rkbu->delete();

        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }

        return redirect()
            ->route('rkbu.' . $config['route'] . '.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    public function submit(Request $request, string $id, WorkflowApprovalService $workflowApprovalService)
    {
        $module = $this->module($request);
        $config = self::MODULES[$module];
        $this->ownedRecords($module)->findOrFail($id);

        $response = $workflowApprovalService->submit($id, $config['model']);

        if ($request->expectsJson()) {
            return $response;
        }

        $payload = $response->getData(true);
        $flashType = ($payload['success'] ?? false) ? 'success' : 'error';

        return redirect()
            ->route('rkbu.' . $config['route'] . '.index')
            ->with($flashType, $payload['message'] ?? 'Pengajuan gagal diproses.');
    }

    public function bulkSubmit(Request $request, WorkflowApprovalService $workflowApprovalService)
    {
        $module = $this->module($request);
        $config = self::MODULES[$module];
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1', 'max:100'],
            'ids.*' => ['required', 'string', 'distinct'],
        ]);

        $records = $this->ownedRecords($module)
            ->whereHas('rkbu', fn(Builder $query) => $query->whereIn('status', ['draft', 'revision', 'rejected']))
            ->whereIn('id', $validated['ids'])
            ->get()
            ->keyBy('id');

        $results = [];
        foreach ($validated['ids'] as $id) {
            if (! $records->has($id)) {
                $results[] = [
                    'id' => $id,
                    'success' => false,
                    'message' => 'Data tidak ditemukan, bukan milik Anda, atau statusnya tidak dapat diajukan.',
                ];

                continue;
            }

            $response = $workflowApprovalService->submit($id, $config['model']);
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
        ]);
    }

    private function module(Request $request): string
    {
        $module = $request->route('rkbuModule');

        abort_unless(is_string($module) && isset(self::MODULES[$module]), 404);

        return $module;
    }

    private function ownedRecords(string $module): Builder
    {
        return $this->recordsForModule($module)
            ->whereHas('rkbu', fn(Builder $query) => $query->where('created_by', auth()->id()));
    }

    private function visibleRecords(string $module): Builder
    {
        $query = $this->recordsForModule($module);

        if (! auth()->user()->hasRole('Administrator')) {
            $query->whereHas('rkbu', fn(Builder $rkbuQuery) => $rkbuQuery->where('created_by', auth()->id()));
        }

        return $query;
    }

    private function recordsForModule(string $module): Builder
    {
        $config = self::MODULES[$module];
        $query = $config['model']::query()
            ->whereHas('rkbu', fn(Builder $rkbuQuery) => $rkbuQuery
                ->where('tahun_anggaran_id', Session::get('tahun_anggaran_id')));

        if (isset($config['category'])) {
            $this->applyCategoryFilter($query, $config['category']);
        }

        return $query;
    }

    private function editableRecord(string $module, string $id): Model
    {
        $record = $this->ownedRecords($module)->with('rkbu')->findOrFail($id);

        abort_unless(in_array($record->rkbu->status, ['draft', 'revision', 'rejected'], true), 403);

        return $record;
    }

    private function subcategories(string $module): Builder
    {
        $query = SubKategoriRkbu::query()
            ->where('status', 'aktif')
            ->with('kategori.jenis_kategori_rkbu')
            ->whereHas('kategori.jenis_kategori_rkbu');

        if (isset(self::MODULES[$module]['category'])) {
            $this->applyLabelFilter($query, self::MODULES[$module]['category']);
        }

        return $query->orderBy('nama_sub_kategori_rkbu');
    }

    private function applyCategoryFilter(Builder $query, array|string $category): void
    {
        $query->whereHas('rkbu.subKategoriRkbu', function (Builder $subcategoryQuery) use ($category) {
            $this->applyLabelFilter($subcategoryQuery, $category);
        });
    }

    private function applyLabelFilter(Builder $query, array|string $categories): void
    {
        $categories = is_array($categories) ? $categories : [$categories];

        $query->where(function (Builder $labelQuery) use ($categories) {
            foreach ($categories as $index => $category) {
                $pattern = '%' . $category . '%';
                $condition = function (Builder $termQuery) use ($pattern) {
                    $termQuery->where('nama_sub_kategori_rkbu', 'like', $pattern)
                        ->orWhereHas('kategori', function (Builder $categoryQuery) use ($pattern) {
                            $categoryQuery->where('nama_kategori_rkbu', 'like', $pattern)
                                ->orWhereHas('jenis_kategori_rkbu', function (Builder $typeQuery) use ($pattern) {
                                    $typeQuery->where('nama_jenis_kategori_rkbu', 'like', $pattern);
                                });
                        });
                };

                if ($index === 0) {
                    $labelQuery->where($condition);
                } else {
                    $labelQuery->orWhere($condition);
                }
            }
        });
    }

    private function rules(array $config): array
    {
        $rules = [];

        foreach ($config['fields'] as $field) {
            $name = $field['name'];
            $required = ! empty($field['required']);

            if ($field['type'] === 'file') {
                $rules[$name] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx', 'max:5120'];
            } elseif ($required) {
                $rules[$name] = $field['type'] === 'number'
                    ? array_values(array_filter([
                        'required',
                        'numeric',
                        'min:' . ($field['min'] ?? 0),
                        $name === 'ppn' || str_starts_with($name, 'bpjs_') ? 'max:100' : null,
                    ]))
                    : ['required', 'string', 'max:255'];
            } elseif ($field['type'] === 'number') {
                $rules[$name] = array_values(array_filter([
                    'nullable',
                    'numeric',
                    'min:' . ($field['min'] ?? 0),
                    $name === 'ppn' || str_starts_with($name, 'bpjs_') ? 'max:100' : null,
                ]));
            } elseif ($field['type'] === 'url') {
                $rules[$name] = ['nullable', 'url', 'max:255'];
            } elseif ($field['type'] === 'date') {
                $rules[$name] = ['nullable', 'date'];
            } else {
                $rules[$name] = ['nullable', 'string', 'max:255'];
            }
        }

        return $rules;
    }

    private function formOptions(array $config): array
    {
        $fieldNames = array_column($config['fields'], 'name');
        $options = [];

        if (in_array('nama_barang', $fieldNames, true)) {
            $options['komponens'] = Komponen::all();
        }

        if (in_array('satuan_1', $fieldNames, true)) {
            $options['uraian_satu'] = UraianSatu::all();
        }

        if (in_array('ppn', $fieldNames, true)) {
            $options['ppn'] = Ppn::query()->where('status', 'aktif')->firstOrFail();
        }

        return $options;
    }

    private function detailValues(Request $request, array $config): array
    {
        $fieldNames = array_column($config['fields'], 'name');
        $values = $request->only($fieldNames);

        foreach (self::FILE_FIELDS as $fileField) {
            unset($values[$fileField]);
            if ($request->hasFile($fileField)) {
                $values[$fileField] = $request->file($fileField)->store('kap', 'public');
            }
        }

        if ($config['quantity'] === 'volume') {
            $volume1 = (int) $request->input('volume_1', 0);
            $volume2 = (int) ($request->input('volume_2') ?: 1);
            $quantity = $volume1 * $volume2;
            $values['volume_1'] = $volume1;
            $values['volume_2'] = $volume2;
        } elseif ($config['model'] === RkbuDetailPegawai::class) {
            $quantity = (int) ($request->input('koefisien_remunerasi') ?: 1);
        } else {
            $quantity = (int) ($request->input($config['quantity']) ?: 1);
        }

        $values['jumlah_volume'] = $quantity;

        if ($config['model'] === RkbuDetailPegawai::class) {
            $gajiPokok = (float) $request->input('gaji_pokok', 0);
            $remunerasi = (float) $request->input('remunerasi', 0);
            $koefisienGaji = (float) $request->input('koefisien_gaji', 0);
            $koefisienRemunerasi = (float) $request->input('koefisien_remunerasi', 0);
            $totalGajiPokok = $gajiPokok * $koefisienGaji;
            $totalRemunerasi = $remunerasi * $koefisienRemunerasi;
            $bpjsTotal = $gajiPokok * (
                (float) $request->input('bpjs_kesehatan', 0)
                + (float) $request->input('bpjs_tk', 0)
                + (float) $request->input('bpjs_jht', 0)
            ) / 100 * $koefisienGaji;

            $values['jumlah_volume'] = $koefisienRemunerasi;
            $values['total_gaji_pokok'] = $totalGajiPokok;
            $values['total_remunerasi'] = $totalRemunerasi;
            $values['total_anggaran'] = $totalGajiPokok + $totalRemunerasi + $bpjsTotal;
        } else {
            $values['ppn'] = in_array('ppn', $fieldNames, true)
                ? (float) Ppn::query()->where('status', 'aktif')->firstOrFail()->ppn
                : 0;
            $values['harga_satuan'] = (float) ($request->input('harga_satuan') ?? 0);
            $subtotal = $quantity * $values['harga_satuan'];
            $total = $subtotal + ($subtotal * $values['ppn'] / 100);
            $values['total_anggaran'] = $total;
        }

        $values['status_komponen'] = 'draft';
        $values['sisa_vol_rkbu'] = $quantity;
        $values['sisa_anggaran_rkbu'] = $values['total_anggaran'];

        return $values;
    }

    private function generateNomorRkbu(): string
    {
        $year = now()->year;
        $sequence = Rkbu::whereYear('created_at', $year)->count() + 1;

        do {
            $number = sprintf('RKBU-%s-%04d', $year, $sequence++);
        } while (Rkbu::where('nomor_rkbu', $number)->exists());

        return $number;
    }
}
