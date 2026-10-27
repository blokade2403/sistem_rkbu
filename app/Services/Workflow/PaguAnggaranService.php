<?php

namespace App\Services\Workflow;

use App\Models\MasterBackend\SettingInput\PaguAnggaran;
use App\Models\MasterBackend\SettingInput\PaguAnggaranHistory;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingRkbu\RekeningBelanja;
use App\Models\Rkbu\Rkbu;
use App\Models\UsulanBelanja\UsulanBelanja;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaguAnggaranService
{
    private const DETAIL_TABLES = [
        'rkbu_detail_barjass',
        'rkbu_detail_modals',
        'rkbu_detail_modal_alkes',
        'rkbu_detail_persediaans',
        'rkbu_detail_pegawais',
    ];

    public function phaseOrder(?string $tahunAnggaranId = null): int
    {
        $tahun = TahunAnggaran::query()
            ->with('fase')
            ->when($tahunAnggaranId, fn ($query) => $query->whereKey($tahunAnggaranId))
            ->when(! $tahunAnggaranId, fn ($query) => $query->where('status', 'aktif'))
            ->first();

        return (int) ($tahun?->fase?->urutan ?? 1);
    }

    public function isAdministrator($user = null): bool
    {
        $user ??= auth()->user();

        return $user && $user->roles()->whereIn('nama_role', ['Administrator', 'Super Admin'])->exists();
    }

    public function assertRkbuEditableByCurrentUser(Rkbu $rkbu): void
    {
        if ($this->phaseOrder($rkbu->tahun_anggaran_id) >= 2 && ! $this->isAdministrator()) {
            abort(403, 'Pada fase perubahan, perubahan RKBU harus dilakukan oleh Administrator.');
        }
    }

    public function assertCreateAllowedByCurrentUser(?string $tahunAnggaranId = null): void
    {
        if ($this->phaseOrder($tahunAnggaranId) >= 2 && ! $this->isAdministrator()) {
            abort(403, 'Pada fase perubahan, penambahan RKBU harus dilakukan oleh Administrator.');
        }
    }

    public function resolveAccountId(Rkbu $rkbu): ?string
    {
        $rkbu->loadMissing(['subKategoriRkbu.sub_kategori_rekening', 'subKegiatan', 'jenisBelanja']);
        // Match the full displayed subcategory code first. The related subcategory
        // ledger can be only a broad code (for example 5.1), shared by many accounts.
        $subcategoryCode = trim((string) $rkbu->subKategoriRkbu?->kode_sub_kategori_rkbu, '.');
        if ($subcategoryCode !== '') {
            $exactAccountId = DB::table('rekening_belanjas')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->where('rekening_belanjas.kode_rekening_belanja', $subcategoryCode)
                ->when($rkbu->sub_kegiatan_id, fn ($query) => $query->where('sub_kegiatans.id_sub_kegiatan', $rkbu->sub_kegiatan_id))
                ->when($rkbu->sumber_dana_id, fn ($query) => $query->where('sub_kegiatans.id_sumber_dana', $rkbu->sumber_dana_id))
                ->value('rekening_belanjas.id_kode_rekening_belanja');
            if ($exactAccountId) {
                return (string) $exactAccountId;
            }
        }

        // Keep compatibility with legacy rows that stored a direct account ID.
        $direct = $rkbu->getAttribute('id_kode_rekening_belanja');
        if ($direct) {
            return (string) $direct;
        }

        // Barjas and other grouped RKBU records use the ledger code stored on
        // jenis_belanja, while their subcategory may be a more detailed code.
        $jenisBelanjaCode = trim((string) $rkbu->jenisBelanja?->kode_jenis_belanja, '.');
        $subcategoryMajorCode = implode('.', array_slice(explode('.', $subcategoryCode), 0, 2));
        $jenisBelanjaMajorCode = implode('.', array_slice(explode('.', $jenisBelanjaCode), 0, 2));
        if ($jenisBelanjaCode !== '' && $jenisBelanjaMajorCode === $subcategoryMajorCode) {
            $jenisBelanjaAccountId = DB::table('rekening_belanjas')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->where('rekening_belanjas.kode_rekening_belanja', $jenisBelanjaCode)
                ->when($rkbu->sub_kegiatan_id, fn ($query) => $query->where('sub_kegiatans.id_sub_kegiatan', $rkbu->sub_kegiatan_id))
                ->when($rkbu->sumber_dana_id, fn ($query) => $query->where('sub_kegiatans.id_sumber_dana', $rkbu->sumber_dana_id))
                ->value('rekening_belanjas.id_kode_rekening_belanja');
            if ($jenisBelanjaAccountId) {
                return (string) $jenisBelanjaAccountId;
            }
        }

        // If there is no exact ledger for the detailed subcategory, preserve the
        // existing subactivity/source based resolution using its broader ledger.
        $subAccountCode = $rkbu->subKategoriRkbu?->sub_kategori_rekening?->kode_sub_kategori_rekening
            ?: ($subcategoryCode !== '' ? rtrim($subcategoryCode, 'Xx.') : null);
        if (! $subAccountCode) {
            return null;
        }

        $query = DB::table('rekening_belanjas')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
            ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->where(function ($query) use ($subAccountCode) {
                $query->where('rekening_belanjas.kode_rekening_belanja', 'like', $subAccountCode . '%')
                    ->orWhereRaw('? LIKE CONCAT(rekening_belanjas.kode_rekening_belanja, ".%")', [$subAccountCode]);
            })
            ->when($rkbu->sub_kegiatan_id, fn ($query) => $query->where('sub_kegiatans.id_sub_kegiatan', $rkbu->sub_kegiatan_id))
            ->when($rkbu->sumber_dana_id, fn ($query) => $query->where('sub_kegiatans.id_sumber_dana', $rkbu->sumber_dana_id))
            ->orderByRaw('LENGTH(rekening_belanjas.kode_rekening_belanja) DESC');

        return $query->value('rekening_belanjas.id_kode_rekening_belanja');
    }

    public function detailTotal(Rkbu $rkbu): float
    {
        return collect(self::DETAIL_TABLES)->sum(fn ($table) => (float) DB::table($table)
            ->where('rkbu_id', $rkbu->id)
            ->sum('total_anggaran'));
    }

    public function totalRkbuForAccount(string $tahunId, string $accountId, ?string $excludeRkbuId = null): float
    {
        $total = 0.0;
        $pagu = $this->paguForAccount($tahunId, $accountId);
        if (! $pagu) {
            return $total;
        }
        $accountCodes = RekeningBelanja::query()->pluck('kode_rekening_belanja', 'id_kode_rekening_belanja');
        $rkbUs = Rkbu::query()
            ->where('tahun_anggaran_id', $tahunId)
            ->whereNotIn('status', ['rejected'])
            ->when($excludeRkbuId, fn ($query) => $query->where('id', '<>', $excludeRkbuId))
            ->with(['subKategoriRkbu.sub_kategori_rekening', 'subKegiatan.sumber_danas', 'sumberDana', 'rekeningBelanja.aktivitas.sub_kegiatan.sumber_danas', 'jenisBelanja']);

        foreach ($rkbUs->cursor() as $rkbu) {
            if ($this->accountIsWithinPagu($this->resolveAccountId($rkbu), $pagu, $accountCodes)
                && $this->rkbuMatchesFundingSource($rkbu, $pagu->jenis_sumber_dana)) {
                $total += $this->detailTotal($rkbu);
            }
        }

        return $total;
    }

    public function approvedRkbuForAccount(string $tahunId, string $accountId): float
    {
        $total = 0.0;
        $rkbUs = Rkbu::query()
            ->where('tahun_anggaran_id', $tahunId)
            ->where('status', 'approved')
            ->with(['subKategoriRkbu.sub_kategori_rekening', 'subKegiatan', 'jenisBelanja']);

        foreach ($rkbUs->cursor() as $rkbu) {
            if ($this->resolveAccountId($rkbu) === $accountId) {
                $total += $this->detailTotal($rkbu);
            }
        }

        return $total;
    }

    public function accountSummary(?string $tahunId, string $module = 'all'): array
    {
        if (! $tahunId) {
            return [];
        }

        $pagus = PaguAnggaran::query()
            ->with('rekeningBelanja.aktivitas.sub_kegiatan.sumber_danas')
            ->where('tahun_anggaran_id', $tahunId)
            ->get();
        $rkbUs = Rkbu::query()
            ->where('tahun_anggaran_id', $tahunId)
            ->with([
                'subKategoriRkbu.kategori.jenis_kategori_rkbu',
                'jenisKategoriRkbu',
                'sumberDana',
                'subKegiatan.sumber_danas',
                'rekeningBelanja.aktivitas.sub_kegiatan.sumber_danas',
                'jenisBelanja',
            ])
            ->get();
        $accountCodes = RekeningBelanja::query()
            ->pluck('kode_rekening_belanja', 'id_kode_rekening_belanja');

        return $pagus->map(function (PaguAnggaran $pagu) use ($rkbUs, $module, $accountCodes) {
            if (! $this->paguMatchesModule($pagu, $module)) {
                return null;
            }

            $approvedTotal = 0.0;

            foreach ($rkbUs as $rkbu) {
                if (
                    ! $this->accountIsWithinPagu($this->resolveAccountId($rkbu), $pagu, $accountCodes)
                    || ! $this->rkbuMatchesModule($rkbu, $module)
                    || ! $this->rkbuMatchesFundingSource($rkbu, $pagu->jenis_sumber_dana)
                ) {
                    continue;
                }

                if ($rkbu->status === 'approved') {
                    $approvedTotal += $this->detailTotal($rkbu);
                }
            }

            return [
                    'rekening' => trim(($pagu->rekeningBelanja?->kode_rekening_belanja ?? '') . ' - ' . ($pagu->rekeningBelanja?->nama_rekening_belanja ?? '')),
                    'pagu' => (float) $pagu->jumlah_pagu,
                    'approved_total' => $approvedTotal,
                    'selisih' => (float) $pagu->jumlah_pagu - $approvedTotal,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function accountIsWithinPagu(?string $rkbuAccountId, PaguAnggaran $pagu, $accountCodes): bool
    {
        if (! $rkbuAccountId) {
            return false;
        }

        if ($rkbuAccountId === $pagu->id_kode_rekening_belanja) {
            return true;
        }

        $paguCode = trim((string) $pagu->rekeningBelanja?->kode_rekening_belanja, '.');
        $rkbuCode = trim((string) $accountCodes->get($rkbuAccountId), '.');

        return $paguCode !== ''
            && $rkbuCode !== ''
            && str_starts_with($rkbuCode, $paguCode . '.');
    }

    private function rkbuMatchesModule(Rkbu $rkbu, string $module): bool
    {
        if ($module === 'all') {
            return true;
        }

        $category = mb_strtolower((string) (
            $rkbu->jenisKategoriRkbu?->nama_jenis_kategori_rkbu
            ?? $rkbu->subKategoriRkbu?->kategori?->jenis_kategori_rkbu?->nama_jenis_kategori_rkbu
        ));

        $keywords = match ($module) {
            'barjas', 'persediaan' => ['barang jasa', 'barang dan jasa', 'barang persediaan', 'persediaan'],
            'pegawai' => ['pegawai'],
            // The combined modal approval page must include every modal class,
            // including equipment and machinery categories without "kantor"/"alkes".
            'modal' => ['modal'],
            'modal-kantor', 'modal-alkes' => ['modal kantor', 'modal kantor lainnya', 'modal alat kesehatan', 'modal alkes'],
            default => [],
        };

        return collect($keywords)->contains(fn (string $keyword) => str_contains($category, $keyword));
    }

    private function paguMatchesModule(PaguAnggaran $pagu, string $module): bool
    {
        if ($module === 'all') {
            return true;
        }

        $label = mb_strtolower(implode(' ', [
            $pagu->rekeningBelanja?->kode_rekening_belanja ?? '',
            $pagu->rekeningBelanja?->nama_rekening_belanja ?? '',
        ]));

        return match ($module) {
            'barjas', 'persediaan' => str_contains($label, 'barang dan jasa')
                || str_contains($label, 'barang/jasa'),
            'pegawai' => str_contains($label, 'belanja pegawai') || preg_match('/\\b5\\.1\\.01\\b/', $label) === 1,
            'modal', 'modal-kantor', 'modal-alkes' => str_contains($label, 'belanja modal')
                || preg_match('/\\b5\\.2(?:\\.|\\b)/', $label) === 1,
            default => false,
        };
    }

    private function rkbuMatchesFundingSource(Rkbu $rkbu, string $paguFundingSource): bool
    {
        $source = mb_strtolower((string) (
            $rkbu->sumberDana?->nama_sumber_dana
            ?? $rkbu->subKegiatan?->sumber_danas?->nama_sumber_dana
            ?? $rkbu->rekeningBelanja?->aktivitas?->sub_kegiatan?->sumber_danas?->nama_sumber_dana
        ));
        $paguSource = mb_strtolower($paguFundingSource);
        if ($source === '') {
            return false;
        }

        $isBlud = str_contains($source, 'blud') && ! str_contains($source, 'non blud');

        if (str_contains($paguSource, 'non blud')) {
            return ! $isBlud;
        }

        if (str_contains($paguSource, 'blud')) {
            return $isBlud;
        }

        return $source === $paguSource;
    }

    public function totalUsulanForAccount(string $tahunId, string $accountId, ?string $excludeUsulanId = null): float
    {
        $pagu = $this->paguForAccount($tahunId, $accountId);
        if (! $pagu) {
            return 0.0;
        }
        $accountCodes = RekeningBelanja::query()->pluck('kode_rekening_belanja', 'id_kode_rekening_belanja');
        $total = 0.0;
        $items = DB::table('usulan_belanja_items as items')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'items.usulan_belanja_id')
            ->join('rkbus', 'rkbus.id', '=', 'items.rkbu_id')
            ->where('rkbus.tahun_anggaran_id', $tahunId)
            ->whereIn('usulan.status_usulan', ['proses', 'approved'])
            ->when($excludeUsulanId, fn ($query) => $query->where('usulan.id', '<>', $excludeUsulanId))
            ->select('items.*', 'rkbus.id as related_rkbu_id')
            ->get();
        $rkbUs = Rkbu::query()->with(['subKategoriRkbu.sub_kategori_rekening', 'subKegiatan.sumber_danas', 'sumberDana', 'rekeningBelanja.aktivitas.sub_kegiatan.sumber_danas', 'jenisBelanja'])
            ->whereIn('id', $items->pluck('related_rkbu_id')->unique())->get()->keyBy('id');

        foreach ($items as $item) {
            $rkbu = $rkbUs->get($item->related_rkbu_id);
            if ($rkbu && $this->accountIsWithinPagu($this->resolveAccountId($rkbu), $pagu, $accountCodes)
                && $this->rkbuMatchesFundingSource($rkbu, $pagu->jenis_sumber_dana)) {
                $subtotal = (float) $item->harga_satuan * (float) $item->volume;
                $total += $subtotal + ($subtotal * (float) $item->ppn / 100);
            }
        }

        return $total;
    }

    /** Resolve a pagu set on this account or on its nearest parent account. */
    private function paguForAccount(string $tahunId, string $accountId): ?PaguAnggaran
    {
        $accountCode = trim((string) RekeningBelanja::query()
            ->where('id_kode_rekening_belanja', $accountId)
            ->value('kode_rekening_belanja'), '.');

        if ($accountCode === '') {
            return null;
        }

        return PaguAnggaran::query()
            ->with('rekeningBelanja')
            ->where('tahun_anggaran_id', $tahunId)
            ->lockForUpdate()
            ->get()
            ->filter(function (PaguAnggaran $pagu) use ($accountId, $accountCode) {
                $paguCode = trim((string) $pagu->rekeningBelanja?->kode_rekening_belanja, '.');

                return $pagu->id_kode_rekening_belanja === $accountId
                    || ($paguCode !== '' && str_starts_with($accountCode, $paguCode . '.'));
            })
            ->sortByDesc(fn (PaguAnggaran $pagu) => strlen(trim((string) $pagu->rekeningBelanja?->kode_rekening_belanja, '.')))
            ->first();
    }

    public function assertRkbuWithinPagu(Rkbu $rkbu, ?float $newTotal = null): void
    {
        if ($this->phaseOrder($rkbu->tahun_anggaran_id) < 2) {
            return;
        }

        $accountId = $this->resolveAccountId($rkbu);
        if (! $accountId) {
            throw ValidationException::withMessages([
                'rekening' => 'Rekening Belanja RKBU belum dapat ditentukan. Lengkapi hubungan rekening sebelum persetujuan fase perubahan.',
            ]);
        }

        $pagu = $this->paguForAccount($rkbu->tahun_anggaran_id, $accountId);
        if (! $pagu) {
            throw ValidationException::withMessages([
                'pagu' => 'Pagu untuk Rekening Belanja ini belum ditetapkan pada tahun anggaran aktif.',
            ]);
        }

        $total = $this->totalRkbuForAccount($rkbu->tahun_anggaran_id, $accountId, $rkbu->id)
            + ($newTotal ?? $this->detailTotal($rkbu));

        if ($total > (float) $pagu->jumlah_pagu) {
            throw ValidationException::withMessages([
                'pagu' => 'Persetujuan ditolak: total RKBU rekening ini menjadi Rp ' . number_format($total, 0, ',', '.')
                    . ', melebihi pagu Rp ' . number_format((float) $pagu->jumlah_pagu, 0, ',', '.') . '.',
            ]);
        }
    }

    public function assertUsulanWithinPagu(UsulanBelanja $usulan): void
    {
        $currentItems = DB::table('usulan_belanja_items')
            ->where('usulan_belanja_id', $usulan->id)
            ->whereNotNull('rkbu_id')
            ->groupBy('rkbu_id')
            ->select('rkbu_id')
            ->selectRaw('SUM(harga_satuan * volume * (1 + COALESCE(ppn, 0) / 100)) as total')
            ->get();
        $currentByAccount = [];

        foreach ($currentItems as $item) {
            $sourceRkbu = Rkbu::query()
                ->with(['subKategoriRkbu.sub_kategori_rekening', 'subKegiatan'])
                ->find($item->rkbu_id);
            $accountId = $sourceRkbu ? $this->resolveAccountId($sourceRkbu) : null;
            if (! $sourceRkbu || ! $accountId) {
                throw ValidationException::withMessages([
                    'pagu' => 'Rekening Belanja untuk salah satu item usulan belum dapat ditentukan.',
                ]);
            }

            $currentByAccount[$accountId] = ($currentByAccount[$accountId] ?? 0) + (float) $item->total;
        }

        foreach ($currentByAccount as $accountId => $currentTotal) {
            $pagu = $this->paguForAccount($usulan->tahun_anggaran_id, $accountId);
            if (! $pagu) {
                throw ValidationException::withMessages([
                    'pagu' => 'Pagu untuk salah satu Rekening Belanja usulan belum ditetapkan.',
                ]);
            }

            $total = $this->totalUsulanForAccount($usulan->tahun_anggaran_id, $accountId, $usulan->id) + $currentTotal;
            if ($total > (float) $pagu->jumlah_pagu) {
                throw ValidationException::withMessages([
                    'pagu' => 'Usulan Belanja ditolak: total usulan rekening ini menjadi Rp ' . number_format($total, 0, ',', '.')
                        . ', melebihi pagu Rp ' . number_format((float) $pagu->jumlah_pagu, 0, ',', '.') . '.',
                ]);
            }
        }
    }

    public function recordChange(array $attributes): PaguAnggaranHistory
    {
        return PaguAnggaranHistory::create($attributes);
    }
}
