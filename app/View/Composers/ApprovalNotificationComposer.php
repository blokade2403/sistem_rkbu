<?php

namespace App\View\Composers;

use App\Services\Workflow\PositionAssignmentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ApprovalNotificationComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();
        $notifications = [];
        if (! $user) {
            $view->with(['approvalNotifications' => $notifications, 'approvalNotificationTotal' => 0]);
            return;
        }

        $yearId = session('tahun_anggaran_id');
        $pending = Schema::hasTable('approvals') && Schema::hasTable('workflow_instances') && Schema::hasTable('workflows')
            ? DB::table('approvals as approval')
                ->join('workflow_instances as instance', 'instance.id', '=', 'approval.workflow_instance_id')
                ->join('workflows as workflow', 'workflow.id', '=', 'instance.workflow_id')
                ->where('approval.status', 'pending')
                ->whereColumn('approval.workflow_step_id', 'instance.current_step_id')
            : null;

        $assignment = Schema::hasTable('position_assignments')
            ? app(PositionAssignmentService::class)->getUserAssignment($user)
            : null;

        $rkbuCount = 0;
        if ($pending && Schema::hasTable('rkbus')) {
            $rkbuQuery = (clone $pending)->whereIn(DB::raw('LOWER(workflow.modul)'), ['rkbu', 'validasi_rkbu'])
                ->join('rkbus as rkbu', 'rkbu.id', '=', 'instance.dokumen_id')
                ->when($yearId, fn ($query) => $query->where('rkbu.tahun_anggaran_id', $yearId))
                ->where(function ($query) use ($user, $assignment) {
                    $query->where('approval.validator_user_id', $user->id);
                    if ($assignment) {
                        $query->orWhere('approval.position_assignment_id', $assignment->id)
                            ->orWhere('approval.position_id', $assignment->position_id)
                            ->orWhere(fn ($legacy) => $legacy->whereNull('approval.position_id')
                                ->where('instance.current_position_id', $assignment->position_id));
                    }
                })
                ->whereNotExists(function ($later) {
                    $later->selectRaw('1')->from('approvals as later_approval')
                        ->whereColumn('later_approval.workflow_instance_id', 'approval.workflow_instance_id')
                        ->where(function ($order) {
                            $order->whereColumn('later_approval.sequence', '>', 'approval.sequence')
                                ->orWhere(fn ($sameSequence) => $sameSequence
                                    ->whereColumn('later_approval.sequence', '=', 'approval.sequence')
                                    ->where(function ($laterCreated) {
                                        $laterCreated->whereColumn('later_approval.created_at', '>', 'approval.created_at')
                                            ->orWhere(fn ($sameTime) => $sameTime
                                                ->whereColumn('later_approval.created_at', '=', 'approval.created_at')
                                                ->whereColumn('later_approval.id', '>', 'approval.id'));
                                    }));
                        });
                });
            $rkbuCount = $rkbuQuery->distinct()->count('approval.workflow_instance_id');
        }
        if ($rkbuCount > 0) {
            $notifications[] = ['label' => 'Validasi RKBU', 'count' => $rkbuCount, 'url' => route('workflowapproval.index'), 'icon' => 'ki-notification-on'];
        }

        $usulanCount = 0;
        if ($pending && Schema::hasTable('usulan_belanjas')) {
            $usulanCount = (clone $pending)->whereIn(DB::raw('LOWER(workflow.modul)'), ['usulan_belanja', 'validasi_usulan_belanja'])
                ->join('usulan_belanjas as usulan', 'usulan.workflow_instance_id', '=', 'instance.id')
                ->where('approval.validator_user_id', $user->id)
                ->where('approval.is_current', true)
                ->where('usulan.status_usulan', 'proses')
                ->when($yearId, fn ($query) => $query->where('usulan.tahun_anggaran_id', $yearId))
                ->distinct()->count('approval.workflow_instance_id');
        }
        if ($usulanCount > 0) {
            $notifications[] = ['label' => 'Validasi Usulan Belanja', 'count' => $usulanCount, 'url' => route('usulan-belanja.validasi.index'), 'icon' => 'ki-document'];
        }

        $pengadaanCount = $this->pengadaanCount($user, $yearId);
        if ($pengadaanCount > 0) {
            $notifications[] = ['label' => 'Approval Pengadaan', 'count' => $pengadaanCount, 'url' => route('usulan-belanja.pengadaan.index'), 'icon' => 'ki-cheque'];
        }

        $view->with([
            'approvalNotifications' => $notifications,
            'approvalNotificationTotal' => array_sum(array_column($notifications, 'count')),
        ]);
    }

    private function pengadaanCount($user, ?string $yearId): int
    {
        if (! Schema::hasTable('spjs') || ! Schema::hasTable('usulan_belanjas')) {
            return 0;
        }

        $query = DB::table('spjs as spj')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
            ->where('usulan.status_usulan', 'approved')
            ->when($yearId, fn ($builder) => $builder->where('usulan.tahun_anggaran_id', $yearId));

        $steps = [];
        $isAdmin = $user->hasRole('Administrator') || $user->hasRole('Super Admin');
        if ($isAdmin || $user->hasRole('Admin Pengadaan')) {
            $steps = array_merge($steps, ['input_pesanan', 'input_bast', 'input_tukar_faktur', 'input_penyerahan_spj_admin']);
        }
        if ($isAdmin || $user->hasRole('Pengurus Barang') || $user->position?->level_jabatan === 'Pengurus Barang') {
            $steps[] = 'validasi_barang';
        }

        $legacyByPosition = [
            'PPK' => 'validasi_ppk', 'PPBJ' => 'validasi_ppbj', 'PPTK' => 'validasi_pptk',
            'Verifikator' => 'validasi_verifikator', 'PPK Keuangan' => 'validasi_ppk_keuangan',
            'Direktur' => 'validasi_direktur',
        ];
        $positionLevel = $user->position?->level_jabatan;
        if (isset($legacyByPosition[$positionLevel])) $steps[] = $legacyByPosition[$positionLevel];
        if ($isAdmin || in_array($positionLevel, ['Admin Pengadaan', 'Bendahara'], true) || $user->hasRole('Bendahara')) {
            $steps[] = 'serah_terima_bendahara';
        }
        if ($isAdmin || in_array($positionLevel, ['Bendahara'], true) || $user->hasRole('Bendahara')) {
            $steps = array_merge($steps, ['verifikasi_bendahara', 'pembayaran']);
        }

        $query->where(function ($builder) use ($steps, $isAdmin, $user) {
            if ($isAdmin) {
                $builder->where('spj.pengadaan_step', '!=', 'selesai');
                return;
            }
            $builder->whereRaw('1 = 0');
            if ($steps) $builder->orWhereIn('spj.pengadaan_step', array_unique($steps));
            if ($user->hasRole('Pendukung PPK') && Schema::hasTable('sub_kategori_pendukung_ppk')) {
                $builder->orWhere(fn ($upload) => $upload->where('spj.pengadaan_step', 'upload_foto')
                    ->whereExists(fn ($assignment) => $assignment->selectRaw('1')->from('sub_kategori_pendukung_ppk as mapping')
                        ->whereColumn('mapping.sub_kategori_rkbu_id', 'usulan.sub_kategori_rkbu_id')->where('mapping.user_id', $user->id)));
            }
            if (! Schema::hasTable('workflow_steps')) return;

            $stepQuery = DB::table('workflow_steps as step')
                ->join('workflows as workflow', 'workflow.id', '=', 'step.workflow_id')
                ->whereRaw('LOWER(workflow.nama_workflow) = ?', ['workflow pengadaan']);
            $roleIds = $user->roles()->pluck('roles.id')->all();
            $validatorRoleIds = method_exists($user, 'validatorRoles') ? $user->validatorRoles()->pluck('validator_roles.id')->all() : [];
            $positionId = $user->position_id;
            $stepQuery->where(function ($match) use ($roleIds, $validatorRoleIds, $positionId) {
                $match->whereRaw('1 = 0');
                if ($positionId) $match->orWhere('step.position_id', $positionId);
                if ($roleIds) $match->orWhereIn('step.role_id', $roleIds);
                if ($validatorRoleIds) $match->orWhereIn('step.validator_role_id', $validatorRoleIds);
            });
            $stepIds = $stepQuery->pluck('step.id');
            if ($stepIds->isNotEmpty()) {
                $builder->orWhere(fn ($workflow) => $workflow->where('spj.pengadaan_step', 'workflow')->whereIn('spj.pengadaan_workflow_step_id', $stepIds));
            }
            if (Schema::hasTable('position_sub_kategori_rkbus') && Schema::hasTable('position_assignments')) {
                $pptkStepIds = DB::table('workflow_steps as step')
                    ->join('workflows as workflow', 'workflow.id', '=', 'step.workflow_id')
                    ->whereRaw('LOWER(workflow.nama_workflow) = ?', ['workflow pengadaan'])
                    ->where('step.is_pptk_step', true)
                    ->pluck('step.id');
                if ($pptkStepIds->isNotEmpty()) {
                    $builder->orWhere(fn ($workflow) => $workflow->where('spj.pengadaan_step', 'workflow')
                        ->whereIn('spj.pengadaan_workflow_step_id', $pptkStepIds)
                        ->whereExists(fn ($mapping) => $mapping->selectRaw('1')->from('position_sub_kategori_rkbus as mapping')
                            ->join('position_assignments as assignment', 'assignment.position_id', '=', 'mapping.position_id')
                            ->whereColumn('mapping.id_sub_kategori_rkbu', 'usulan.sub_kategori_rkbu_id')
                            ->where('assignment.user_id', $user->id)
                            ->where('assignment.is_active', true)
                            ->whereDate('assignment.tanggal_mulai', '<=', today())
                            ->where(fn ($active) => $active->whereNull('assignment.tanggal_selesai')->orWhereDate('assignment.tanggal_selesai', '>=', today()))));
                }
            }
        });

        return (int) $query->distinct()->count('spj.id');
    }
}
