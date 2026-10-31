<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\Models\Asset\Asset;
use App\Models\Asset\AssetHistory;
use App\Models\Asset\Penempatan;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\MasterBackend\SettingUser\Unit;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AssetModuleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAssetAccess();
        $perPage = in_array((int) $request->input('per_page'), [10, 25, 50, 100], true)
            ? (int) $request->input('per_page')
            : 10;
        $assetQuery = $this->scopedAssets()
            ->with(['unit', 'rkbu'])
            ->where(function ($query) {
                $query->whereNull('status_reklas_arb')->orWhere('status_reklas_arb', '!=', 'Reklas');
            })
            ->when($request->filled('tahun'), fn ($query) => $query->whereYear('assets.created_at', $request->input('tahun')))
            ->when($request->filled('kondisi'), fn ($query) => $query->where('kondisi_asset', $request->input('kondisi')))
            ->when($request->filled('tahun_perolehan'), fn ($query) => $query->where('tahun_perolehan', $request->input('tahun_perolehan')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = trim($request->input('search'));
                $query->where(function ($search) use ($term) {
                    $search->where('nama_asset', 'like', '%' . $term . '%')
                        ->orWhere('no_register', 'like', '%' . $term . '%')
                        ->orWhere('kode_asset', 'like', '%' . $term . '%')
                        ->orWhere('merk', 'like', '%' . $term . '%')
                        ->orWhere('type', 'like', '%' . $term . '%');
                });
            });
        $years = $this->scopedAssets()->whereNotNull('created_at')->selectRaw('YEAR(created_at) as year')->distinct()->orderByDesc('year')->pluck('year');
        $acquisitionYears = $this->scopedAssets()->whereNotNull('tahun_perolehan')->distinct()->orderByDesc('tahun_perolehan')->pluck('tahun_perolehan');
        $assets = $assetQuery
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'assets_page')
            ->withQueryString();
        $reclassificationItems = $this->scopedAssets()
            ->with(['unit', 'histories' => fn ($query) => $query->where('action', 'deletion_proposed')->latest()])
            ->where('status_reklas_arb', 'Reklas')
            ->orderByDesc('updated_at')
            ->paginate(10, ['*'], 'reclass_page')
            ->withQueryString();
        $assets->getCollection()->concat($reclassificationItems->getCollection())
            ->unique('id_asset')->each(fn (Asset $asset) => $this->ensureAssetQrCode($asset));
        $pendingItems = $this->eligiblePurchaseItems()
            ->orderByDesc('payment.tanggal_pembayaran')
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        return view('asset_module.index', [
            'title' => 'Modul Aset Barang',
            'assets' => $assets,
            'years' => $years,
            'acquisitionYears' => $acquisitionYears,
            'perPage' => $perPage,
            'reclassificationItems' => $reclassificationItems,
            'pendingItems' => $pendingItems,
            'isAdministrator' => $this->isAdministrator(),
        ]);
    }

    public function printQrCodes(Request $request)
    {
        $this->authorizeAssetAccess();
        $validated = $request->validate([
            'asset_ids' => ['required', 'array', 'min:1', 'max:200'],
            'asset_ids.*' => ['required', 'uuid', 'distinct'],
        ]);
        $assets = $this->scopedAssets()
            ->whereIn('id_asset', $validated['asset_ids'])
            ->orderBy('nama_asset')
            ->get();
        abort_if($assets->isEmpty(), 404, 'Aset yang dipilih tidak ditemukan.');
        $assets->each(fn (Asset $asset) => $this->ensureAssetQrCode($asset));

        return view('asset_module.print_qr', ['title' => 'Cetak QR Aset', 'assets' => $assets]);
    }

    public function historyIndex()
    {
        $this->authorizeAssetAccess();
        $histories = AssetHistory::query()
            ->with('user')
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('asset_unit_id', auth()->user()->unit_id))
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('asset_module.history', ['title' => 'Riwayat Aset', 'histories' => $histories]);
    }

    public function create(string $spjDetailId)
    {
        $this->authorizeAssetAccess();
        $item = $this->eligiblePurchaseItems()->where('sd.id', $spjDetailId)->first();
        abort_if(! $item, 404, 'Item SPJ tidak ditemukan atau belum memenuhi syarat registrasi aset.');

        return view('asset_module.form', [
            'title' => 'Registrasi Aset dari Usulan Belanja',
            'asset' => null,
            'sourceItem' => $item,
            'isManual' => false,
            'units' => collect(),
            'users' => $this->assignmentUsers(),
            'penempatans' => $this->penempatanOptions(),
        ]);
    }

    public function manualCreate()
    {
        $this->authorizeAssetAccess();

        return view('asset_module.form', [
            'title' => 'Input Aset Hibah / Manual',
            'asset' => null,
            'sourceItem' => null,
            'isManual' => true,
            'units' => $this->isAdministrator()
                ? Unit::query()->orderBy('nama_unit')->get()
                : Unit::query()->whereKey(auth()->user()->unit_id)->get(),
            'users' => $this->assignmentUsers(),
            'penempatans' => $this->penempatanOptions(),
        ]);
    }

    public function manualStore(Request $request)
    {
        $this->authorizeAssetAccess();
        $rules = array_merge($this->assetRules(), [
            'nama_asset' => ['required', 'string', 'max:255'],
            'satuan' => ['nullable', 'string', 'max:255'],
            'spek' => ['nullable', 'string', 'max:5000'],
            'harga_asset' => ['required', 'numeric', 'min:0'],
            'jumlah_asset' => ['required', 'numeric', 'gt:0'],
            'tahun_perolehan' => ['required', 'integer', 'min:1900', 'max:' . (now()->year + 1)],
            'tgl_asset' => ['required', 'date'],
            'penyedia' => ['nullable', 'string', 'max:255'],
            'hibah' => ['required', 'string', 'max:1000'],
            'sumber_anggaran' => ['nullable', 'string', 'max:255'],
            'unit_id' => $this->isAdministrator()
                ? ['required', 'uuid', Rule::exists('units', 'id')]
                : ['prohibited'],
        ]);
        $data = $request->validate($rules);
        $data = $this->applyAssignmentData($data);

        $photoPath = $request->file('foto')?->store('assets/photos', 'public');
        $sptjmPath = $request->file('upload_sptjm')?->store('assets/sptjm', 'public');
        unset($data['foto'], $data['upload_sptjm']);
        $unitId = $this->isAdministrator() ? $data['unit_id'] : auth()->user()->unit_id;
        $register = ($data['no_register'] ?? null) ?: 'AST-' . $data['tahun_perolehan'] . '-' . Str::upper(Str::random(8));
        $assetId = (string) Str::uuid();

        $asset = DB::transaction(function () use ($data, $unitId, $register, $photoPath, $sptjmPath, $assetId) {
            $asset = Asset::query()->create(array_merge($data, [
                'id_asset' => $assetId,
                'unit_id' => $unitId,
                'registered_by' => auth()->id(),
                'no_register' => $register,
                'total_anggaran_asset' => round((float) $data['harga_asset'] * (float) $data['jumlah_asset'], 2),
                'sumber_anggaran' => ($data['sumber_anggaran'] ?? null) ?: 'Hibah',
                'foto' => $photoPath,
                'upload_sptjm' => $sptjmPath,
                'status_asset' => 'Aktif',
                'status_registrasi' => 'terdaftar',
                'link_detail' => route('asset.scan', $assetId),
            ]));
            $this->recordAssetHistory($asset, 'created', 'Aset hibah/manual dicatat.', null, $asset->getAttributes());

            return $asset;
        });
        $this->ensureAssetQrCode($asset);

        return redirect()->route('asset-module.show', $asset)->with('success', 'Aset hibah berhasil dicatat secara manual.');
    }

    public function store(Request $request, string $spjDetailId)
    {
        $this->authorizeAssetAccess();
        $data = $request->validate($this->assetRules());
        $data = $this->applyAssignmentData($data);

        $asset = DB::transaction(function () use ($request, $data, $spjDetailId) {
            $source = $this->eligiblePurchaseItems()
                ->where('sd.id', $spjDetailId)
                ->lockForUpdate()
                ->first();
            abort_if(! $source, 404, 'Item SPJ tidak ditemukan atau belum memenuhi syarat registrasi aset.');

            $id = (string) Str::uuid();
            $year = (int) \Illuminate\Support\Carbon::parse($source->tgl_barang_datang ?: $source->tanggal_pembayaran)->year;
            $photoPath = $request->file('foto')?->store('assets/photos', 'public');
            $sptjmPath = $request->file('upload_sptjm')?->store('assets/sptjm', 'public');
            unset($data['foto'], $data['upload_sptjm']);
            $quantity = (float) ($source->volume_diterima ?? 0);
            $price = (float) ($source->harga_satuan ?? 0);
            $ppn = (float) ($source->ppn ?? 0);

            $asset = Asset::query()->create(array_merge($data, [
                'id_asset' => $id,
                'unit_id' => $source->unit_id,
                'id_jenis_kategori_rkbu' => $source->jenis_kategori_rkbu_id,
                'rkbu_id' => $source->rkbu_id,
                'usulan_belanja_item_id' => $source->usulan_belanja_item_id,
                'spj_detail_id' => $source->spj_detail_id,
                'spj_id' => $source->spj_id,
                'registered_by' => auth()->id(),
                'kode_asset' => $data['kode_asset'] ?? $source->kode_jenis_kategori,
                'nama_asset' => $source->nama_barang,
                'satuan' => $source->satuan,
                'spek' => $source->spesifikasi,
                'harga_asset' => $price,
                'tahun_perolehan' => $year,
                'tgl_asset' => $source->tgl_barang_datang ?: $source->tanggal_pembayaran,
                'penyedia' => $source->nama_perusahaan,
                'id_barang' => $source->kode_sub_kategori,
                'jumlah_asset' => $quantity,
                'total_anggaran_asset' => round($price * $quantity * (1 + $ppn / 100), 2),
                'sumber_anggaran' => $source->nama_sumber_dana,
                'no_register' => ($data['no_register'] ?? null) ?: 'AST-' . $year . '-' . Str::upper(Str::random(8)),
                'foto' => $photoPath,
                'upload_sptjm' => $sptjmPath,
                'status_asset' => 'Aktif',
                'status_registrasi' => 'terdaftar',
                'link_detail' => route('asset.scan', $id),
            ]));
            $this->recordAssetHistory($asset, 'created', 'Aset diregistrasikan dari item SPJ yang telah diterima dan dibayar.', null, $asset->getAttributes());

            return $asset;
        });
        $this->ensureAssetQrCode($asset);

        return redirect()->route('asset-module.index')->with('success', 'Barang berhasil diregistrasikan sebagai aset. Sumber RKBU dan SPJ tersimpan untuk penelusuran.');
    }

    public function show(string $id)
    {
        $this->authorizeAssetAccess();
        $asset = $this->scopedAssets()->with(['unit', 'rkbu', 'spj', 'histories.user'])->whereKey($id)->firstOrFail();
        $this->ensureAssetQrCode($asset);

        return view('asset_module.show', ['title' => 'Detail Aset', 'asset' => $asset, 'histories' => $asset->histories->sortByDesc('created_at')]);
    }

    public function scan(string $id)
    {
        $asset = Asset::query()->with(['unit', 'rkbu'])->whereKey($id)->firstOrFail();

        return view('asset_module.public_show', ['asset' => $asset]);
    }

    public function qrImage(string $id)
    {
        $asset = Asset::query()->whereKey($id)->firstOrFail();
        $this->ensureAssetQrCode($asset);
        $disk = Storage::disk('public');

        abort_unless($disk->exists($asset->qrcode_path), 404, 'QR code aset belum tersedia.');

        return response($disk->get($asset->qrcode_path), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function edit(string $id)
    {
        $this->authorizeAssetAccess();
        $asset = $this->scopedAssets()->whereKey($id)->firstOrFail();

        return view('asset_module.form', [
            'title' => 'Ubah Data Aset',
            'asset' => $asset,
            'sourceItem' => null,
            'isManual' => false,
            'units' => collect(),
            'users' => $this->assignmentUsers(),
            'penempatans' => $this->penempatanOptions(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $this->authorizeAssetAccess();
        $asset = $this->scopedAssets()->whereKey($id)->firstOrFail();
        $before = $asset->getOriginal();
        $data = $request->validate($this->assetRules($asset->id_asset));
        $data = $this->applyAssignmentData($data);
        unset($data['foto'], $data['upload_sptjm']);
        $asset->fill($data);

        if ($request->hasFile('foto')) {
            if ($asset->foto) Storage::disk('public')->delete($asset->foto);
            $asset->foto = $request->file('foto')->store('assets/photos', 'public');
        }
        if ($request->hasFile('upload_sptjm')) {
            if ($asset->upload_sptjm) Storage::disk('public')->delete($asset->upload_sptjm);
            $asset->upload_sptjm = $request->file('upload_sptjm')->store('assets/sptjm', 'public');
        }
        DB::transaction(function () use ($asset, $before): void {
            $asset->save();
            $this->recordAssetHistory($asset, 'updated', 'Data aset diperbarui.', $before, $asset->getAttributes());
        });

        return redirect()->route('asset-module.show', $asset)->with('success', 'Data aset berhasil diperbarui.');
    }

    public function proposeDeletion(Request $request, string $id)
    {
        $this->authorizeAssetAccess();
        $asset = $this->scopedAssets()->whereKey($id)->firstOrFail();
        abort_unless($asset->kondisi_asset === 'Rusak Berat', 422, 'Usulan penghapusan hanya dapat diajukan untuk aset berkondisi rusak berat.');
        abort_if($asset->status_reklas_arb === 'Reklas', 422, 'Usulan penghapusan aset ini sudah pernah diajukan.');
        $data = $request->validate(['alasan' => ['required', 'string', 'max:2000']]);
        $before = $asset->getOriginal();
        DB::transaction(function () use ($asset, $data, $before): void {
            $asset->forceFill(['status_reklas_arb' => 'Reklas'])->save();
            $this->recordAssetHistory(
                $asset,
                'deletion_proposed',
                'Usulan penghapusan diajukan: ' . $data['alasan'],
                $before,
                $asset->getAttributes()
            );
        });

        return redirect()->route('asset-module.index')->with('success', 'Usulan penghapusan dicatat. Aset dipindahkan dari daftar aktif dan riwayat aset tetap tersimpan.');
    }

    private function eligiblePurchaseItems(): Builder
    {
        return DB::table('spj_details as sd')
            ->join('spjs as spj', 'spj.id', '=', 'sd.spj_id')
            ->join('master_spjs as payment', 'payment.spj_id', '=', 'spj.id')
            ->join('usulan_belanja_items as item', 'item.id', '=', 'sd.usulan_belanja_item_id')
            ->join('rkbus as rkbu', 'rkbu.id', '=', 'sd.rkbu_id')
            ->leftJoin('rkbu_detail_modals as modal', function ($join) {
                $join->on('modal.id', '=', 'item.detail_id')->where('item.detail_type', '=', 'modal');
            })
            ->leftJoin('rkbu_detail_modal_alkes as alkes', function ($join) {
                $join->on('alkes.id', '=', 'item.detail_id')->where('item.detail_type', '=', 'modal_alkes');
            })
            ->leftJoin('jenis_kategori_rkbus as jenis', 'jenis.id_jenis_kategori_rkbu', '=', 'rkbu.jenis_kategori_rkbu_id')
            ->leftJoin('sub_kategori_rkbus as subkategori', 'subkategori.id_sub_kategori_rkbu', '=', 'rkbu.sub_kategori_rkbu_id')
            ->leftJoin('sumber_danas as sumber', 'sumber.id_sumber_dana', '=', 'rkbu.sumber_dana_id')
            ->leftJoin('perusahaans as perusahaan', 'perusahaan.id_perusahaan', '=', 'payment.perusahaan_id')
            ->leftJoin('units as unit', 'unit.id', '=', 'rkbu.unit_id')
            ->whereIn('item.detail_type', ['modal', 'modal_alkes'])
            ->where(function ($query) {
                $query->where('spj.status_verifikasi_pengurus_barang', 'Selesai')
                    ->orWhere('payment.status_verifikasi_pengurus_barang', 'Selesai');
            })
            ->where(function ($query) {
                $query->whereRaw("LOWER(TRIM(COALESCE(payment.status_pembayaran, ''))) IN (?, ?, ?)", [
                    'selesai',
                    'sudah di bayar',
                    'sudah dibayar',
                ])->orWhereRaw(
                    'COALESCE(payment.pembayaran, 0) >= (
                        SELECT COALESCE(SUM(paid_item.harga_satuan * paid_detail.volume * (1 + COALESCE(paid_item.ppn, 0) / 100)), 0)
                        FROM spj_details as paid_detail
                        INNER JOIN usulan_belanja_items as paid_item ON paid_item.id = paid_detail.usulan_belanja_item_id
                        WHERE paid_detail.spj_id = spj.id
                    ) - 0.01'
                );
            })
            ->whereNotExists(fn ($query) => $query->selectRaw('1')->from('assets')->whereColumn('assets.spj_detail_id', 'sd.id'))
            ->when(! $this->isAdministrator(), function ($query) {
                $user = auth()->user();
                $query->where(function ($scope) use ($user) {
                    $scope->where('rkbu.unit_id', $user->unit_id)
                        ->orWhere('spj.validator_pengurus_barang_id', $user->id);
                });
            })
            ->select([
                'sd.id as spj_detail_id',
                'sd.volume as volume_diterima',
                'spj.id as spj_id',
                'spj.tgl_barang_datang',
                'spj.no_bast_serah_terima_unit',
                'spj.nama_penerima_barang',
                'payment.tanggal_pembayaran',
                'payment.status_pembayaran',
                'payment.no_surat_pesanan',
                'perusahaan.nama_perusahaan',
                'item.id as usulan_belanja_item_id',
                'item.rkbu_id',
                'item.harga_satuan',
                'item.ppn',
                'rkbu.unit_id',
                'unit.nama_unit',
                'rkbu.jenis_kategori_rkbu_id',
                'rkbu.nomor_rkbu',
                'jenis.kode_jenis_kategori_rkbu as kode_jenis_kategori',
                'subkategori.kode_sub_kategori_rkbu as kode_sub_kategori',
                'sumber.nama_sumber_dana',
                DB::raw("COALESCE(modal.nama_barang, alkes.nama_barang) as nama_barang"),
                DB::raw("COALESCE(modal.spesifikasi, alkes.spesifikasi) as spesifikasi"),
                DB::raw("COALESCE(modal.satuan_1, alkes.satuan_1) as satuan"),
            ]);
    }

    private function ensureAssetQrCode(Asset $asset): void
    {
        $disk = Storage::disk('public');
        $detailUrl = route('asset.scan', $asset->id_asset);
        if ($asset->qrcode_path && $disk->exists($asset->qrcode_path) && $asset->link_detail === $detailUrl) {
            return;
        }

        $qrCode = QrCode::create($detailUrl)
            ->setSize(420)
            ->setMargin(12)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High);
        $result = (new PngWriter())->write($qrCode);
        $path = 'assets/qrcodes/' . $asset->id_asset . '.png';
        $saved = $disk->put($path, $result->getString());
        abort_unless($saved, 500, 'QR code aset gagal disimpan. Periksa konfigurasi penyimpanan aplikasi.');

        $asset->forceFill([
            'qrcode_path' => $path,
            'link_detail' => $detailUrl,
        ])->save();
    }

    private function recordAssetHistory(Asset $asset, string $action, string $description, ?array $before, ?array $after): void
    {
        AssetHistory::query()->create([
            'asset_id' => $asset->id_asset,
            'asset_unit_id' => $after['unit_id'] ?? $before['unit_id'] ?? $asset->unit_id,
            'asset_name' => $asset->nama_asset,
            'asset_register' => $asset->no_register,
            'action' => $action,
            'description' => $description,
            'before_data' => $before,
            'after_data' => $after,
            'user_id' => auth()->id(),
        ]);
    }

    private function scopedAssets()
    {
        return Asset::query()->when(! $this->isAdministrator(), function ($query) {
            $user = auth()->user();
            $query->where(function ($scope) use ($user) {
                $scope->where('assets.unit_id', $user->unit_id)
                    ->orWhere('assets.registered_by', $user->id)
                    ->orWhereExists(function ($spjs) use ($user) {
                        $spjs->selectRaw('1')
                            ->from('spjs')
                            ->whereColumn('spjs.id', 'assets.spj_id')
                            ->where('spjs.validator_pengurus_barang_id', $user->id);
                    });
            });
        });
    }

    private function authorizeAssetAccess(): void
    {
        abort_unless(
            $this->isAdministrator() || (auth()->user()->hasRole('Pengurus Barang') && filled(auth()->user()->unit_id)),
            403,
            'Modul Aset hanya dapat diakses Pengurus Barang dan Administrator.'
        );
    }

    private function isAdministrator(): bool
    {
        return auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin');
    }

    private function unitScope(): ?string
    {
        return $this->isAdministrator() ? null : auth()->user()->unit_id;
    }

    private function assetRules(?string $assetId = null): array
    {
        $registerRule = Rule::unique('assets', 'no_register');
        if ($assetId) {
            $registerRule->ignore($assetId, 'id_asset');
        }

        return [
            'kode_asset' => ['nullable', 'string', 'max:255'],
            'no_register' => ['nullable', 'string', 'max:255', $registerRule],
            'merk' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'kondisi_asset' => ['required', Rule::in(['Baik', 'Rusak Ringan', 'Rusak Berat', 'Belum Dinilai'])],
            'pengguna_asset' => ['nullable', 'string', 'max:255'],
            'nip_pengguna' => ['nullable', 'string', 'max:255'],
            'jabatan_pengguna' => ['nullable', 'string', 'max:255'],
            'lokasi_asset' => ['nullable', 'string', 'max:255'],
            'penanggung_jawab' => ['nullable', 'string', 'max:255'],
            'penanggung_jawab_user_id' => ['nullable', 'uuid', Rule::exists('users', 'id')],
            'id_penempatan' => ['nullable', 'uuid', Rule::exists('penempatans', 'id_penempatan')],
            'kapitalisasi' => ['nullable', 'string', 'max:255'],
            'tgl_bpkb' => ['nullable', 'date'],
            'no_rangka' => ['nullable', 'string', 'max:255'],
            'no_mesin' => ['nullable', 'string', 'max:255'],
            'no_polisi' => ['nullable', 'string', 'max:255'],
            'hibah' => ['nullable', 'string', 'max:255'],
            'kategori_asset_bergerak' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'upload_sptjm' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    private function assignmentUsers()
    {
        return User::query()->with('position')->where('status_user', 'aktif')->orderBy('nama')->get();
    }

    private function penempatanOptions()
    {
        return Penempatan::query()
            ->orderBy('lokasi_barang')->orderBy('gedung')->orderBy('tempat_lokasi')->get();
    }

    private function applyAssignmentData(array $data): array
    {
        $user = filled($data['penanggung_jawab_user_id'] ?? null)
            ? User::query()->with('position')->findOrFail($data['penanggung_jawab_user_id'])
            : null;
        $location = filled($data['id_penempatan'] ?? null)
            ? Penempatan::query()->findOrFail($data['id_penempatan'])
            : null;

        $data['penanggung_jawab'] = $user?->nama;
        $data['jabatan_pengguna'] = $user?->position?->nama_jabatan;
        $data['id_penempatan'] = $location?->id_penempatan;
        $data['lokasi_asset'] = $location
            ? collect([$location->lokasi_barang, $location->gedung, $location->tempat_lokasi])->filter()->implode(' - ')
            : null;

        return $data;
    }
}
