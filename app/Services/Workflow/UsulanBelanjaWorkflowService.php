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
        $this->assertFirstUsulanValidator($user);

        return $this->availableRkbusQuery()
            ->orderBy('rkbus.nomor_rkbu')
            ->get();
    }

    public function getAvailableItems(UsulanBelanja $usulan): Collection
    {
        return $this->getDetailItems($usulan->sub_kategori_rkbu_id);
    }

    public function getSelectedItems(UsulanBelanja $usulan): Collection
    {
        $selected = DB::table('usulan_belanja_items')
            ->where('usulan_belanja_id', $usulan->id)
            ->whereNotNull('detail_type')
            ->get(['detail_type', 'detail_id'])
            ->map(fn ($item) => $item->detail_type . ':' . $item->detail_id)
            ->all();

        return $this->getDetailItems($usulan->sub_kategori_rkbu_id, $selected);
    }

    private function getDetailItems(string $subKategoriId, ?array $selected = null): Collection
    {
        $detailTables = [
            'barjas' => 'rkbu_detail_barjass',
            'persediaan' => 'rkbu_detail_persediaans',
            'modal' => 'rkbu_detail_modals',
            'pegawai' => 'rkbu_detail_pegawais',
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
                ->where('rkbus.sub_kategori_rkbu_id', $subKategoriId);

            if ($selected !== null) {
                $detailIds = collect($selected)
                    ->filter(fn ($selection) => str_starts_with($selection, $type . ':'))
                    ->map(fn ($selection) => substr($selection, strlen($type) + 1))
                    ->all();
                $query->whereIn('item.id', $detailIds);
            } else {
                $query->whereNotExists(function ($subquery) use ($type) {
                    $subquery->selectRaw('1')
                        ->from('usulan_belanja_items')
                        ->where('detail_type', $type)
                        ->whereColumn('detail_id', 'item.id');
                });
            }

            $items = $items->merge(
                $query->select([
                    DB::raw("'{$type}' as detail_type"),
                    'item.id as detail_id',
                    'rkbus.id as rkbu_id',
                    'rkbus.nomor_rkbu',
                    DB::raw("{$name} as nama_barang"),
                    DB::raw("{$specification} as spesifikasi"),
                    'item.total_anggaran',
                    'item.sisa_vol_rkbu',
                    'item.sisa_anggaran_rkbu',
                ])
                    ->orderBy('rkbus.nomor_rkbu')
                    ->get()
            );
        }

        return $items->sortBy('nomor_rkbu')->values();
    }

    private function availableRkbusQuery(?string $subKategoriId = null)
    {
        return DB::table('rkbus')
            ->leftJoin(
                'sub_kategori_rkbus',
                'rkbus.sub_kategori_rkbu_id',
                '=',
                'sub_kategori_rkbus.id_sub_kategori_rkbu'
            )
            ->where('rkbus.status', 'approved')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('usulan_belanjas')
                    ->whereColumn('usulan_belanjas.rkbu_id', 'rkbus.id');
            })
            ->when($subKategoriId, fn ($query) => $query->where(
                'rkbus.sub_kategori_rkbu_id',
                $subKategoriId
            ))
            ->select([
                'rkbus.id',
                'rkbus.nomor_rkbu',
                'rkbus.tahun_anggaran_id',
                'sub_kategori_rkbus.nama_sub_kategori_rkbu',
            ]);
    }

    public function buatUsulan(string $rkbuId, User $user): UsulanBelanja
    {
        return DB::transaction(function () use ($rkbuId, $user) {
            $this->assertFirstUsulanValidator($user);

            $rkbu = DB::table('rkbus')
                ->where('id', $rkbuId)
                ->lockForUpdate()
                ->first();

            if (!$rkbu) {
                throw ValidationException::withMessages([
                    'rkbu_id' => 'Data RKBU tidak ditemukan.',
                ]);
            }

            if ($rkbu->status !== 'approved') {
                throw ValidationException::withMessages([
                    'rkbu_id' => 'Usulan Belanja hanya dapat dibuat untuk RKBU yang sudah disetujui.',
                ]);
            }

            $existing = UsulanBelanja::where('rkbu_id', $rkbu->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'rkbu_id' => 'RKBU ini sudah memiliki usulan belanja.',
                ]);
            }

            // Kunci baris tahun anggaran untuk nomor urut.
            $tahun = DB::table('tahun_anggarans')
                ->where('id', $rkbu->tahun_anggaran_id)
                ->lockForUpdate()
                ->first();

            if (!$tahun) {
                throw ValidationException::withMessages([
                    'rkbu_id' => 'Tahun anggaran RKBU tidak ditemukan.',
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
                'tahun_anggaran_id' => $rkbu->tahun_anggaran_id,
                'status_usulan' => 'draft',
            ]);

            return $usulan;
        }, 3);
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

            if ($usulan->status_usulan !== 'draft' || $usulan->workflow_instance_id) {
                throw ValidationException::withMessages([
                    'rkbu_id' => 'Item hanya dapat ditambahkan selama Usulan Belanja berstatus draft.',
                ]);
            }

            $detailTables = [
                'barjas' => 'rkbu_detail_barjass',
                'persediaan' => 'rkbu_detail_persediaans',
                'modal' => 'rkbu_detail_modals',
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

                $detail = DB::table($table . ' as item')
                    ->join('rkbus', 'rkbus.id', '=', 'item.rkbu_id')
                    ->where('item.id', $detailId)
                    ->where('rkbus.status', 'approved')
                    ->where('rkbus.sub_kategori_rkbu_id', $usulan->sub_kategori_rkbu_id)
                    ->lockForUpdate()
                    ->first(['item.id', 'item.rkbu_id']);

                if (! $detail) {
                    throw ValidationException::withMessages([
                        'items' => 'Item harus berasal dari RKBU approved dengan subkategori yang sama.',
                    ]);
                }

                $alreadySelected = UsulanBelanjaItem::query()
                    ->where('detail_type', $type)
                    ->where('detail_id', $detailId)
                    ->exists();

                if ($alreadySelected) {
                    throw ValidationException::withMessages([
                        'items' => 'Salah satu item sudah digunakan pada Usulan Belanja lain.',
                    ]);
                }

                UsulanBelanjaItem::create([
                    'usulan_belanja_id' => $usulan->id,
                    'rkbu_id' => $detail->rkbu_id,
                    'detail_type' => $type,
                    'detail_id' => $detailId,
                ]);
                $added++;
            }

            return $added;
        }, 3);
    }

    private function assertFirstUsulanValidator(User $user): PositionAssignment
    {
        $assignment = app(PositionAssignmentService::class)
            ->getUserAssignment($user);

        $firstStep = DB::table('workflow_steps')
            ->join('workflows', 'workflow_steps.workflow_id', '=', 'workflows.id')
            ->whereRaw('LOWER(workflows.modul) = ?', ['usulan_belanja'])
            ->where('workflow_steps.step_order', 1)
            ->orderBy('workflow_steps.id')
            ->select([
                'workflow_steps.validator_type',
                'workflow_steps.position_id',
                'workflow_steps.role_id',
                'workflow_steps.validator_role_id',
            ])
            ->first();

        $isFirstStepValidator = $assignment && $firstStep && (
            ($firstStep->validator_type === 'position'
                && $firstStep->position_id === $assignment->position_id)
            || ($firstStep->validator_type === 'role'
                && $firstStep->role_id
                && $user->roles()->whereKey($firstStep->role_id)->exists())
            || ($firstStep->validator_role_id
                && $user->validatorRoles()->whereKey($firstStep->validator_role_id)->exists())
        );

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

            if (!in_array(
                $usulan->status_usulan,
                ['draft', 'revision'],
                true
            )) {
                throw ValidationException::withMessages([
                    'status' => 'Usulan tidak dapat diajukan.',
                ]);
            }

            if ($usulan->workflow_instance_id) {
                throw ValidationException::withMessages([
                    'status' => 'Usulan sudah memiliki instance workflow.',
                ]);
            }

            if (! DB::table('usulan_belanja_items')
                ->where('usulan_belanja_id', $usulan->id)
                ->whereNotNull('detail_type')
                ->exists()) {
                throw ValidationException::withMessages([
                    'status' => 'Pilih minimal satu item RKBU sebelum mengajukan Usulan Belanja.',
                ]);
            }

            $workflow = DB::table('workflows')
                ->whereRaw('LOWER(modul) = ?', ['usulan_belanja'])
                ->first();

            if (!$workflow) {
                throw new RuntimeException(
                    'Workflow Usulan Belanja belum dikonfigurasi.'
                );
            }

            $steps = DB::table('workflow_steps')
                ->where('workflow_id', $workflow->id)
                ->where(function ($query) use ($usulan) {
                    $query->whereNotExists(function ($sub) {
                        $sub->selectRaw('1')
                            ->from('workflow_step_sub_kategori_rkbu');
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

            $instanceId = (string) Str::uuid();
            $first = $steps->first();

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

            $usulan->update([
                'workflow_instance_id' => $instanceId,
                'status_usulan' => 'proses',
            ]);

            $this->buatApproval($instanceId, $first, $usulan);

            DB::table('approval_logs')->insert([
                'id' => (string) Str::uuid(),
                'workflow_instance_id' => $instanceId,
                'user_id' => $user->id,
                'aksi' => 'submit',
                'catatan' => 'Usulan Belanja diajukan.',
                'aksi_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

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
    ): void {
        $approvers = $this->resolveApprovers($step, $usulan);

        if ($approvers->isEmpty() && $step->is_required) {
            throw new RuntimeException(
                'Validator tidak ditemukan untuk tahap workflow: '
                    . $step->step_order
            );
        }

        foreach ($approvers as $approver) {
            DB::table('approvals')->insert([
                'id' => (string) Str::uuid(),
                'workflow_instance_id' => $instanceId,
                'workflow_step_id' => $step->id,
                'validator_role_id' => $step->validator_role_id,
                'validator_user_id' => $approver->user_id,
                'position_id' => $approver->position_id,
                'position_assignment_id' =>
                $approver->position_assignment_id,
                'status' => 'pending',
                'received_at' => now(),
                'sequence' => $step->step_order,
                'is_current' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Resolver dasar.
     * Lihat penjelasan setelah kode untuk batasan konfigurasi.
     */
    private function resolveApprovers(
        object $step,
        UsulanBelanja $usulan
    ) {
        $user = DB::table('users')
            ->where('id', $usulan->user_id)
            ->first();

        if (!$user) {
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
            $positionId = $user->position_id;
            $level = (int) ($step->hierarchy_level ?? 1);
            $parentIds = [];

            for ($i = 0; $i < $level && $positionId; $i++) {
                $position = DB::table('positions')
                    ->where('id', $positionId)
                    ->first();

                $positionId = $position?->parent_id;

                if ($positionId) {
                    $parentIds[] = $positionId;
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

            if (!$instance || $instance->status !== 'proses') {
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

            if (!$approval) {
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
                'position_assignment_id' =>
                $approval->position_assignment_id,
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
