<div class="modal fade" id="kt_modal_new_target" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h3 class="modal-title fw-bold">Tambah Mapping Step</h3>
                <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal"
                    aria-label="Tutup">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </button>
            </div>
            <form action="{{ route('workflow-step-sub-kategori-rkbu.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-5">
                        <label for="workflow_step_id" class="form-label required">Workflow dan step</label>
                        <select id="workflow_step_id" name="workflow_step_id" class="form-select"
                            data-control="select2" data-dropdown-parent="#kt_modal_new_target" required>
                            <option value="">Pilih step</option>
                            @foreach ($workflowSteps->groupBy(fn ($step) => $step->workflow?->nama_workflow ?? 'Workflow') as $workflowName => $steps)
                                <optgroup label="{{ $workflowName }}">
                                    @foreach ($steps->sortBy('step_order') as $step)
                                        @php
                                            $validatorName = $step->is_pptk_step
                                                ? 'PPTK'
                                                : ($step->validator_type === 'hierarchy'
                                                    ? 'Atasan / Hirarki ' . max(1, (int) ($step->hierarchy_level ?? 1))
                                                    : ($step->position?->level_jabatan
                                                        ?? $step->systemRole?->nama_role
                                                        ?? $step->validatorRole?->nama_validator
                                                        ?? 'Validator workflow'));
                                        @endphp
                                        <option value="{{ $step->id }}">
                                            Step {{ $step->step_order }} — {{ $validatorName }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="sub_kategori_rkbu_id" class="form-label required">Sub Kategori RKBU</label>
                        <select id="sub_kategori_rkbu_id" name="sub_kategori_rkbu_id" class="form-select"
                            data-control="select2" data-dropdown-parent="#kt_modal_new_target" required>
                            <option value="">Pilih subkategori</option>
                            @foreach ($subKategoriRkbus as $sub)
                                <option value="{{ $sub->id_sub_kategori_rkbu }}">
                                    {{ $sub->nama_sub_kategori_rkbu }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>
