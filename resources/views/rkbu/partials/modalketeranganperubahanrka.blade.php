<div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="modalKeteranganLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-body pb-2">

                <!-- Gambar -->
                <div class="onboarding-media text-center mb-3">
                    <img src="{{ asset('assets/images/file.png') }}" alt="file" width="335" class="img-fluid"
                        data-app-light-img="images/file.png" data-app-dark-img="images/file.png" />
                </div>

                <!-- Keterangan -->
                <div class="onboarding-content mb-3">
                    <h4 class="onboarding-title text-body mb-1">Tuliskan Keterangan Perubahan</h4>
                    <div class="onboarding-info mb-3">
                        Contoh : Perubahan harga karena menyesuaikan harga e-katalog, dan lain-lain.
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <textarea class="form-control" placeholder="Tuliskan Keterangan" name="keterangan_status" id="keteranganInput"
                            style="height: 120px"></textarea>
                        <label for="keteranganInput">Keterangan</label>
                    </div>
                    <!-- Upload -->
                    <h4 class="onboarding-title text-body mb-1">Upload File Pendukung</h4>
                    <div class="onboarding-info mb-3">
                        Contoh : Nota Dinas, KAP, dan dokumen pendukung lainnya.
                    </div>
                    <div>
                        <input class="form-control form-control-sm" type="file" name="upload_file_5" />
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium shadow-none"
                    data-bs-dismiss="modal">
                    <i class="ri-close-line me-1 align-middle"></i> Tutup
                </a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
