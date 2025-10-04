<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="exampleModalLabel">Validasi Usulan Belanja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="close-modal"></button>
            </div>
            <div class="card-body">
                <h5 class="fs-15 fw-semibold">Validasi Usulan Belanja</h5>
                <div class="d-flex flex-wrap justify-content-evenly">
                    <p class="text-muted mb-0"><i
                            class="mdi mdi-numeric-1-circle text-success fs-18 align-middle me-2 rounded-circle shadow"></i>Completed
                    </p>
                    <p class="text-muted mb-0"><i
                            class="mdi mdi-numeric-3-circle text-info fs-18 align-middle me-2 rounded-circle shadow"></i>In
                        Progress</p>
                    <p class="text-muted mb-0"><i
                            class="mdi mdi-numeric-2-circle text-primary fs-18 align-middle me-2 rounded-circle shadow"></i>To
                        Do</p>
                </div>
            </div>
            <div class="progress animated-progress bg-soft-primary rounded-bottom rounded-0" style="height: 6px;">
                <div class="progress-bar bg-success rounded-0" role="progressbar" style="width: 30%" aria-valuenow="30"
                    aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-info rounded-0" role="progressbar" style="width: 50%" aria-valuenow="50"
                    aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar rounded-0" role="progressbar" style="width: 20%" aria-valuenow="20"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p></p>
            <form method="POST" id="confirmUpdateForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id_usulan_barang" id="id-field" />

                    <div class="mb-3">
                        <label for="orderId" class="form-label">No Invoice</label>
                        <input type="text" id="orderId" class="form-control" readonly />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" id="kategori-field" class="form-control" readonly />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pengusul</label>
                        <input type="text" id="pengusul-field" class="form-control" readonly />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Anggaran</label>
                        <input type="text" id="total-field" class="form-control" readonly />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Status Validasi</label>
                        <select class="form-control" name="status_validasi_pptk" data-choices
                            data-choices-sorting="true" id="status-field">
                            <option value="">-- Pilih --</option>
                            <option value="Disetujui PPTK">Validasi PPTK</option>
                            <option value="Di Tolak">Di Tolak</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date-field" class="form-label">Order Date</label>
                        <input type="date" id="date-field" name="order_date" class="form-control" required />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
