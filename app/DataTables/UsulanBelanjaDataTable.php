<?php

namespace App\DataTables;

use App\Models\UsulanBelanja\UsulanBelanja;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UsulanBelanjaDataTable
{
    public function render(Request $request)
    {
        $isAdministrator = $request->user()->roles()
            ->where('nama_role', 'Administrator')
            ->exists();

        $query = UsulanBelanja::query()
            ->with([
                'pengusul',
                'unit',
                'rkbu.unit',
                'rkbu.subKategoriRkbu',
                'tahunAnggaran',
                'workflowInstance.workflow.steps.position',
                'workflowInstance.workflow.steps.systemRole',
                'workflowInstance.workflow.steps.validatorRole',
                'workflowInstance.workflow.steps.subKategoriRkbus',
                'workflowInstance.currentStep',
                'workflowInstance.approvals.validatorUser',
                'workflowInstance.approvals.position',
                'workflowInstance.approvals.workflowStep.position',
                'workflowInstance.approvals.workflowStep.systemRole',
                'workflowInstance.approvals.workflowStep.validatorRole',
            ])
            ->withCount([
                'items as selected_items_count' => fn (Builder $items) => $items
                    ->whereNotNull('detail_type')
                    ->whereNotNull('detail_id'),
            ])
            ->addSelect([
                'total_nominal' => \Illuminate\Support\Facades\DB::table('usulan_belanja_items')
                    ->selectRaw('COALESCE(SUM(harga_satuan * volume * (1 + COALESCE(ppn, 0) / 100)), 0)')
                    ->whereColumn('usulan_belanja_items.usulan_belanja_id', 'usulan_belanjas.id')
                    ->whereNotNull('detail_type')
                    ->whereNotNull('detail_id'),
            ]);

        if (! $isAdministrator) {
            $query->where('user_id', $request->user()->id);
        }

        if ($request->filled('status')) {
            $query->where('status_usulan', $request->input('status'));
        }

        // Tahun aktif disimpan di session dan tidak boleh dioverride oleh filter request.
        $query->where('tahun_anggaran_id', session('tahun_anggaran_id'));

        if ($request->filled('jenis_kategori_rkbu_id')) {
            $query->where('jenis_kategori_rkbu_id', $request->input('jenis_kategori_rkbu_id'));
        }

        if ($request->filled('sub_kategori_rkbu_id')) {
            $query->where('sub_kategori_rkbu_id', $request->input('sub_kategori_rkbu_id'));
        }

        $query->orderByDesc('created_at');

        return DataTables::eloquent($query)
            ->filter(function (Builder $query) use ($request) {
                $keyword = trim((string) $request->input('search.value', ''));
                if ($keyword === '') {
                    return;
                }

                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $keyword) . '%';
                $query->where(function (Builder $searchQuery) use ($like) {
                    $searchQuery->where('no_usulan_belanja', 'like', $like)
                        ->orWhere('nama_pengusul', 'like', $like)
                        ->orWhereHas('pengusul', fn (Builder $userQuery) => $userQuery->where('nama', 'like', $like))
                        ->orWhereHas('rkbu', fn (Builder $rkbuQuery) => $rkbuQuery->where('nomor_rkbu', 'like', $like))
                        ->orWhereHas('rkbu.subKategoriRkbu', fn (Builder $subcategoryQuery) => $subcategoryQuery->where('nama_sub_kategori_rkbu', 'like', $like));
                });
            })
            ->addIndexColumn()
            ->addColumn('no_inv', fn (UsulanBelanja $usulan) => $this->renderInvoice($usulan))
            ->addColumn('status_validasi', fn (UsulanBelanja $usulan) => $this->renderValidationTimeline($usulan))
            ->addColumn('status_proses', fn (UsulanBelanja $usulan) => $this->renderProcessStatus($usulan))
            ->addColumn('total_nominal', fn (UsulanBelanja $usulan) => 'Rp ' . number_format((float) $usulan->total_nominal, 0, ',', '.'))
            ->addColumn('aksi', fn (UsulanBelanja $usulan) => $this->renderActions($usulan))
            ->rawColumns(['no_inv', 'status_validasi', 'status_proses', 'aksi'])
            ->toJson();
    }

    private function renderInvoice(UsulanBelanja $usulan): string
    {
        $url = route('usulan-belanja.show', $usulan->id);
        $createdAt = $usulan->created_at?->format('Y-m-d H:i:s') ?? '-';
        $pengusul = $usulan->nama_pengusul ?: $usulan->pengusul?->nama ?: '-';
        $unit = $usulan->unit?->nama_unit ?? $usulan->rkbu?->unit?->nama_unit ?? '-';
        $subcategory = $usulan->rkbu?->subKategoriRkbu;
        $subcategoryName = $subcategory
            ? trim(($subcategory->kode_sub_kategori_rkbu ?? '') . ' ' . ($subcategory->nama_sub_kategori_rkbu ?? ''))
            : '-';

        return '<div class="d-flex flex-column py-2">'
            . '<a class="fw-bold fs-5 text-primary" href="' . e($url) . '">#' . e($usulan->no_usulan_belanja) . '</a>'
            . '<span class="text-muted mt-2">' . e($createdAt) . '</span>'
            . '<span class="mt-1">Pengusul: ' . e($pengusul) . '</span>'
            . '<span class="mt-1">Unit: ' . e($unit) . '</span>'
            . '<span class="mt-2">' . e($subcategoryName) . '</span>'
            . '</div>';
    }

    private function renderValidationTimeline(UsulanBelanja $usulan): string
    {
        $instance = $usulan->workflowInstance;
        if (! $instance) {
            return '<span class="text-muted">Belum diajukan untuk validasi</span>';
        }

        $steps = $instance->workflow?->steps ?? collect();
        $subcategoryId = $usulan->sub_kategori_rkbu_id;
        $steps = $steps->filter(function ($step) use ($subcategoryId) {
            $subcategoryIds = $step->subKategoriRkbus->pluck('id_sub_kategori_rkbu');
            return $subcategoryIds->isEmpty() || $subcategoryIds->contains($subcategoryId);
        })->sortBy('step_order');

        if ($steps->isEmpty()) {
            return '<span class="text-muted">Tahapan validasi belum dikonfigurasi</span>';
        }

        $html = '<div class="d-flex flex-column gap-4 border-start border-3 border-gray-300 ps-4 py-1">';
        $hasVisibleStage = false;
        foreach ($steps as $step) {
            $approvals = $instance->approvals->where('workflow_step_id', $step->id);
            $stepTitle = $this->stepTitle($step);
            $isCurrent = $instance->current_step_id === $step->id && $instance->status === 'proses';

            if ($approvals->isNotEmpty()) {
                foreach ($approvals as $approval) {
                    $status = strtolower((string) $approval->status);
                    $color = $status === 'approved' ? 'success' : (in_array($status, ['rejected', 'revision'], true) ? 'danger' : 'primary');
                    $pic = $approval->validatorUser?->nama
                        ?? $approval->position?->nama_jabatan
                        ?? $step->position?->nama_jabatan
                        ?? $step->systemRole?->nama_role
                        ?? $step->validatorRole?->nama_validator
                        ?? 'PIC belum ditentukan';
                    $date = $approval->approved_at
                        ?? $approval->closed_at
                        ?? $approval->received_at;
                    $dateLabel = $date?->format('d-m-Y') ?? 'Menunggu';

                    $check = $status === 'approved'
                        ? '<span class="symbol symbol-20px ms-2"><span class="symbol-label bg-light-success text-success fw-bold">✓</span></span>'
                        : '';
                    $html .= '<div class="d-flex flex-column">'
                        . '<span class="text-muted fs-7">' . e($dateLabel) . '</span>'
                        . '<span class="fw-semibold text-gray-800 mt-1">' . e($stepTitle) . $check . '</span>'
                        . '<span class="text-muted mt-1">PIC: ' . e($pic) . '</span>';
                    if ($status !== 'approved') {
                        $html .= '<span class="badge badge-light-' . $color . ' align-self-start mt-1">'
                            . e($this->approvalStatusLabel($status)) . '</span>';
                    }
                    $html .= '</div>';
                    $hasVisibleStage = true;
                }

                $stepStillWaiting = $approvals->contains(fn ($approval) => strtolower((string) $approval->status) === 'pending');
                if ($stepStillWaiting || in_array($instance->status, ['revision', 'rejected'], true)) {
                    break;
                }
                continue;
            }

            if (! $isCurrent) {
                continue;
            }

            $pic = $step->position?->nama_jabatan
                ?? $step->systemRole?->nama_role
                ?? $step->validatorRole?->nama_validator
                ?? ($step->is_pptk_step ? 'PPTK sesuai subkategori' : 'PIC ditentukan saat tahap aktif');
            $html .= '<div class="d-flex flex-column">'
                . '<span class="text-muted fs-7">Menunggu</span>'
                . '<span class="fw-semibold text-gray-800 mt-1">' . e($stepTitle) . '</span>'
                . '<span class="text-muted mt-1">PIC: ' . e($pic) . '</span>'
                . '<span class="badge badge-light-primary align-self-start mt-1">Menunggu</span>'
                . '</div>';
            $hasVisibleStage = true;
            break;
        }

        return $hasVisibleStage
            ? $html . '</div>'
            : '<span class="text-muted">Belum ada tahapan validasi.</span>';
    }

    private function stepTitle($step): string
    {
        if ($step->is_pptk_step) {
            return 'Validasi PPTK';
        }

        $validator = $step->position?->nama_jabatan
            ?? $step->systemRole?->nama_role
            ?? $step->validatorRole?->nama_validator;

        return $validator
            ? 'Validasi ' . $validator
            : 'Validasi Tahap ' . $step->step_order;
    }

    private function approvalStatusLabel(string $status): string
    {
        return match ($status) {
            'approved' => 'Selesai',
            'rejected' => 'Ditolak',
            'revision' => 'Perlu revisi',
            default => 'Menunggu',
        };
    }

    private function renderProcessStatus(UsulanBelanja $usulan): string
    {
        $status = strtolower((string) $usulan->status_usulan);
        [$label, $color] = match ($status) {
            'draft' => ['Draft', 'secondary'],
            'proses' => ['Dalam Proses', 'primary'],
            'revision' => ['Perlu Revisi', 'warning'],
            'approved' => ['Selesai', 'success'],
            'rejected' => ['Ditolak', 'danger'],
            default => [ucfirst($status ?: 'Tidak diketahui'), 'secondary'],
        };

        return '<span class="badge badge-light-' . $color . '">' . e($label) . '</span>';
    }

    private function renderActions(UsulanBelanja $usulan): string
    {
        $actions = '<a href="' . e(route('usulan-belanja.show', $usulan->id)) . '" class="btn btn-sm btn-icon btn-light-primary" title="Lihat detail" aria-label="Lihat detail">'
            . '<i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></a>';

        if ($usulan->workflow_instance_id) {
            $actions .= ' <a href="' . e(route('usulan-belanja.print', $usulan->id)) . '" target="_blank" rel="noopener" class="btn btn-sm btn-icon btn-light-danger" title="Cetak PDF" aria-label="Cetak PDF">'
                . '<i class="ki-duotone ki-printer fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></a>';
        }

        if ($usulan->status_usulan === 'approved') {
            $actions .= ' <a href="' . e(route('usulan-belanja.print-surat-perintah', $usulan->id)) . '" target="_blank" rel="noopener" class="btn btn-sm btn-icon btn-light-success" title="Print Surat Perintah Belanja" aria-label="Print Surat Perintah Belanja">'
                . '<i class="ki-duotone ki-document fs-2"><span class="path1"></span><span class="path2"></span></i></a>';
        }

        if ($usulan->status_usulan === 'draft' && ! $usulan->workflow_instance_id && (int) $usulan->selected_items_count === 0) {
            $actions .= ' <form method="POST" action="' . e(route('usulan-belanja.destroy', $usulan->id)) . '" class="d-inline" onsubmit="return confirm(\'Hapus Usulan Belanja kosong ini?\')">'
                . csrf_field() . method_field('DELETE')
                . '<button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Hapus" aria-label="Hapus">'
                . '<i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></button></form>';
        }

        return $actions;
    }
}
