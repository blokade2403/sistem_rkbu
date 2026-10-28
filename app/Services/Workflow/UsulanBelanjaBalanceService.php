<?php

namespace App\Services\Workflow;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UsulanBelanjaBalanceService
{
    private const DETAIL_TYPES = [
        'barjas' => 'rkbu_detail_barjass',
        'persediaan' => 'rkbu_detail_persediaans',
        'modal' => 'rkbu_detail_modals',
        'modal_alkes' => 'rkbu_detail_modal_alkes',
        'pegawai' => 'rkbu_detail_pegawais',
    ];

    public function applyToDetail($detail, ?string $excludeUsulanId = null): void
    {
        $type = array_search($detail->getTable(), self::DETAIL_TYPES, true);
        if (! $type) {
            return;
        }

        $reserved = $this->reservedBalances($type, [$detail->getKey()], $excludeUsulanId)
            ->get($detail->getKey());

        $detail->setAttribute('sisa_vol_rkbu', max(
            0,
            (float) ($detail->getAttribute('sisa_vol_rkbu') ?? $detail->getAttribute('jumlah_volume') ?? 0)
                - (float) ($reserved->volume ?? 0)
        ));
        $detail->setAttribute('sisa_anggaran_rkbu', max(
            0,
            (float) ($detail->getAttribute('sisa_anggaran_rkbu') ?? $detail->getAttribute('total_anggaran') ?? 0)
                - (float) ($reserved->anggaran ?? 0)
        ));
    }

    public function applyToCollection(Collection $details, ?string $excludeUsulanId = null): Collection
    {
        foreach (self::DETAIL_TYPES as $type => $table) {
            $typedDetails = $details->filter(fn ($detail) => $detail->getTable() === $table);
            if ($typedDetails->isEmpty()) {
                continue;
            }

            $reserved = $this->reservedBalances($type, $typedDetails->modelKeys(), $excludeUsulanId);
            foreach ($typedDetails as $detail) {
                $balance = $reserved->get($detail->getKey());
                $detail->setAttribute('sisa_vol_rkbu', max(
                    0,
                    (float) ($detail->getAttribute('sisa_vol_rkbu') ?? $detail->getAttribute('jumlah_volume') ?? 0)
                        - (float) ($balance->volume ?? 0)
                ));
                $detail->setAttribute('sisa_anggaran_rkbu', max(
                    0,
                    (float) ($detail->getAttribute('sisa_anggaran_rkbu') ?? $detail->getAttribute('total_anggaran') ?? 0)
                        - (float) ($balance->anggaran ?? 0)
                ));
            }
        }

        return $details;
    }

    public function applyToItemCollection(
        Collection $items,
        ?string $excludeUsulanId = null,
        ?string $includeUsulanId = null
    ): Collection
    {
        foreach (self::DETAIL_TYPES as $type => $table) {
            $typedItems = $items->where('detail_type', $type);
            if ($typedItems->isEmpty()) {
                continue;
            }

            $reserved = $this->reservedBalances($type, $typedItems->pluck('detail_id')->all(), $excludeUsulanId);
            $currentUsulan = $includeUsulanId
                ? $this->usulanItemBalances($includeUsulanId, $type, $typedItems->pluck('detail_id')->all())
                : collect();
            foreach ($typedItems as $item) {
                $balance = $reserved->get($item->detail_id);
                $current = $currentUsulan->get($item->detail_id);
                $item->sisa_vol_rkbu = max(
                    0,
                    (float) $item->sisa_vol_rkbu
                        - (float) ($balance->volume ?? 0)
                        - (float) ($current->volume ?? 0)
                );
                $item->sisa_anggaran_rkbu = max(
                    0,
                    (float) $item->sisa_anggaran_rkbu
                        - (float) ($balance->anggaran ?? 0)
                        - (float) ($current->anggaran ?? 0)
                );
            }
        }

        return $items;
    }

    public function remainingForDetail(
        string $type,
        string $detailId,
        float $baseVolume,
        float $baseBudget,
        ?string $excludeUsulanId = null,
        ?string $includeUsulanId = null
    ): array
    {
        $balance = $this->reservedBalances($type, [$detailId], $excludeUsulanId)->get($detailId);
        $current = $includeUsulanId
            ? $this->usulanItemBalances($includeUsulanId, $type, [$detailId])->get($detailId)
            : null;

        return [
            'volume' => max(0, $baseVolume - (float) ($balance->volume ?? 0) - (float) ($current->volume ?? 0)),
            'anggaran' => max(0, $baseBudget - (float) ($balance->anggaran ?? 0) - (float) ($current->anggaran ?? 0)),
        ];
    }

    public function assertDetailCoversReservations($detail, float $proposedVolume, float $proposedBudget): void
    {
        $type = array_search($detail->getTable(), self::DETAIL_TYPES, true);
        if (! $type) {
            return;
        }

        $reserved = DB::table('usulan_belanja_items as items')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'items.usulan_belanja_id')
            ->where('items.detail_type', $type)
            ->where('items.detail_id', $detail->getKey())
            ->whereIn('usulan.status_usulan', ['proses', 'approved'])
            ->selectRaw('COALESCE(SUM(items.volume), 0) as volume')
            ->selectRaw('COALESCE(SUM(ROUND(items.harga_satuan * items.volume, 2) + ROUND(ROUND(items.harga_satuan * items.volume, 2) * COALESCE(items.ppn, 0) / 100, 2)), 0) as anggaran')
            ->first();

        $reservedVolume = (float) ($reserved->volume ?? 0);
        $reservedBudget = (float) ($reserved->anggaran ?? 0);
        $remainingVolume = $proposedVolume - $reservedVolume;
        $remainingBudget = $proposedBudget - $reservedBudget;

        if ($remainingVolume < -0.00001 || $remainingBudget < -0.01) {
            throw ValidationException::withMessages([
                'details' => sprintf(
                    'Perubahan item "%s" ditolak karena anggaran atau volume tersisa menjadi minus. Sudah terpakai %s dari %s dan Rp %s dari Rp %s. Sesuaikan volume/harga atau lakukan pergeseran penambahan anggaran terlebih dahulu.',
                    $detail->nama_barang ?? $detail->nama_pegawai ?? 'RKBU',
                    number_format($reservedVolume, 2, ',', '.'),
                    number_format($proposedVolume, 2, ',', '.'),
                    number_format($reservedBudget, 2, ',', '.'),
                    number_format($proposedBudget, 2, ',', '.')
                ),
            ]);
        }
    }

    private function usulanItemBalances(string $usulanId, string $type, array $detailIds): Collection
    {
        if ($detailIds === []) {
            return collect();
        }

        return DB::table('usulan_belanja_items')
            ->where('usulan_belanja_id', $usulanId)
            ->where('detail_type', $type)
            ->whereIn('detail_id', $detailIds)
            ->groupBy('detail_id')
            ->select('detail_id')
            ->selectRaw('SUM(volume) as volume')
            ->selectRaw('SUM(ROUND(harga_satuan * volume, 2) + ROUND(ROUND(harga_satuan * volume, 2) * COALESCE(ppn, 0) / 100, 2)) as anggaran')
            ->get()
            ->keyBy('detail_id');
    }

    public function assertUsulanFits(\App\Models\UsulanBelanja\UsulanBelanja $usulan): void
    {
        $requestedItems = DB::table('usulan_belanja_items')
            ->where('usulan_belanja_id', $usulan->id)
            ->whereNotNull('detail_type')
            ->whereNotNull('detail_id')
            ->groupBy('detail_type', 'detail_id')
            ->select('detail_type', 'detail_id')
            ->selectRaw('SUM(volume) as volume')
            ->selectRaw('SUM(ROUND(harga_satuan * volume, 2) + ROUND(ROUND(harga_satuan * volume, 2) * COALESCE(ppn, 0) / 100, 2)) as anggaran')
            ->get();

        foreach ($requestedItems as $requested) {
            $table = self::DETAIL_TYPES[$requested->detail_type] ?? null;
            if (! $table) {
                throw ValidationException::withMessages([
                    'items' => 'Jenis item RKBU dalam usulan tidak valid.',
                ]);
            }

            $detail = DB::table($table)
                ->where('id', $requested->detail_id)
                ->lockForUpdate()
                ->first(['jumlah_volume', 'total_anggaran', 'sisa_vol_rkbu', 'sisa_anggaran_rkbu']);

            if (! $detail) {
                throw ValidationException::withMessages([
                    'items' => 'Detail RKBU dalam usulan tidak ditemukan.',
                ]);
            }

            $remaining = $this->remainingForDetail(
                $requested->detail_type,
                $requested->detail_id,
                (float) ($detail->sisa_vol_rkbu ?? $detail->jumlah_volume),
                (float) ($detail->sisa_anggaran_rkbu ?? $detail->total_anggaran),
                $usulan->id
            );

            $remainingBudgetAfterUsulan = round(
                $remaining['anggaran'] - (float) $requested->anggaran,
                1
            );

            if ($remainingBudgetAfterUsulan < 0) {
                throw ValidationException::withMessages([
                    'items' => 'Lakukan pergeseran Penambahan Anggaran agar Usulan Belanja bisa dilanjutkan.',
                ]);
            }
            if ((float) $requested->volume > $remaining['volume']) {
                throw ValidationException::withMessages([
                    'items' => 'Volume usulan melebihi sisa volume RKBU. Kurangi volume sebelum mengajukan validasi.',
                ]);
            }
        }
    }

    private function reservedBalances(string $type, array $detailIds, ?string $excludeUsulanId = null): Collection
    {
        if ($detailIds === []) {
            return collect();
        }

        return DB::table('usulan_belanja_items as items')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'items.usulan_belanja_id')
            ->where('items.detail_type', $type)
            ->whereIn('items.detail_id', $detailIds)
            ->whereIn('usulan.status_usulan', ['proses', 'approved'])
            ->when($excludeUsulanId, fn ($query) => $query->where('usulan.id', '<>', $excludeUsulanId))
            ->groupBy('items.detail_id')
            ->selectRaw('items.detail_id, SUM(items.volume) as volume')
            ->selectRaw('SUM(ROUND(items.harga_satuan * items.volume, 2) + ROUND(ROUND(items.harga_satuan * items.volume, 2) * COALESCE(items.ppn, 0) / 100, 2)) as anggaran')
            ->get()
            ->keyBy('detail_id');
    }
}
