@extends('layouts.main')

@section('content')
    <div class="card mb-6">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-4">
            <div>
                <h2 class="fw-bold text-gray-900 mb-2">Workflow per Sub Kategori RKBU</h2>
                <p class="text-muted mb-0">
                    Step ditampilkan berurutan berdasarkan nomor workflow. Jika subkategori memiliki mapping, hanya step
                    yang dipilih yang dijalankan. Subkategori tanpa mapping memakai workflow utama.
                </p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">
                Tambah Mapping Step
            </button>
        </div>
    </div>

    @include('master_backend.workflow_setting.workflow_step_sub_kategori_rkbu.modal_add')

    @if ($data->isEmpty())
        <div class="card">
            <div class="card-body text-center py-12">
                <h4 class="text-gray-800 mb-2">Belum ada mapping workflow</h4>
                <p class="text-muted mb-0">Tambah mapping untuk menentukan step khusus bagi subkategori RKBU.</p>
            </div>
        </div>
    @else
        @foreach ($data->groupBy('sub_kategori_rkbu_id') as $subcategoryMappings)
            @php
                $subcategory = $subcategoryMappings->first()->subKategoriRkbu;
                $workflowGroups = $subcategoryMappings->groupBy(fn ($mapping) => $mapping->workflowStep->workflow_id);
            @endphp

            <section class="card mb-6">
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <h3 class="fw-bold text-gray-900 mb-1">
                                {{ $subcategory?->nama_sub_kategori_rkbu ?? 'Subkategori tidak ditemukan' }}
                            </h3>
                            <span class="text-muted fs-7">{{ $subcategoryMappings->count() }} step terpilih</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($workflowGroups as $workflowMappings)
                        @php
                            $workflowName = $workflowMappings->first()->workflowStep->workflow->nama_workflow ?? 'Workflow';
                            $orderedMappings = $workflowMappings->sortBy(fn ($mapping) => $mapping->workflowStep->step_order);
                        @endphp

                        <div class="border rounded p-4 {{ !$loop->last ? 'mb-5' : '' }}">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <h4 class="fw-bold text-gray-800 mb-0">{{ $workflowName }}</h4>
                                <span class="badge badge-light-primary">{{ $orderedMappings->count() }} step</span>
                            </div>

                            <div class="d-flex flex-column">
                                @foreach ($orderedMappings as $mapping)
                                    @php
                                        $step = $mapping->workflowStep;
                                        $validatorName = $step->is_pptk_step
                                            ? 'PPTK'
                                            : ($step->validator_type === 'hierarchy'
                                                ? 'Atasan / Hirarki ' . max(1, (int) ($step->hierarchy_level ?? 1))
                                                : ($step->position?->level_jabatan
                                                    ?? $step->systemRole?->nama_role
                                                    ?? $step->validatorRole?->nama_validator
                                                    ?? 'Validator workflow'));
                                    @endphp

                                    <div class="d-flex align-items-center justify-content-between gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge badge-circle badge-light-primary fs-6">
                                                {{ $step->step_order }}
                                            </span>
                                            <div>
                                                <div class="fw-semibold text-gray-800">Step {{ $step->step_order }}</div>
                                                <div class="text-muted fs-7">{{ $validatorName }}</div>
                                            </div>
                                        </div>
                                        <form action="{{ route('workflow-step-sub-kategori-rkbu.destroy', $mapping->id) }}"
                                            method="POST" class="form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    @endif
@endsection
