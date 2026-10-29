@extends('layouts.main')

@section('content')
        @include('components.partials.table.headermenu')
    @include('master_backend.workflow_setting.workflow_step.modal_add')

    @php
        $stepsByWorkflow = $workflowSteps->groupBy('workflow_id');
    @endphp

    @forelse ($stepsByWorkflow as $steps)
        @php
            $workflow = $steps->first()->workflow;
        @endphp
        <div class="card mb-7">
            <div class="card-header align-items-center py-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-3 fw-bold text-gray-900">{{ $workflow->nama_workflow ?? 'Workflow tidak ditemukan' }}</span>
                    <span class="text-muted fs-7 mt-1">{{ $steps->count() }} tahap persetujuan</span>
                </div>
                @if ($workflow?->modul)
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary fs-7">{{ strtoupper($workflow->modul) }}</span>
                    </div>
                @endif
            </div>

            <div class="card-body pt-2">
                <div class="workflow-step-list">
                    @foreach ($steps as $item)
                        @php
                            $validatorType = $item->validator_type ?? 'hierarchy';
                            $validatorLabel = match ($validatorType) {
                                'position' => $item->position?->nama_jabatan ?? 'Jabatan belum dipilih',
                                'role' => $item->systemRole?->nama_role ?? 'Role belum dipilih',
                                'user' => $item->validatorRole?->nama_validator ?? 'Validator belum dipilih',
                                default => 'Atasan berdasarkan hierarki' . ($item->hierarchy_level ? ' · Level ' . $item->hierarchy_level : ''),
                            };
                            $validatorKind = match ($validatorType) {
                                'position' => 'Jabatan',
                                'role' => 'Role',
                                'user' => 'Validator',
                                default => 'Hierarki',
                            };
                        @endphp
                        <div class="workflow-step-item d-flex gap-4 position-relative pb-5">
                            <div class="workflow-step-marker flex-shrink-0">
                                <span class="workflow-step-number">{{ $item->step_order }}</span>
                            </div>

                            <div class="border rounded-3 p-4 p-lg-5 flex-grow-1 bg-light-light">
                                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
                                    <div class="flex-grow-1">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                            <h4 class="mb-0 text-gray-900">Tahap {{ $item->step_order }}</h4>
                                            @if ($item->is_required)
                                                <span class="badge badge-light-danger">Wajib</span>
                                            @else
                                                <span class="badge badge-light">Opsional</span>
                                            @endif
                                            @if ($item->is_pptk_step)
                                                <span class="badge badge-light-primary">Tahap PPTK</span>
                                            @endif
                                        </div>
                                        <div class="text-gray-800 fw-semibold">{{ $validatorLabel }}</div>
                                        <div class="text-muted fs-7 mt-1">{{ $validatorKind }} · Persetujuan {{ $item->approval_type === 'parallel' ? 'bersamaan' : 'berurutan' }}</div>

                                        @if ($item->subKategoriRkbus->isNotEmpty())
                                            <div class="mt-4">
                                                <div class="text-muted fs-8 fw-bold text-uppercase mb-2">Berlaku untuk subkategori</div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($item->subKategoriRkbus as $subKategori)
                                                        <span class="badge badge-light-info">{{ $subKategori->nama_sub_kategori_rkbu }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-muted fs-8 mt-3">Berlaku untuk semua subkategori workflow ini</div>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-start gap-2 flex-shrink-0">
                                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target_form{{ $item->id }}">
                                            <i class="fas fa-pen me-1"></i> Edit
                                        </button>
                                        <form action="{{ route('workflow_steps.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-15">
                <i class="fas fa-route fs-3x text-muted mb-5"></i>
                <h3 class="text-gray-800">Belum ada tahap workflow</h3>
                <p class="text-muted mb-0">Tambahkan tahap pertama melalui tombol tambah di atas.</p>
            </div>
        </div>
    @endforelse

    @include('master_backend.workflow_setting.workflow_step.modal_edit')

    <style>
        .workflow-step-list { --step-line-left: 19px; }
        .workflow-step-item:last-child { padding-bottom: 0 !important; }
        .workflow-step-marker { width: 40px; display: flex; justify-content: center; position: relative; }
        .workflow-step-marker::after {
            content: ''; position: absolute; top: 40px; bottom: -1.5rem; left: 50%;
            width: 2px; background: #e4e6ef; transform: translateX(-50%);
        }
        .workflow-step-item:last-child .workflow-step-marker::after { display: none; }
        .workflow-step-number {
            z-index: 1; display: inline-flex; align-items: center; justify-content: center;
            width: 40px; height: 40px; border-radius: 50%; background: #eef6ff;
            color: #3699ff; font-weight: 700; border: 1px solid #c9e3ff;
        }
        @media (max-width: 575.98px) {
            .workflow-step-item { gap: .75rem !important; }
            .workflow-step-marker, .workflow-step-number { width: 34px; }
            .workflow-step-number { height: 34px; }
            .workflow-step-marker::after { top: 34px; }
        }
    </style>
@endsection
