<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog" style="z-index: 1050; display: none;"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tuliskan Keterangan Perubahan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tulikan Keterangan atas Perubahan Komponen Anggaran</p>
                <form id="keteranganForm">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="sub-title">Keterangan Status</h4>
                            <div class="row">
                                <label class="col-sm-4 col-lg-2 col-form-label">Keterangan</label>
                                <div class="col-sm-8 col-lg-10">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon1">*</span>
                                        <textarea name="keterangan_status" rows="5" cols="5" class="form-control"
                                            placeholder="Spesifikasi Komponen"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="sub-title">Upload File Pendukung</h4>
                            <div class="row">
                                <div class="col-sm-8 col-lg-12">
                                    <div class="input-group">
                                        <input class="form-control" type="file" name="upload_file_5"
                                            id="formFileMultiple" multiple />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light ">Save changes</button>
            </div>
        </div>
    </div>
</div>
