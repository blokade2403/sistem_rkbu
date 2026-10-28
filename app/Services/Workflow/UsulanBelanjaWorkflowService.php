<?php

namespace App\Services\Workflow;

use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\UsulanBelanja\UsulanBelanja;
use App\Models\UsulanBelanja\UsulanBelanjaItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class UsulanBelanjaWorkflowService
{
    public function getEligibleRkbus(User $user): Collection
    {
        $assignment = $this->assertFirstUsulanValidator($user);

        return $this->availableRkbusQuery(null, $this->subordinateUserIds($assignment))
            ->orderBy('sub_kategori_rkbus.nama_sub_kategori_rkbu')
            ->get();
    }

    public function getAvailableItems(UsulanBelanja $usulan): Collection
    {
        return $this->getDetailItems($usulan->sub_kategori_rkbu_id, null, $usulan->id, $usulan->id, $usulan->sumber_dana_id);
    }

    public function getSelectedItems(UsulanBelanja $usulan): Collection
    {
        $selectedItems = DB::table('usulan_belanja_items')
            ->where('usulan_belanja_id', $usulan->id)
            ->whereNotNull('detail_type')
            ->get([
                'detail_type',
                'detail_id',
                'id',
                'harga_satuan',
                'volume',
                'ppn',
                'spesifikasi as spesifikasi_usulan',
            ]);
        $selected = $selectedItems
            ->map(fn($item) => $item->detail_type . ':' . $item->detail_id)
            ->all();

        $savedValues = $selectedItems->keyBy(
            fn($item) => $item->detail_type . ':' . $item->detail_id
        );

        return $this->getDetailItems($usulan->sub_kategori_rkbu_id, $selected, $usulan->id, null, $usulan->sumber_dana_id)
            ->map(function ($item) use ($savedValues) {
                $saved = $savedValues->get($item->detail_type . ':' . $item->detail_id);
                $item->link_id = $saved?->id;
                $item->harga_perencanaan = (float) $item->harga_satuan;
                $item->harga_satuan = $saved?->harga_satuan ?? $item->harga_satuan;
                $item->volume = $saved?->volume ?? $item->volume;
                $item->ppn = $saved?->ppn ?? $item->ppn;
                if ($saved && $saved->spesifikasi_usulan !== null) {
                    $item->spesifikasi = $saved->spesifikasi_usulan;
                }
                $item->total_anggaran = round(
                    (float) $item->harga_satuan * (float) $item->volume,
                    2
                );
                $item->total_ppn = round(
                    $item->total_anggaran * (float) $item->ppn / 100,
                    2
                );
                $item->anggaran_tersedia = (float) $item->sisa_anggaran_rkbu;
                $item->volume_tersedia = (float) $item->sisa_vol_rkbu;
                $item->sisa_anggaran_rkbu = round(
                    $item->anggaran_tersedia - $item->total_anggaran,
                    2
                );
                $item->sisa_vol_rkbu = round(
                    $item->volume_tersedia - (float) $item->volume,
                    3
                );

                return $item;
            });
    }

    private function getDetailItems(
        string $subKategoriId,
        ?array $selected = null,
        ?string $excludeUsulanId = null,
        ?string $includeUsulanId = null,
        ?string $sumberDanaId = null
    ): Collection
    {
        $detailTables = [
            'barjas'            => 'rkbu_detail_barjass',
            'persediaan'        => 'rkbu_detail_persediaans',
            'modal'             => 'rkbu_detail_modals',
            'modal_alkes'       => 'rkbu_detail_modal_alkes',
            'pegawai'           => 'rkbu_detail_pegawais',
        ];
        $items = collect();

        foreach ($detailTables as $type => $table) {
            $specification = $type === 'pegawai'
                ? "CONCAT_WS(' - ', item.jabatan, item.pendidikan)"
                : 'item.spesifikasi';
            $name = $type === 'pegawai'
                ? "COALESCE(item.nama_pegawai, item.jabatan, 'Pegawai')"
                : 'item.nama_barang';

            $query = DB::table($table . ' as item')
                ->join('rkbus', 'rkbus.id', '=', 'item.rkbu_id')
                ->where('rkbus.status', 'approved')
                ->where('rkbus.tahun_anggaran_id', session('tahun_anggaran_id'))
                ->where('rkbus.sub_kategori_rkbu_id', $subKategoriId)
                ->when($sumberDanaId, fn ($query) => $query->where('rkbus.sumber_dana_id', $sumberDanaId));

            if ($selected !== null) {
                $detailIds = collect($selected)
                    ->filter(fn($selection) => str_starts_with($selection, $type . ':'))
                    ->map(fn($selection) => substr($selection, strlen($type) + 1))
                    ->all();
                $query->whereIn('item.id', $detailIds);
            }

            $items = $items->merge(
                $query->select([
                    DB::raw("'{$type}' as detail_type"),
                    'item.id as detail_id',
                    'rkbus.id as rkbu_id',
                    'rkbus.nomor_rkbu',
                    DB::raw("{$name} as nama_barang"),
                    DB::raw("{$specification} as spesifikasi"),
                    DB::raw(in_array($type, ['barjas', 'modal', 'modal_alkes'], true)
                        ? 'COALESCE(item.volume_1, 0) as volume_1'
                        : '0 as volume_1'),
                    DB::raw($type === 'pegawai'
                        ? "'Bulan' as satuan_1"
                        : "COALESCE(item.satuan_1, '-') as satuan_1"),
                    DB::raw(in_array($type, ['barjas', 'modal', 'modal_alkes'], true)
                        ? 'COALESCE(item.volume_2, 0) as volume_2'
                        : '0 as volume_2'),
                    DB::raw(in_array($type, ['barjas', 'modal', 'modal_alkes'], true)
                        ? "COALESCE(item.satuan_2, '-') as satuan_2"
                        : "'-' as satuan_2"),
                    'item.total_anggaran',
                    DB::raw($type === 'pegawai'
                        ? 'CASE WHEN item.jumlah_volume > 0 THEN item.total_anggaran / item.jumlah_volume ELSE item.total_anggaran END as harga_satuan'
                        : 'COALESCE(item.harga_satuan, 0) as harga_satuan'),
                    DB::raw('COALESCE(item.jumlah_volume, 0) as volume'),
                    DB::raw($type === 'pegawai'
                        ? '0 as ppn'
                        : 'COALESCE(item.ppn, 0) as ppn'),
                    DB::raw('COALESCE(item.sisa_vol_rkbu, item.jumlah_volume, 0) as sisa_vol_rkbu'),
                    DB::raw('COALESCE(item.sisa_anggaran_rkbu, item.total_anggaran, 0) as sisa_anggaran_rkbu'),
                ])
                    ->orderBy('rkbus.nomor_rkbu')
                    ->get()
            );
        }

        $items = $items->sortBy('nomor_rkbu')->values();

        return app(UsulanBelanjaBalanceService::class)
            ->applyToItemCollection($items, $excludeUsulanId, $includeUsulanId);
    }

    private function availableRkbusQuery(?string $subKategoriId = null, ?array $creatorIds = null)
    {
        return DB::table('rkbus')
            ->leftJoin(
                'sub_kategori_rkbus',
                'rkbus.sub_kategori_rkbu_id',
                '=',
                'sub_kategori_rkbus.id_sub_kategori_rkbu'
            )
            ->leftJoin('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->leftJoin('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('sumber_danas', 'rkbus.sumber_dana_id', '=', 'sumber_danas.id_sumber_dana')
            ->where('rkbus.status', 'approved')
            ->when($creatorIds !== null, fn($query) => $query->whereIn('rkbus.created_by', $creatorIds))
            ->where('rkbus.tahun_anggaran_id', session('tahun_anggaran_id'))
            ->when($subKategoriId, fn($query) => $query->where(
                'rkbus.sub_kategori_rkbu_id',
                $subKategoriId
            ))
            ->select([
                'rkbus.sub_kategori_rkbu_id as id',
                'rkbus.tahun_anggaran_id',
                'rkbus.sumber_dana_id',
                'sumber_danas.nama_sumber_dana',
                'sub_kategori_rkbus.nama_sub_kategori_rkbu',
                'jenis_kategori_rkbus.id_jenis_kategori_rkbu',
                'jenis_kategori_rkbus.nama_jenis_kategori_rkbu',
            ])
            ->distinct();
    }

    public function buatUsulan(string $sumberDanaId, string $jenisKategoriId, string $subKategoriId, User $user): UsulanBelanja
    {
        return DB::transaction(function () use ($sumberDanaId, $jenisKategoriId, $subKategoriId, $user) {
            $assignment = $this->assertFirstUsulanValidator($user);
            $creatorIds = $this->subordinateUserIds($assignment);

            $rkbu = DB::table('rkbus')
                ->where('sub_kategori_rkbu_id', $subKategoriId)
                ->where('sumber_dana_id', $sumberDanaId)
                ->where('jenis_kategori_rkbu_id', $jenisKategoriId)
                ->where('tahun_anggaran_id', session('tahun_anggaran_id'))
                ->where('status', 'approved')
                ->whereIn('created_by', $creatorIds)
                ->orderBy('nomor_rkbu')
                ->lockForUpdate()
                ->first();

            if (! $rkbu) {
                throw ValidationException::withMessages([
                    'sub_kategori_rkbu_id' => 'Tidak ada RKBU approved pada subkategori ini.',
                ]);
            }

            // Kunci baris tahun anggaran untuk nomor urut.
            $tahun = DB::table('tahun_anggarans')
                ->where('id', $rkbu->tahun_anggaran_id)
                ->lockForUpdate()
                ->first();

            if (! $tahun) {
                throw ValidationException::withMessages([
                    'sub_kategori_rkbu_id' => 'Tahun anggaran RKBU tidak ditemukan.',
                ]);
            }

            $max = UsulanBelanja::where(
                'tahun_anggaran_id',
                $tahun->id
            )->max('nomor_urut') ?? 0;

            $urut = $max + 1;

            $tahunLabel = preg_replace(
                '/[^A-Za-z0-9\/-]/',
                '',
                $tahun->nama_tahun_anggaran
            );

            $nomor = sprintf('UB/%s/%04d', $tahunLabel, $urut);

            $usulan = UsulanBelanja::create([
                'id' => (string) Str::uuid(),
                'rkbu_id' => $rkbu->id,
                'no_usulan_belanja' => $nomor,
                'nomor_urut' => $urut,
                'user_id' => $user->id,
                'nama_pengusul' => $user->nama,
                'unit_id' => $rkbu->unit_id,
                'sub_kategori_rkbu_id' => $rkbu->sub_kategori_rkbu_id,
                'jenis_kategori_rkbu_id' => $rkbu->jenis_kategori_rkbu_id,
                'sumber_dana_id' => $rkbu->sumber_dana_id,
                'tahun_anggaran_id' => $rkbu->tahun_anggaran_id,
                'status_usulan' => 'draft',
            ]);

            return $usulan;
        }, 3);
    }

    /**
     * Pengusul RKBU yang berada pada jabatan di bawah jabatan validator.
     * Subkategori usulan dibatasi ke RKBU approved milik pengusul tersebut.
     */
    private function subordinateUserIds(PositionAssignment $assignment): array
    {
        $positions = DB::table('positions')->get(['id', 'parent_id']);
        $descendantIds = [$assignment->position_id];

        do {
            $count = count($descendantIds);
            $descendantIds = array_values(array_unique(array_merge(
                $descendantIds,
                $positions->whereIn('parent_id', $descendantIds)->pluck('id')->all()
            )));
        } while (count($descendantIds) > $count);

        // Jabatan validator sendiri tidak termasuk bawahannya.
        $descendantIds = array_values(array_diff($descendantIds, [$assignment->position_id]));

        return DB::table('position_assignments')
            ->whereIn('position_id', $descendantIds)
            ->pluck('user_id')
            ->unique()
            ->values()
            ->all();
    }

    public function addItems(
        UsulanBelanja $usulan,
        array $selections,
        User $user
    ): int {
        return DB::transaction(function () use ($usulan, $selections, $user) {
            $usulan = UsulanBelanja::query()
                ->whereKey($usulan->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($usulan->user_id === $user->id, 403);
            abort_unless((string) $usulan->tahun_anggaran_id === (string) session('tahun_anggaran_id'), 403);

            if ($usulan->status_usulan !== 'draft' || $usulan->workflow_instance_id) {
                throw ValidationException::withMessages([
                    'rkbu_id' => 'Item hanya dapat ditambahkan selama Usulan Belanja berstatus draft.',
                ]);
            }

            $detailTables = [
                'barjas' => 'rkbu_detail_barjass',
                'persediaan' => 'rkbu_detail_persediaans',
                'modal' => 'rkbu_detail_modals',
                'modal_alkes' => 'rkbu_detail_modal_alkes',
                'pegawai' => 'rkbu_detail_pegawais',
            ];
            $added = 0;

            foreach (array_unique($selections) as $selection) {
                [$type, $detailId] = array_pad(explode(':', $selection, 2), 2, null);
                $table = $detailTables[$type] ?? null;

                if (! $table || ! Str::isUuid($detailId ?? '')) {
                    throw ValidationException::withMessages([
                        'items' => 'Pilihan item RKBU tidak valid.',
                    ]);
                }

                $priceFields = $type === 'pegawai'
                    ? [
                        DB::raw('CASE WHEN item.jumlah_volume > 0 THEN item.total_anggaran / item.jumlah_volume ELSE item.total_anggaran END as harga_satuan'),
                        DB::raw('0 as ppn'),
                    ]
                    : ['item.harga_satuan', 'item.ppn'];
                $specificationField = $type === 'pegawai'
                    ? DB::raw("CONCAT_WS(' - ', item.jabatan, item.pendidikan) as spesifikasi")
                    : 'item.spesifikasi as spesifikasi';

                $detail = DB::table($table . ' as item')
                    ->join('rkbus', 'rkbus.id', '=', 'item.rkbu_id')
                    ->where('item.id', $detailId)
                    ->where('rkbus.status', 'approved')
                    ->where('rkbus.tahun_anggaran_id', session('tahun_anggaran_id'))
                    ->where('rkbus.sub_kategori_rkbu_id', $usulan->sub_kategori_rkbu_id)
                    ->when($usulan->sumber_dana_id, fn ($query) => $query->where('rkbus.sumber_dana_id', $usulan->sumber_dana_id))
                    ->lockForUpdate()
                    ->first(array_merge([
                        'item.id',
                        'item.rkbu_id',
                        'item.jumlah_volume as volume',
                        'item.total_anggaran',
                        'item.sisa_vol_rkbu',
                        'item.sisa_anggaran_rkbu',
                        $specificationField,
                    ], $priceFields));

                if (! $detail) {
                    throw ValidationException::withMessages([
                        'items' => 'Item harus berasal dari RKBU approved dengan subkategori yang sama.',
                    ]);
                }

                $remaining = app(UsulanBelanjaBalanceService::class)->remainingForDetail(
                    $type,
                    $detailId,
                    (float) ($detail->sisa_vol_rkbu ?? $detail->volume),
                    (float) ($detail->sisa_anggaran_rkbu ?? $detail->total_anggaran),
                    null,
                    $usulan->id
                );
                $availableVolume = $remaining['volume'];
                $initialVolume = min((float) $detail->volume, $availableVolume);

                if ($initialVolume <= 0) {
                    throw ValidationException::withMessages([
                        'items' => 'Sisa volume RKBU tidak cukup untuk item yang dipilih.',
                    ]);
                }

                UsulanBelanjaItem::create([
                    'usulan_belanja_id' => $usulan->id,
                    'rkbu_id' => $detail->rkbu_id,
                    'detail_type' => $type,
                    'detail_id' => $detailId,
                    'spesifikasi' => $detail->spesifikasi,
                    'harga_satuan' => $detail->harga_satuan,
                    'volume' => $initialVolume,
                    'ppn' => $detail->ppn,
                ]);
                $added++;
            }

            return $added;
        }, 3);
    }

    public function updateItems(
        UsulanBelanja $usulan,
        array $items,
        User $user
    ): void {
        DB::transaction(function () use ($usulan, $items, $user) {
            $usulan = UsulanBelanja::query()
                ->whereKey($usulan->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($usulan->user_id === $user->id, 403);

            $canEditDraft = $usulan->status_usulan === 'draft' && ! $usulan->workflow_instance_id;
            $canEditRevision = $usulan->status_usulan === 'revision' && (bool) $usulan->workflow_instance_id;
            if (! $canEditDraft && ! $canEditRevision) {
                throw ValidationException::withMessages([
                    'items' => 'Item hanya dapat diubah selama Usulan Belanja berstatus draft atau revisi.',
                ]);
            }

            foreach ($items as $itemId => $values) {
                $item = UsulanBelanjaItem::query()
                    ->whereKey($itemId)
                    ->where('usulan_belanja_id', $usulan->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $detailTables = [
                    'barjas' => 'rkbu_detail_barjass',
                    'persediaan' => 'rkbu_detail_persediaans',
                    'modal' => 'rkbu_detail_modals',
                    'modal_alkes' => 'rkbu_detail_modal_alkes',
                    'pegawai' => 'rkbu_detail_pegawais',
                ];
                $table = $detailTables[$item->detail_type] ?? null;
                if (! $table) {
                    throw ValidationException::withMessages([
                        'items' => 'Jenis item RKBU tidak valid.',
                    ]);
                }

                $detail = DB::table($table)
                    ->where('id', $item->detail_id)
                    ->lockForUpdate()
                    ->first([
                        'jumlah_volume',
                        'total_anggaran',
                        'sisa_vol_rkbu',
                        'sisa_anggaran_rkbu',
                    ]);

                if (! $detail) {
                    throw ValidationException::withMessages([
                        'items' => 'Detail RKBU untuk item ini tidak ditemukan.',
                    ]);
                }

                $requestedSubtotal = round(
                    (float) $values['harga_satuan'] * (float) $values['volume'],
                    2
                );
                $requestedAmount = round(
                    $requestedSubtotal + round($requestedSubtotal * (float) $values['ppn'] / 100, 2),
                    2
                );
                $remaining = app(UsulanBelanjaBalanceService::class)->remainingForDetail(
                    $item->detail_type,
                    $item->detail_id,
                    (float) ($detail->sisa_vol_rkbu ?? $detail->jumlah_volume),
                    (float) ($detail->sisa_anggaran_rkbu ?? $detail->total_anggaran),
                    $usulan->id
                );
                $availableVolume = $remaining['volume'];
                $availableBudget = $remaining['anggaran'];

                $remainingBudgetAfterUsulan = round($availableBudget - $requestedAmount, 1);

                if ($remainingBudgetAfterUsulan < 0) {
                    throw ValidationException::withMessages([
                        "items.{$itemId}.harga_satuan" => 'Lakukan pergeseran Penambahan Anggaran agar Usulan Belanja bisa dilanjutkan.',
                    ]);
                }
                if ((float) $values['volume'] > $availableVolume) {
                    throw ValidationException::withMessages([
                        "items.{$itemId}.volume" => 'Volume usulan melebihi sisa volume RKBU.',
                    ]);
                }

                $item->update([
                    'harga_satuan' => $values['harga_satuan'],
                    'volume' => $values['volume'],
                    'ppn' => $values['ppn'],
                    'spesifikasi' => $values['spesifikasi'] ?? '',
                ]);
            }
        }, 3);
    }

    public function deleteEmptyUsulan(UsulanBelanja $usulan): void
    {
        DB::transaction(function () use ($usulan) {
            $usulan = UsulanBelanja::query()
                ->whereKey($usulan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($usulan->status_usulan !== 'draft' || $usulan->workflow_instance_id) {
                throw ValidationException::withMessages([
                    'usulan' => 'Hanya Usulan Belanja draft yang belum diajukan yang dapat dihapus.',
                ]);
            }

            $hasSelectedItems = UsulanBelanjaItem::query()
                ->where('usulan_belanja_id', $usulan->id)
                ->whereNotNull('detail_type')
                ->whereNotNull('detail_id')
                ->exists();

            if ($hasSelectedItems) {
                throw ValidationException::withMessages([
                    'usulan' => 'Usulan Belanja yang memiliki item terpilih tidak dapat dihapus.',
                ]);
            }

            $usulan->delete();
        }, 3);
    }

    public function deleteItem(
        UsulanBelanja $usulan,
        UsulanBelanjaItem $item,
        User $user
    ): void {
        DB::transaction(function () use ($usulan, $item, $user) {
            $usulan = UsulanBelanja::query()
                ->whereKey($usulan->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($usulan->user_id === $user->id, 403);

            if ($usulan->status_usulan !== 'draft' || $usulan->workflow_instance_id) {
                throw ValidationException::withMessages([
                    'items' => 'Item hanya dapat dihapus selama Usulan Belanja berstatus draft.',
                ]);
            }

            $item->newQuery()
                ->whereKey($item->id)
                ->where('usulan_belanja_id', $usulan->id)
                ->firstOrFail()
                ->delete();
        }, 3);
    }

    private function assertFirstUsulanValidator(User $user): PositionAssignment
    {
        $assignment = app(PositionAssignmentService::class)
            ->getUserAssignment($user);

        $firstStep = DB::table('workflow_steps')
            ->join('workflows', 'workflow_steps.workflow_id', '=', 'workflows.id')
            ->whereRaw('LOWER(workflows.modul) = ?', ['usulan_belanja'])
            ->where('workflow_steps.is_pptk_step', false)
            ->orderBy('workflow_steps.step_order')
            ->select([
                'workflow_steps.validator_type',
                'workflow_steps.position_id',
                'workflow_steps.role_id',
                'workflow_steps.validator_role_id',
            ])
            ->first();

        $isUsulanCreator = $assignment
            && $user->roles()->where('nama_role', 'Validator')->exists();

        $isFirstStepValidator = $isUsulanCreator || ($assignment && $firstStep && (
            ($firstStep->position_id
                && $firstStep->position_id === $assignment->position_id)
            || ($firstStep->validator_type === 'role'
                && $firstStep->role_id
                && $user->roles()->whereKey($firstStep->role_id)->exists())
            || ($firstStep->validator_role_id
                && $user->validatorRoles()->whereKey($firstStep->validator_role_id)->exists())
        ));

        abort_unless(
            $isFirstStepValidator,
            403,
            'Hanya validator aktif pada urutan pertama workflow Usulan Belanja yang dapat membuat usulan.'
        );

        return $assignment;
    }

    /**
     * Memulai workflow yang sudah dikonfigurasi.
     *
     * Konfigurasi workflow harus memiliki modul Usulan Belanja.
     */
    public function ajukan(UsulanBelanja $usulan, $user): UsulanBelanja
    {
        return DB::transaction(function () use ($usulan, $user) {
            $usulan = UsulanBelanja::whereKey($usulan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($usulan->user_id !== $user->id) {
                abort(403);
            }

            if (! in_array(
                $usulan->status_usulan,
                ['draft', 'revision'],
                true
            )) {
                throw ValidationException::withMessages([
                    'status' => 'Usulan tidak dapat diajukan.',
                ]);
            }

            $existingInstance = null;
            if ($usulan->workflow_instance_id) {
                $existingInstance = DB::table('workflow_instances')
                    ->where('id', $usulan->workflow_instance_id)
                    ->lockForUpdate()
                    ->first();

                if (
                    $usulan->status_usulan !== 'revision'
                    || ! $existingInstance
                    || $existingInstance->status !== 'revision'
                    || $existingInstance->dokumen_id !== $usulan->id
                    || $existingInstance->dokumen_type !== UsulanBelanja::class
                ) {
                    throw ValidationException::withMessages([
                        'status' => 'Usulan sudah memiliki instance workflow yang tidak dapat diajukan kembali.',
                    ]);
                }
            }

            if (! DB::table('usulan_belanja_items')
                ->where('usulan_belanja_id', $usulan->id)
                ->whereNotNull('detail_type')
                ->exists()) {
                throw ValidationException::withMessages([
                    'status' => 'Pilih minimal satu item RKBU sebelum mengajukan Usulan Belanja.',
                ]);
            }

            app(UsulanBelanjaBalanceService::class)->assertUsulanFits($usulan);
            app(PaguAnggaranService::class)->assertUsulanWithinPagu($usulan);

            $workflowQuery = DB::table('workflows');
            $workflow = $existingInstance
                ? $workflowQuery->where('id', $existingInstance->workflow_id)->first()
                : $workflowQuery->whereRaw('LOWER(modul) = ?', ['usulan_belanja'])->first();

            if (! $workflow) {
                throw new RuntimeException(
                    'Workflow Usulan Belanja belum dikonfigurasi.'
                );
            }

            $steps = DB::table('workflow_steps')
                ->where('workflow_id', $workflow->id)
                ->where(function ($query) use ($usulan) {
                    $query->whereNotExists(function ($sub) {
                        $sub->selectRaw('1')
                            ->from('workflow_step_sub_kategori_rkbu')
                            ->whereColumn(
                                'workflow_step_sub_kategori_rkbu.workflow_step_id',
                                'workflow_steps.id'
                            );
                    })->orWhereIn(
                        'id',
                        DB::table('workflow_step_sub_kategori_rkbu')
                            ->select('workflow_step_id')
                            ->where(
                                'sub_kategori_rkbu_id',
                                $usulan->sub_kategori_rkbu_id
                            )
                    );
                })
                ->orderBy('step_order')
                ->get();

            if ($steps->isEmpty()) {
                throw new RuntimeException(
                    'Workflow belum memiliki tahap yang sesuai subkategori.'
                );
            }

            $instanceId = $existingInstance?->id ?? (string) Str::uuid();
            $first = $steps->first();

            if ($existingInstance) {
                // Preserve old approval rows as history and open a fresh approval cycle.
                DB::table('approvals')
                    ->where('workflow_instance_id', $instanceId)
                    ->where('is_current', true)
                    ->update(['is_current' => false, 'updated_at' => now()]);

                DB::table('workflow_instances')
                    ->where('id', $instanceId)
                    ->update([
                        'current_step_id' => $first->id,
                        'submitted_by' => $user->id,
                        'submitted_at' => now(),
                        'finished_at' => null,
                        'status' => 'proses',
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('workflow_instances')->insert([
                    'id' => $instanceId,
                    'workflow_id' => $workflow->id,
                    'current_step_id' => $first->id,
                    'dokumen_id' => $usulan->id,
                    'dokumen_type' => UsulanBelanja::class,
                    'submitted_by' => $user->id,
                    'submitted_at' => now(),
                    'status' => 'proses',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $usulan->update([
                'workflow_instance_id' => $instanceId,
                'status_usulan' => 'proses',
                'catatan' => null,
            ]);

            $approvalIds = $this->buatApproval($instanceId, $first, $usulan);

            foreach ($approvalIds as $approvalId) {
                DB::table('approval_logs')->insert([
                    'id' => (string) Str::uuid(),
                    'approval_id' => $approvalId,
                    'workflow_instance_id' => $instanceId,
                    'user_id' => $user->id,
                    'aksi' => 'submit',
                    'catatan' => $existingInstance
                        ? 'Usulan Belanja diajukan kembali setelah revisi.'
                        : 'Usulan Belanja diajukan.',
                    'aksi_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $usulan->fresh();
        }, 3);
    }

    /**
     * Membuat approval berdasarkan jenis validator.
     *
     * Implementasi resolver disesuaikan lagi dengan aturan pejabat
     * dan delegasi yang berlaku di organisasi.
     */
    private function buatApproval(
        string $instanceId,
        object $step,
        UsulanBelanja $usulan
    ): array {
        $approvers = $this->resolveApprovers($step, $usulan);

        if ($approvers->isEmpty() && $step->is_required) {
            throw new RuntimeException(
                'Validator tidak ditemukan untuk tahap workflow: '
                    . $step->step_order
            );
        }

        $approvalIds = [];
        foreach ($approvers as $approver) {
            $approvalId = (string) Str::uuid();
            DB::table('approvals')->insert([
                'id' => $approvalId,
                'workflow_instance_id' => $instanceId,
                'workflow_step_id' => $step->id,
                'validator_role_id' => $step->validator_role_id,
                'validator_user_id' => $approver->user_id,
                'position_id' => $approver->position_id,
                'position_assignment_id' => $approver->position_assignment_id,
                'status' => 'pending',
                'received_at' => now(),
                'sequence' => $step->step_order,
                'is_current' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $approvalIds[] = $approvalId;
        }

        return $approvalIds;
    }

    /**
     * Resolver dasar.
     * Lihat penjelasan setelah kode untuk batasan konfigurasi.
     */
    public function resolveApprovers(
        object $step,
        UsulanBelanja $usulan
    ) {
        $user = DB::table('users')
            ->where('id', $usulan->user_id)
            ->first();

        if (! $user) {
            return collect();
        }

        $query = DB::table('position_assignments as pa')
            ->join('users as u', 'u.id', '=', 'pa.user_id')
            ->where('pa.is_active', true)
            ->where(function ($q) {
                $q->whereNull('pa.tanggal_selesai')
                    ->orWhereDate('pa.tanggal_selesai', '>=', now());
            })
            ->whereDate('pa.tanggal_mulai', '<=', now())
            ->where('u.status_user', 'aktif')
            ->select([
                'u.id as user_id',
                'pa.position_id',
                'pa.id as position_assignment_id',
            ]);

        if ($step->is_pptk_step) {
            $pptkPositionIds = DB::table('position_sub_kategori_rkbus')
                ->where('id_sub_kategori_rkbu', $usulan->sub_kategori_rkbu_id)
                ->pluck('position_id');

            if ($pptkPositionIds->isEmpty()) {
                return collect();
            }

            return $query->whereIn('pa.position_id', $pptkPositionIds)->get();
        }

        // Tahap yang sudah menunjuk jabatan validator harus memakai jabatan itu
        // secara langsung, termasuk data workflow lama yang masih bertipe hierarchy.
        if ($step->position_id) {
            return $query->where('pa.position_id', $step->position_id)->get();
        }

        if (
            $step->validator_type === 'user'
            || ($step->validator_type === 'hierarchy' && $step->validator_role_id)
        ) {
            return $query
                ->join(
                    'user_validator_roles as uvr',
                    'uvr.user_id',
                    '=',
                    'u.id'
                )
                ->where('uvr.validator_role_id', $step->validator_role_id)
                ->get();
        }

        if ($step->validator_type === 'position') {
            return $query->where('pa.position_id', $step->position_id)
                ->get();
        }

        if ($step->validator_type === 'role' && $step->role_id) {
            return $query->join(
                'role_user as ru',
                'ru.user_id',
                '=',
                'u.id'
            )->where('ru.role_id', $step->role_id)->get();
        }

        if ($step->validator_type === 'hierarchy') {
            $level = (int) ($step->hierarchy_level ?? 1);
            $parentIds = [];
            $submitterPositionIds = DB::table('position_assignments')
                ->where('user_id', $usulan->user_id)
                ->where('is_active', true)
                ->whereDate('tanggal_mulai', '<=', now())
                ->where(function ($assignmentQuery) {
                    $assignmentQuery->whereNull('tanggal_selesai')
                        ->orWhereDate('tanggal_selesai', '>=', now());
                })
                ->pluck('position_id');

            if ($submitterPositionIds->isEmpty() && $user->position_id) {
                $submitterPositionIds->push($user->position_id);
            }

            foreach ($submitterPositionIds->unique() as $positionId) {
                for ($i = 0; $i < $level && $positionId; $i++) {
                    $position = DB::table('positions')
                        ->where('id', $positionId)
                        ->first();

                    $positionId = $position?->parent_id;

                    if ($positionId) {
                        $parentIds[] = $positionId;
                    }
                }
            }

            if (empty($parentIds)) {
                return collect();
            }

            return $query->whereIn('pa.position_id', $parentIds)
                ->get();
        }

        return collect();
    }

    public function prosesKeputusan(
        string $usulanId,
        string $aksi,
        ?string $catatan,
        $user
    ): UsulanBelanja {
        return DB::transaction(function () use (
            $usulanId,
            $aksi,
            $catatan,
            $user
        ) {
            $usulan = UsulanBelanja::whereKey($usulanId)
                ->lockForUpdate()
                ->firstOrFail();

            $instance = DB::table('workflow_instances')
                ->where('id', $usulan->workflow_instance_id)
                ->lockForUpdate()
                ->first();

            if (! $instance || $instance->status !== 'proses') {
                throw ValidationException::withMessages([
                    'aksi' => 'Workflow tidak sedang berjalan.',
                ]);
            }

            $approval = DB::table('approvals')
                ->where('workflow_instance_id', $instance->id)
                ->where('workflow_step_id', $instance->current_step_id)
                ->where('validator_user_id', $user->id)
                ->where('status', 'pending')
                ->where('is_current', true)
                ->lockForUpdate()
                ->first();

            if (! $approval) {
                abort(403, 'Anda bukan validator aktif dokumen ini.');
            }

            $statusApproval = match ($aksi) {
                'approved' => 'approved',
                'revision' => 'revision',
                'rejected' => 'rejected',
                default => throw ValidationException::withMessages([
                    'aksi' => 'Aksi tidak valid.',
                ]),
            };

            DB::table('approvals')
                ->where('id', $approval->id)
                ->update([
                    'status' => $statusApproval,
                    'catatan' => $catatan,
                    'approved_at' => $aksi === 'approved' ? now() : null,
                    'closed_at' => now(),
                    'updated_at' => now(),
                ]);

            DB::table('approval_logs')->insert([
                'id' => (string) Str::uuid(),
                'approval_id' => $approval->id,
                'workflow_instance_id' => $instance->id,
                'user_id' => $user->id,
                'position_id' => $approval->position_id,
                'position_assignment_id' => $approval->position_assignment_id,
                'aksi' => $aksi,
                'catatan' => $catatan,
                'aksi_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($aksi !== 'approved') {
                $usulan->update([
                    'status_usulan' => $aksi,
                    'catatan' => $catatan,
                ]);

                DB::table('workflow_instances')
                    ->where('id', $instance->id)
                    ->update([
                        'status' => $aksi,
                        'finished_at' => now(),
                        'updated_at' => now(),
                    ]);

                return $usulan->fresh();
            }

            // Jangan maju jika masih ada approval wajib yang pending.
            $pending = DB::table('approvals')
                ->where('workflow_instance_id', $instance->id)
                ->where('workflow_step_id', $instance->current_step_id)
                ->where('is_current', true)
                ->where('status', 'pending')
                ->exists();

            if ($pending) {
                return $usulan->fresh();
            }

            $currentStep = DB::table('workflow_steps')
                ->where('id', $instance->current_step_id)
                ->first();

            $nextStep = DB::table('workflow_steps')
                ->where('workflow_id', $instance->workflow_id)
                ->where('step_order', '>', $currentStep->step_order)
                ->where(function ($query) use ($usulan) {
                    $query->whereNotExists(function ($sub) {
                        $sub->selectRaw('1')
                            ->from('workflow_step_sub_kategori_rkbu')
                            ->whereColumn(
                                'workflow_step_sub_kategori_rkbu.workflow_step_id',
                                'workflow_steps.id'
                            );
                    })->orWhereIn(
                        'id',
                        DB::table('workflow_step_sub_kategori_rkbu')
                            ->select('workflow_step_id')
                            ->where(
                                'sub_kategori_rkbu_id',
                                $usulan->sub_kategori_rkbu_id
                            )
                    );
                })
                ->orderBy('step_order')
                ->first();

            DB::table('approvals')
                ->where('workflow_instance_id', $instance->id)
                ->where('workflow_step_id', $instance->current_step_id)
                ->update([
                    'is_current' => false,
                    'updated_at' => now(),
                ]);

            if ($nextStep) {
                DB::table('workflow_instances')
                    ->where('id', $instance->id)
                    ->update([
                        'current_step_id' => $nextStep->id,
                        'updated_at' => now(),
                    ]);

                $this->buatApproval(
                    $instance->id,
                    $nextStep,
                    $usulan
                );

                return $usulan->fresh();
            }

            // Semua tahap workflow selesai.
            DB::table('workflow_instances')
                ->where('id', $instance->id)
                ->update([
                    'status' => 'approved',
                    'finished_at' => now(),
                    'updated_at' => now(),
                ]);

            $usulan->update([
                'status_usulan' => 'approved',
                'tanggal_validasi' => now(),
                'validator_user_id' => $user->id,
                'nama_validasi' => $user->nama,
                'catatan' => $catatan,
            ]);

            return $usulan->fresh();
        }, 3);
    }
}
