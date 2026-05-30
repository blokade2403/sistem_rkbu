@foreach ($fases as $item)
    <!--begin::Modal Edit Fase-->
    <div class="modal fade" id="modal_edit_fase_{{ $item->id }}"
        tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary"
                        data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24"
                                viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5"
                                    x="6" y="17.3137"
                                    width="16" height="2"
                                    rx="1"
                                    transform="rotate(-45 6 17.3137)"
                                    fill="black" />
                                <rect x="7.41422"
                                    y="6"
                                    width="16" height="2"
                                    rx="1"
                                    transform="rotate(45 7.41422 6)"
                                    fill="black" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form method="POST"
                        action="{{ route('fases.update', $item->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Edit Fase</h1>
                            <div class="text-muted fw-bold fs-5">
                                Update data fase sesuai kebutuhan Anda.
                            </div>
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <label class="required fs-6 fw-bold mb-2">
                                Nama Fase
                            </label>
                            <input type="text"
                                class="form-control form-control-solid"
                                name="nama_fase"
                                value="{{ $item->nama_fase }}"
                                placeholder="Masukkan nama fase" />
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <label class="required fs-6 fw-bold mb-2">
                                Nomor Urutan Fase
                            </label>
                            <input type="number"
                                class="form-control form-control-solid"
                                name="urutan"
                                value="{{ $item->urutan }}"
                                placeholder="Masukkan nomor urutan fase" />
                        </div>
                        <div class="text-center">
                            <button type="reset"
                                class="btn btn-light me-3"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit"
                                class="btn btn-primary">
                                <span class="indicator-label">
                                    Update
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach