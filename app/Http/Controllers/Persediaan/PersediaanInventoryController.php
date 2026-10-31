<?php

namespace App\Http\Controllers\Persediaan;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\Persediaan\PersediaanItem;
use App\Models\Persediaan\PersediaanRequest;
use App\Models\Persediaan\PersediaanTransaction;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersediaanInventoryController extends Controller
{
    public function index()
    {
        return $this->renderPage('overview');
    }

    public function barangMasuk()
    {
        return $this->renderPage('barang_masuk');
    }

    public function stok(Request $request)
    {
        $request->validate([
            'sub_kategori_rkbu_id' => ['nullable', 'uuid', 'exists:sub_kategori_rkbus,id_sub_kategori_rkbu'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        return $this->renderPage('stok', $request);
    }

    public function permintaan()
    {
        return $this->renderPage('permintaan');
    }

    public function stokOpname()
    {
        return $this->renderPage('opname');
    }

    public function laporan(Request $request)
    {
        $request->validate([
            'jenis' => ['nullable', 'in:masuk,keluar,opname'],
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date'],
            'unit_id' => ['nullable', 'uuid', 'exists:units,id'],
        ]);
        return $this->renderPage('laporan', $request);
    }

    private function renderPage(string $page, ?Request $filters = null)
    {
        $this->authorizeRead();
        if (in_array($page, ['barang_masuk', 'opname'], true)) {
            $this->authorizeManage();
        }
        $sources = $this->paidPersediaanSources()->orderByDesc('payment.tanggal_pembayaran')->get();

        foreach ($sources as $source) {
            $source->inventory = PersediaanItem::query()->firstOrCreate(
                ['usulan_belanja_item_id' => $source->usulan_belanja_item_id],
                [
                    'kode_barang' => 'PS-' . strtoupper(substr(str_replace('-', '', (string) $source->usulan_belanja_item_id), 0, 12)),
                    'unit_id' => $source->unit_id,
                    'sub_kategori_rkbu_id' => $source->sub_kategori_rkbu_id,
                    'nama_barang' => $source->nama_barang,
                    'spesifikasi' => $source->spesifikasi,
                    'satuan' => $source->satuan,
                    'harga_satuan' => $source->harga_satuan,
                    'ppn' => $source->ppn ?: 0,
                ]
            );
        }
        $receivedBySource = PersediaanTransaction::query()
            ->where('jenis', 'masuk')
            ->whereIn('spj_detail_id', $sources->pluck('spj_detail_id'))
            ->select('spj_detail_id', DB::raw('SUM(jumlah) as received'))
            ->groupBy('spj_detail_id')
            ->pluck('received', 'spj_detail_id');
        foreach ($sources as $source) {
            $source->received_quantity = (float) ($receivedBySource[$source->spj_detail_id] ?? 0);
            $source->remaining_quantity = max(0, (float) $source->volume - $source->received_quantity);
        }

        $visibleItems = $this->canManage() ? $this->scopedItems() : PersediaanItem::query();
        $stockQuery = $visibleItems
            ->when($page === 'stok' && $filters?->filled('sub_kategori_rkbu_id'), fn ($query) => $query->where('sub_kategori_rkbu_id', $filters->input('sub_kategori_rkbu_id')))
            ->when($page === 'stok' && $filters?->filled('search'), function ($query) use ($filters) {
                $term = trim($filters->input('search'));
                $query->where(function ($search) use ($term) {
                    $search->where('nama_barang', 'like', '%' . $term . '%')
                        ->orWhere('kode_barang', 'like', '%' . $term . '%')
                        ->orWhere('spesifikasi', 'like', '%' . $term . '%');
                });
            });
        $inventoryValue = (clone $stockQuery)->sum(DB::raw('stok * COALESCE(harga_satuan, 0) * (1 + COALESCE(ppn, 0) / 100)'));
        $items = $stockQuery->with(['unit', 'subKategoriRkbu'])->orderBy('nama_barang')->paginate(20, ['*'], 'stock_page')->withQueryString();
        $subcategories = DB::table('sub_kategori_rkbus as sub')
            ->join('kategori_rkbus as kategori', 'kategori.id_kategori_rkbu', '=', 'sub.id_kategori_rkbu')
            ->where('kategori.id_jenis_kategori_rkbu', '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c')
            ->orderBy('sub.nama_sub_kategori_rkbu')
            ->get(['sub.id_sub_kategori_rkbu', 'sub.kode_sub_kategori_rkbu', 'sub.nama_sub_kategori_rkbu']);
        $requestableItems = $page === 'permintaan'
            ? PersediaanItem::query()->with(['unit', 'subKategoriRkbu'])->orderBy('nama_barang')->get()
            : collect();
        $requests = $this->scopedRequests()
            ->with(['requester', 'reviewer'])
            ->latest()
            ->limit(100)
            ->get();
        $transactionQuery = PersediaanTransaction::query()
            ->with(['item', 'unit', 'requestingUnit', 'actor', 'spjDetail'])
            ->when(! $this->isAdministrator(), function ($query) {
                $user = auth()->user();
                if ($user->hasRole('Pengurus Barang')) {
                    $query->where('unit_id', $user->unit_id);
                } else {
                    $query->whereHas('request', fn ($request) => $request->where('unit_peminta_id', $user->unit_id));
                }
            })
            ->when($filters?->filled('jenis'), fn ($query) => $query->where('jenis', $filters->input('jenis')))
            ->when($filters?->filled('dari'), fn ($query) => $query->whereDate('tanggal', '>=', $filters->input('dari')))
            ->when($filters?->filled('sampai'), fn ($query) => $query->whereDate('tanggal', '<=', $filters->input('sampai')))
            ->when($filters?->filled('unit_id') && $this->isAdministrator(), fn ($query) => $query->where('unit_id', $filters->input('unit_id')))
            ->latest('tanggal')->latest('created_at');
        $transactions = $page === 'laporan'
            ? $transactionQuery->paginate(50, ['*'], 'transaction_page')->withQueryString()
            : $transactionQuery->limit($page === 'overview' ? 8 : 100)->get();

        $totalItems = $this->canManage() ? $this->scopedItems()->count() : PersediaanItem::query()->count();
        $transactionCount = PersediaanTransaction::query()
            ->when(! $this->isAdministrator(), function ($query) {
                $user = auth()->user();
                if ($user->hasRole('Pengurus Barang')) {
                    $query->where('unit_id', $user->unit_id);
                } else {
                    $query->whereHas('request', fn ($request) => $request->where('unit_peminta_id', $user->unit_id));
                }
            })->count();
        $pendingRequests = $this->scopedRequests()->where('status', 'Menunggu')->count();
        $incomingPending = $sources->where('remaining_quantity', '>', 0)->count();

        return view('persediaan.inventory.index', [
            'title' => match ($page) {
                'barang_masuk' => 'Barang Masuk', 'stok' => 'Stok Barang', 'permintaan' => 'Permintaan Barang',
                'opname' => 'Stok Opname', 'laporan' => 'Laporan Mutasi Persediaan', default => 'Ringkasan Persediaan',
            },
            'page' => $page,
            'sources' => $sources,
            'items' => $items,
            'subcategories' => $subcategories,
            'inventoryValue' => $inventoryValue,
            'requestableItems' => $requestableItems,
            'requests' => $requests,
            'transactions' => $transactions,
            'units' => $this->isAdministrator() ? Unit::query()->orderBy('nama_unit')->get() : collect(),
            'canManage' => $this->canManage(),
            'isAdministrator' => $this->isAdministrator(),
            'totalItems' => $totalItems,
            'transactionCount' => $transactionCount,
            'pendingRequests' => $pendingRequests,
            'incomingPending' => $incomingPending,
        ]);
    }

    public function receive(Request $request)
    {
        $this->authorizeManage();
        $data = $request->validate([
            'spj_detail_id' => ['required', 'uuid'],
            'jumlah' => ['required', 'numeric', 'gt:0'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($data): void {
            $source = $this->paidPersediaanSources()->where('sd.id', $data['spj_detail_id'])->lockForUpdate()->first();
            abort_if(! $source, 404, 'Sumber belanja persediaan yang sudah dibayar tidak ditemukan.');
            $item = PersediaanItem::query()
                ->where('usulan_belanja_item_id', $source->usulan_belanja_item_id)
                ->lockForUpdate()->firstOrFail();
            $received = (float) PersediaanTransaction::query()
                ->where('spj_detail_id', $source->spj_detail_id)->where('jenis', 'masuk')->sum('jumlah');
            $quantity = (float) $data['jumlah'];
            abort_if($received + $quantity > (float) $source->volume + 0.0005, 422, 'Jumlah barang masuk melebihi volume pada usulan belanja.');

            $before = (float) $item->stok;
            $after = $before + $quantity;
            $item->update(['stok' => $after]);
            PersediaanTransaction::query()->create([
                'persediaan_item_id' => $item->id,
                'unit_id' => $item->unit_id,
                'spj_detail_id' => $source->spj_detail_id,
                'jenis' => 'masuk',
                'jumlah' => $quantity,
                'stok_sebelum' => $before,
                'stok_sesudah' => $after,
                'tanggal' => $data['tanggal'],
                'keterangan' => $data['keterangan'] ?: 'Penerimaan dari ' . $source->no_surat_pesanan,
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Barang masuk berhasil dicatat dan stok telah diperbarui.');
    }

    public function requestIssue(Request $request)
    {
        $this->authorizeRead();
        $user = auth()->user();
        abort_unless(filled($user->unit_id), 403, 'Akun peminta harus terhubung ke unit.');
        $data = $request->validate([
            'persediaan_item_id' => ['required', 'uuid'],
            'jumlah' => ['required', 'numeric', 'gt:0'],
            'keperluan' => ['required', 'string', 'max:2000'],
        ]);
        $item = PersediaanItem::query()->whereKey($data['persediaan_item_id'])->firstOrFail();

        PersediaanRequest::query()->create([
            'persediaan_item_id' => $item->id,
            'unit_peminta_id' => $user->unit_id,
            'requested_by' => $user->id,
            'jumlah' => $data['jumlah'],
            'keperluan' => $data['keperluan'],
            'status' => 'Menunggu',
        ]);

        return back()->with('success', 'Permintaan pengeluaran barang dikirim untuk diproses Pengurus Barang.');
    }

    public function approveRequest(Request $request, string $id)
    {
        $this->authorizeManage();
        $data = $request->validate(['catatan_verifikasi' => ['nullable', 'string', 'max:2000']]);
        DB::transaction(function () use ($id, $data): void {
            $issue = $this->scopedRequests()->whereKey($id)->lockForUpdate()->firstOrFail();
            abort_unless($issue->status === 'Menunggu', 422, 'Permintaan sudah diproses.');
            $item = $this->scopedItems()->whereKey($issue->persediaan_item_id)->lockForUpdate()->firstOrFail();
            $before = (float) $item->stok;
            $quantity = (float) $issue->jumlah;
            abort_if($quantity > $before, 422, 'Stok tidak mencukupi untuk memenuhi permintaan ini.');
            $after = $before - $quantity;
            $item->update(['stok' => $after]);
            $issue->update([
                'status' => 'Disetujui', 'reviewed_by' => auth()->id(), 'reviewed_at' => now(),
                'catatan_verifikasi' => $data['catatan_verifikasi'] ?? null,
            ]);
            PersediaanTransaction::query()->create([
                'persediaan_item_id' => $item->id,
                'unit_id' => $item->unit_id,
                'unit_peminta_id' => $issue->unit_peminta_id,
                'persediaan_request_id' => $issue->id,
                'jenis' => 'keluar',
                'jumlah' => -$quantity,
                'stok_sebelum' => $before,
                'stok_sesudah' => $after,
                'tanggal' => now()->toDateString(),
                'keterangan' => 'Pengeluaran untuk unit ' . $issue->unit->nama_unit . ': ' . $issue->keperluan,
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Permintaan disetujui dan barang keluar tercatat pada kartu stok.');
    }

    public function rejectRequest(Request $request, string $id)
    {
        $this->authorizeManage();
        $data = $request->validate(['catatan_verifikasi' => ['required', 'string', 'max:2000']]);
        DB::transaction(function () use ($id, $data): void {
            $issue = $this->scopedRequests()->whereKey($id)->lockForUpdate()->firstOrFail();
            abort_unless($issue->status === 'Menunggu', 422, 'Permintaan sudah diproses.');
            $issue->update([
                'status' => 'Ditolak', 'reviewed_by' => auth()->id(), 'reviewed_at' => now(),
                'catatan_verifikasi' => $data['catatan_verifikasi'],
            ]);
        });

        return back()->with('success', 'Permintaan barang ditolak dengan catatan.');
    }

    public function stocktake(Request $request, string $id)
    {
        $this->authorizeManage();
        $data = $request->validate([
            'stok_fisik' => ['required', 'numeric', 'min:0'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string', 'max:2000'],
        ]);
        DB::transaction(function () use ($id, $data): void {
            $item = $this->scopedItems()->whereKey($id)->lockForUpdate()->firstOrFail();
            $before = (float) $item->stok;
            $after = (float) $data['stok_fisik'];
            $item->update(['stok' => $after]);
            PersediaanTransaction::query()->create([
                'persediaan_item_id' => $item->id,
                'unit_id' => $item->unit_id,
                'jenis' => 'opname',
                'jumlah' => $after - $before,
                'stok_sebelum' => $before,
                'stok_sesudah' => $after,
                'tanggal' => $data['tanggal'],
                'keterangan' => $data['keterangan'],
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Stok opname dicatat dan saldo stok disesuaikan.');
    }

    private function paidPersediaanSources(): Builder
    {
        $paymentSummary = DB::table('master_spjs')
            ->select('spj_id')
            ->selectRaw('MAX(tanggal_pembayaran) as tanggal_pembayaran')
            ->selectRaw('MAX(no_surat_pesanan) as no_surat_pesanan')
            ->groupBy('spj_id');

        return DB::table('spj_details as sd')
            ->join('spjs as spj', 'spj.id', '=', 'sd.spj_id')
            ->leftJoinSub($paymentSummary, 'payment', fn ($join) => $join->on('payment.spj_id', '=', 'spj.id'))
            ->join('usulan_belanja_items as item', 'item.id', '=', 'sd.usulan_belanja_item_id')
            ->join('rkbus as rkbu', 'rkbu.id', '=', 'sd.rkbu_id')
            ->join('rkbu_detail_persediaans as product', function ($join) {
                $join->on('product.id', '=', 'item.detail_id')
                    ->orOn(function ($fallback) {
                        $fallback->on('product.rkbu_id', '=', 'rkbu.id')
                            ->whereNull('item.detail_id')
                            ->whereRaw('(item.harga_satuan IS NULL OR product.harga_satuan = item.harga_satuan)')
                            ->whereRaw("(item.spesifikasi IS NULL OR COALESCE(product.spesifikasi, '') = COALESCE(item.spesifikasi, ''))")
                            ->whereRaw("(SELECT COUNT(*) FROM rkbu_detail_persediaans candidate WHERE candidate.rkbu_id = rkbu.id AND (item.harga_satuan IS NULL OR candidate.harga_satuan = item.harga_satuan) AND (item.spesifikasi IS NULL OR COALESCE(candidate.spesifikasi, '') = COALESCE(item.spesifikasi, ''))) = 1");
                    });
            })
            ->join('units as unit', 'unit.id', '=', 'rkbu.unit_id')
            ->join('sub_kategori_rkbus as sub', 'sub.id_sub_kategori_rkbu', '=', 'rkbu.sub_kategori_rkbu_id')
            ->where('rkbu.jenis_kategori_rkbu_id', '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c')
            ->where(function ($query) {
                $query->whereRaw("LOWER(TRIM(COALESCE(spj.status_spj, ''))) IN (?, ?)", ['sudah di bayar', 'sudah dibayar'])
                    ->orWhereExists(function ($payment) {
                        $payment->selectRaw('1')
                            ->from('master_spjs as paid')
                            ->whereColumn('paid.spj_id', 'spj.id')
                            ->whereRaw("LOWER(TRIM(COALESCE(paid.status_pembayaran, ''))) IN (?, ?)", ['sudah di bayar', 'sudah dibayar']);
                });
            })
            ->when(! $this->isAdministrator(), function ($query) {
                $user = auth()->user();
                $query->where(function ($scope) use ($user) {
                    $scope->where('rkbu.unit_id', $user->unit_id)
                        ->when($user->hasRole('Pengurus Barang'), fn ($scope) => $scope->orWhere('spj.validator_pengurus_barang_id', $user->id));
                });
            })
            ->select([
                'sd.id as spj_detail_id', 'sd.volume', 'spj.id as spj_id', 'spj.no_bast_serah_terima_unit',
                'payment.tanggal_pembayaran',
                DB::raw('COALESCE(payment.no_surat_pesanan, spj.no_surat_pesanan) as no_surat_pesanan'),
                'item.id as usulan_belanja_item_id', 'rkbu.unit_id',
                'rkbu.nomor_rkbu', 'rkbu.sub_kategori_rkbu_id', 'unit.nama_unit', 'product.nama_barang',
                'product.spesifikasi', 'product.satuan_1 as satuan', 'item.harga_satuan', 'item.ppn',
                'sub.nama_sub_kategori_rkbu', 'sub.kode_sub_kategori_rkbu',
            ])
            ->distinct();
    }

    private function scopedItems()
    {
        $user = auth()->user();

        return PersediaanItem::query()->when(! $this->isAdministrator(), function ($query) use ($user) {
            $query->where(function ($scope) use ($user) {
                $scope->where('unit_id', $user->unit_id);

                if ($user->hasRole('Pengurus Barang')) {
                    $scope->orWhereIn('usulan_belanja_item_id', DB::table('spj_details as sd')
                        ->join('spjs as spj', 'spj.id', '=', 'sd.spj_id')
                        ->where('spj.validator_pengurus_barang_id', $user->id)
                        ->select('sd.usulan_belanja_item_id'));
                }
            });
        });
    }

    private function scopedRequests()
    {
        return PersediaanRequest::query()->with(['unit', 'item'])->when(! $this->isAdministrator(), function ($query) {
            $user = auth()->user();
            if ($user->hasRole('Pengurus Barang')) {
                $query->whereHas('item', fn ($item) => $item->where('unit_id', $user->unit_id));
            } else {
                $query->where('unit_peminta_id', $user->unit_id);
            }
        });
    }

    private function authorizeRead(): void
    {
        $user = auth()->user();
        abort_unless($user && ($this->isAdministrator() || filled($user->unit_id)), 403, 'Akun harus terhubung ke unit untuk mengakses persediaan.');
    }

    private function authorizeManage(): void
    {
        $this->authorizeRead();
        abort_unless($this->isAdministrator() || auth()->user()->hasRole('Pengurus Barang'), 403, 'Hanya Pengurus Barang dan Administrator yang dapat memproses stok.');
    }

    private function canManage(): bool
    {
        return $this->isAdministrator() || auth()->user()->hasRole('Pengurus Barang');
    }

    private function isAdministrator(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('Administrator') || $user->hasRole('Super Admin'));
    }
}
