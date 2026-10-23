@foreach ($workflowSteps as $step)
    <div class="modal fade" id="kt_modal_new_target_form{{ $step->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal" aria-label="Tutup">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </button>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form id="workflow-step-edit-{{ $step->id }}" class="form form-submit js-workflow-step-edit"
                        action="{{ route('workflow_steps.update', $step->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-10 text-center">
                            <h1 class="mb-3">Edit Workflow Step</h1>
                            <div class="text-muted">Atur penerima dan perilaku approval untuk tahap ini.</div>
                        </div>

                        <div class="mb-8">
                            <label class="required fs-6 fw-bold mb-2">Workflow</label>
                            <select class="form-select" name="workflow_id" data-control="select2" data-dropdown-parent="#kt_modal_new_target_form{{ $step->id }}" required>
                                <option value="">Pilih workflow</option>
                                @foreach ($workflows as $workflow)
                                    <option value="{{ $workflow->id }}" {{ $workflow->id == $step->workflow_id ? 'selected' : '' }}>
                                        {{ $workflow->nama_workflow }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-8">
                            <label class="required fs-6 fw-bold mb-2">Jenis Validator</label>
                            <select class="form-select js-validator-type" name="validator_type" data-control="select2" data-dropdown-parent="#kt_modal_new_target_form{{ $step->id }}" required>
                                <option value="hierarchy" {{ $step->validator_type === 'hierarchy' ? 'selected' : '' }}>Atasan berdasarkan hierarki</option>
                                <option value="position" {{ $step->validator_type === 'position' ? 'selected' : '' }}>Jabatan tertentu</option>
                                <option value="role" {{ $step->validator_type === 'role' ? 'selected' : '' }}>Role tertentu</option>
                                <option value="user" {{ $step->validator_type === 'user' ? 'selected' : '' }}>Pengguna tertentu</option>
                            </select>
                        </div>

                        <div class="mb-8 js-hierarchy-area">
                            <label class="required fs-6 fw-bold mb-2">Level Hierarki</label>
                            <input type="number" min="1" class="form-control" name="hierarchy_level" value="{{ $step->hierarchy_level ?? 1 }}">
                            <small class="text-muted">1 = atasan langsung, 2 = atasan dari atasan.</small>
                        </div>

                        <div class="mb-8 js-position-area">
                            <label class="required fs-6 fw-bold mb-2">Jabatan</label>
                            <select class="form-select" name="position_id" data-control="select2" data-dropdown-parent="#kt_modal_new_target_form{{ $step->id }}">
                                <option value="">Pilih jabatan</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}" {{ $position->id == $step->position_id ? 'selected' : '' }}>
                                        {{ $position->nama_jabatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-8 js-role-area">
                            <label class="required fs-6 fw-bold mb-2">Role</label>
                            <select class="form-select" name="role_id" data-control="select2" data-dropdown-parent="#kt_modal_new_target_form{{ $step->id }}">
                                <option value="">Pilih role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $role->id == $step->role_id ? 'selected' : '' }}>
                                        {{ $role->nama_role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-8">
                            <label class="fs-6 fw-bold mb-2 js-validator-role-label">Validator</label>
                            <select class="form-select js-validator-role" name="validator_role_id" data-control="select2" data-dropdown-parent="#kt_modal_new_target_form{{ $step->id }}">
                                <option value="">Tidak ada</option>
                                @foreach ($validatorRoles as $validatorRole)
                                    <option value="{{ $validatorRole->id }}" {{ $validatorRole->id == $step->validator_role_id ? 'selected' : '' }}>
                                        {{ $validatorRole->nama_validator }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-8">
                            <label class="required fs-6 fw-bold mb-2">Tipe Approval</label>
                            <select class="form-select" name="approval_type" required>
                                <option value="sequential" {{ $step->approval_type === 'sequential' ? 'selected' : '' }}>Berurutan</option>
                                <option value="parallel" {{ $step->approval_type === 'parallel' ? 'selected' : '' }}>Paralel</option>
                            </select>
                        </div>

                        <div class="mb-8">
                            <label class="required fs-6 fw-bold mb-2">Urutan</label>
                            <input type="number" min="1" class="form-control" name="step_order" value="{{ $step->step_order }}" required>
                        </div>

                        <div class="form-check form-check-custom form-check-solid mb-5">
                            <input class="form-check-input" type="checkbox" name="is_required" value="1" {{ $step->is_required ? 'checked' : '' }}>
                            <label class="form-check-label">Tahap wajib</label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid mb-8">
                            <input class="form-check-input" type="checkbox" name="is_pptk_step" value="1" {{ $step->is_pptk_step ? 'checked' : '' }}>
                            <label class="form-check-label">Tahap PPTK</label>
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    document.querySelectorAll('.js-workflow-step-edit').forEach(function (form) {
        const typeSelect = form.querySelector('.js-validator-type');
        const areas = {
            hierarchy: form.querySelector('.js-hierarchy-area'),
            position: form.querySelector('.js-position-area'),
            role: form.querySelector('.js-role-area'),
        };

        function updateValidatorFields() {
            const type = typeSelect.value;
            Object.entries(areas).forEach(function ([key, area]) {
                const visible = key === type;
                area.classList.toggle('d-none', !visible);
                area.querySelector('input, select').required = visible;
            });

            const validatorRole = form.querySelector('.js-validator-role');
            validatorRole.required = type === 'user';
            form.querySelector('.js-validator-role-label').classList.toggle('required', type === 'user');
        }

        typeSelect.addEventListener('change', updateValidatorFields);
        updateValidatorFields();
    });
</script>
