<style>
    #modalDetailApproval .modal-dialog { max-width: min(1200px, calc(100% - 1.5rem)); }
    #modalDetailApproval .modal-content { border: 0; border-radius: 1rem; overflow: hidden; box-shadow: 0 1rem 3rem rgba(31, 41, 55, .18); }
    #modalDetailApproval .modal-header { padding: 1.25rem 1.5rem; background: linear-gradient(120deg, #f2f7ff, #fff); border-bottom: 1px solid #e8edf5; }
    #modalDetailApproval .modal-body { padding: 1.5rem; }
    #modalDetailApproval .approval-section-title { display: flex; align-items: center; gap: .65rem; margin: 0 0 1rem; font-size: 1.05rem; font-weight: 700; color: #26334a; }
    #modalDetailApproval .approval-section-title i { color: #3699ff; }
    #modalDetailApproval .approval-info-card { height: 100%; padding: 1rem; border: 1px solid #e7ecf3; border-radius: .8rem; background: #fff; }
    #modalDetailApproval .approval-info-card .table { margin: 0; }
    #modalDetailApproval .approval-info-card th { width: 38%; color: #78849a; font-weight: 600; }
    #modalDetailApproval .approval-info-card td { color: #27364b; font-weight: 600; overflow-wrap: anywhere; }
    #modalDetailApproval .approval-info-card th, #modalDetailApproval .approval-info-card td { padding: .65rem .4rem; border-color: #eef1f5; }
    #modalDetailApproval .approval-data-section, #modalDetailApproval .approval-note-card { padding: 1.15rem; border: 1px solid #e7ecf3; border-radius: .8rem; background: #fff; }
    #modalDetailApproval .approval-data-section .table-responsive { border: 1px solid #e7ecf3; border-radius: .6rem; }
    #modalDetailApproval .approval-data-section table { margin: 0; min-width: 600px; }
    #modalDetailApproval .approval-data-section thead th { background: #f5f8fc; color: #59677c; white-space: nowrap; }
    #modalDetailApproval #rkbu-edit-panel { border: 1px solid #dce8f8 !important; border-radius: .9rem !important; padding: 1.25rem !important; background: #f8fbff; }
    #modalDetailApproval #rkbu-edit-panel > h5 { color: #263f64; }
    #modalDetailApproval .rkbu-edit-detail { border: 1px solid #e2eaf4; border-radius: .75rem; box-shadow: 0 .2rem .8rem rgba(31, 41, 55, .04); }
    #modalDetailApproval .rkbu-edit-detail .card-body { padding: 1.1rem; }
    #modalDetailApproval .rkbu-edit-detail h6 { padding-bottom: .75rem; border-bottom: 1px solid #edf1f6; color: #334a68; }
    #modalDetailApproval .form-label { color: #47566b; font-weight: 600; }
    #modalDetailApproval .form-control, #modalDetailApproval .form-select { min-height: 42px; border-color: #dce3ed; border-radius: .55rem; }
    #modalDetailApproval .approval-note-card { background: #f9fbfe; }
    #modalDetailApproval .modal-footer { gap: .5rem; padding: 1rem 1.5rem; border-top: 1px solid #e8edf5; }
    #modalDetailApproval .modal-footer .btn { min-height: 40px; }
    @media (max-width: 767.98px) {
        #modalDetailApproval .modal-dialog { max-width: calc(100% - 1rem); margin: .5rem auto; }
        #modalDetailApproval .modal-header, #modalDetailApproval .modal-body { padding: 1rem; }
        #modalDetailApproval .modal-title { font-size: 1.1rem; }
        #modalDetailApproval .approval-info-card { padding: .75rem; }
        #modalDetailApproval .approval-data-section, #modalDetailApproval .approval-note-card { padding: .85rem; }
        #modalDetailApproval .modal-footer { padding: .85rem 1rem; }
    }
    @media (max-width: 575.98px) {
        #modalDetailApproval .approval-print-label { display: none; }
        #modalDetailApproval .modal-footer { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        #modalDetailApproval .modal-footer .btn { width: 100%; margin: 0; }
    }
</style>

<div class="modal fade" id="modalDetailApproval" tabindex="-1" aria-labelledby="modalDetailApprovalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <span class="d-flex align-items-center justify-content-center rounded-circle bg-light-primary text-primary" style="width:42px;height:42px;">
                        <i class="fas fa-clipboard-check fs-5"></i>
                    </span>
                    <div>
                        <h3 class="modal-title mb-0" id="modalDetailApprovalTitle">Detail Approval RKBU</h3>
                        <div class="text-muted fs-7 mt-1">Informasi usulan dan proses persetujuan</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <a id="approval-print-pdf" href="#" target="_blank" rel="noopener"
                        class="btn btn-sm btn-light-primary disabled" aria-disabled="true">
                        <i class="fas fa-file-pdf me-1"></i><span class="approval-print-label">Cetak PDF</span>
                    </a>
                    <button type="button" class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal" aria-label="Tutup">
                        <i class="ki-duotone ki-cross fs-2"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <section class="mb-5">
                    <h5 class="approval-section-title"><i class="fas fa-info-circle"></i>Ringkasan Approval</h5>
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="approval-info-card">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr><th>Nomor RKBU</th><td id="detail_nomor_rkbu"></td></tr>
                                        <tr><th>Unit</th><td id="detail_unit"></td></tr>
                                        <tr><th>Sumber Dana</th><td id="detail_sumber_dana"></td></tr>
                                        <tr><th>Tahun</th><td id="detail_tahun"></td></tr>
                                        <tr><th>Status Workflow</th><td id="detail_status"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="approval-info-card">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr><th>Validator</th><td id="detail_validator"></td></tr>
                                        <tr><th>Step</th><td id="detail_step"></td></tr>
                                        <tr><th>Tanggal Dibuat</th><td id="detail_tanggal"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="approval-data-section mb-5">
                    <h5 class="approval-section-title"><i class="fas fa-boxes"></i>Detail <span id="detail_module"></span></h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead id="theadDetailApproval"></thead>
                            <tbody id="tbodyBarang"></tbody>
                        </table>
                    </div>
                </section>

                <div id="rkbu-edit-panel" class="mb-5" style="display: none;">
                    <h5 class="approval-section-title"><i class="fas fa-pen-to-square"></i>Ubah Data RKBU</h5>
                    <form id="rkbu-edit-form">
                        <div class="row g-3 mb-4" id="rkbu-edit-header-fields">
                            <div class="col-md-6">
                                <label for="edit_sumber_dana_id" class="form-label required">Sumber Dana</label>
                                <select id="edit_sumber_dana_id" class="form-select" required><option value="">Pilih sumber dana</option></select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_sub_kegiatan_id" class="form-label required">Sub Kegiatan</label>
                                <select id="edit_sub_kegiatan_id" class="form-select" required><option value="">Pilih sub kegiatan</option></select>
                            </div>
                        </div>
                        <div id="rkbu-edit-details" class="d-flex flex-column gap-3"></div>
                        <div id="rkbu-shift-fields" class="row g-3 mt-2" style="display:none;">
                            <div class="col-12">
                                <label for="rkbu_shift_reason" class="form-label required">Keterangan pergeseran anggaran</label>
                                <textarea id="rkbu_shift_reason" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <label for="rkbu_shift_file" class="form-label required">Lampiran bukti/alasan</label>
                                <input id="rkbu_shift_file" type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text">PDF/JPG/PNG, maksimal 5 MB.</div>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="approval-next-official-wrapper" class="mb-4" style="display: none;">
                    <label for="approval_next_official" class="form-label required">Pejabat Approval Berikutnya (sesuai hirarki)</label>
                    <select id="approval_next_official" class="form-select"><option value="">Pilih pejabat aktif</option></select>
                </div>
                <div id="approval_attachments" class="mb-5"></div>
                <section class="approval-note-card mb-4">
                    <label class="form-label fw-bold">Catatan Revisi Sebelumnya</label>
                    <div id="history_revision" class="border rounded p-3 bg-light"></div>
                </section>
                <section class="approval-note-card">
                    <label class="form-label" for="approval_note">Catatan Approval</label>
                    <textarea id="approval_note" class="form-control" rows="4" placeholder="Isi alasan jika reject atau catatan approval"></textarea>
                </section>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnEditRkbu">Ubah Data RKBU</button>
                <button type="button" class="btn btn-light" id="btnCancelEditRkbu" style="display: none;">Batal Ubah</button>
                <button type="button" class="btn btn-success" id="btnSaveRkbu" style="display: none;">Simpan Perubahan</button>
                <button class="btn btn-warning" id="btnRevision">Revision</button>
                <button class="btn btn-danger" id="btnReject">Reject</button>
                <button class="btn btn-success" id="btnApprove">Approve</button>
            </div>
        </div>
    </div>
</div>
