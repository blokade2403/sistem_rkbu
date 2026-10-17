<div class="modal fade" id="modalDetailApproval" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    Detail Approval RKBU
                </h3>
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="35%">Nomor RKBU</th>
                                <td id="detail_nomor_rkbu"></td>
                            </tr>
                            <tr>
                                <th>Unit</th>
                                <td id="detail_unit"></td>
                            </tr>
                            <tr>
                                <th>Tahun</th>
                                <td id="detail_tahun"></td>
                            </tr>
                            <tr>
                                <th>Status Workflow</th>
                                <td id="detail_status"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="35%">Validator</th>
                                <td id="detail_validator"></td>
                            </tr>
                            <tr>
                                <th>Step</th>
                                <td id="detail_step"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Dibuat</th>
                                <td id="detail_tanggal"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <h5 class="mb-3">
                    Detail <span id="detail_module"></span>
                </h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead id="theadDetailApproval">
                        </thead>
                        <tbody id="tbodyBarang">
                        </tbody>
                    </table>
                </div>
                @if ($isSuperAdmin)
                    <div id="rkbu-edit-panel" class="border rounded p-5 mb-5" style="display: none;">
                        <h5 class="mb-4">Ubah Data RKBU</h5>
                        <form id="rkbu-edit-form">
                            <div class="row mb-5">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_sumber_dana_id" class="form-label required">Sumber Dana</label>
                                    <select id="edit_sumber_dana_id" class="form-select" required>
                                        <option value="">Pilih sumber dana</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_sub_kegiatan_id" class="form-label required">Sub Kegiatan</label>
                                    <select id="edit_sub_kegiatan_id" class="form-select" required>
                                        <option value="">Pilih sub kegiatan</option>
                                    </select>
                                </div>
                            </div>
                            <div id="rkbu-edit-details" class="d-flex flex-column gap-5"></div>
                        </form>
                    </div>
                    <div id="approval-next-official-wrapper" class="mb-4" style="display: none;">
                        <label for="approval_next_official" class="form-label required">
                            Pejabat Approval Berikutnya (sesuai hirarki)
                        </label>
                        <select id="approval_next_official" class="form-select">
                            <option value="">Pilih pejabat aktif</option>
                        </select>
                    </div>
                @endif
                <div id="approval_attachments" class="mb-5"></div>
                <hr>
                <div class="mb-5">
                    <label class="form-label fw-bold">
                        Catatan Revisi Sebelumnya
                    </label>
                    <div id="history_revision" class="border rounded p-3 bg-light">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">
                        Catatan Approval
                    </label>
                    <textarea id="approval_note" class="form-control" rows="4"
                        placeholder="Isi alasan jika reject atau catatan approval"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">
                    Tutup
                </button>
                @if ($isSuperAdmin)
                    <button type="button" class="btn btn-primary" id="btnEditRkbu">Ubah Data RKBU</button>
                    <button type="button" class="btn btn-light" id="btnCancelEditRkbu" style="display: none;">Batal Ubah</button>
                    <button type="button" class="btn btn-success" id="btnSaveRkbu" style="display: none;">Simpan Perubahan</button>
                @endif
                <button class="btn btn-warning" id="btnRevision">
                    Revision
                </button>
                <button class="btn btn-danger" id="btnReject">
                    Reject
                </button>
                <button class="btn btn-success" id="btnApprove">
                    Approve
                </button>
            </div>
        </div>
    </div>
</div>
