<div class="modal fade" id="editPenempatan{{ $penempatan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content rounded-3">
        <div class="modal-header"><div><h2 class="modal-title fs-4 fw-bold">Edit Penempatan</h2><div class="text-muted fs-7">Perbarui informasi lokasi aset.</div></div><button type="button" class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal" aria-label="Tutup"><i class="fas fa-times"></i></button></div>
        <form method="POST" action="{{ route('penempatans.update', $penempatan) }}">@csrf @method('PUT')
            <div class="modal-body"><div class="row g-4">
                <div class="col-md-6"><label class="form-label required">Lokasi Barang</label><input name="lokasi_barang" class="form-control form-control-solid" maxlength="500" value="{{ $penempatan->lokasi_barang }}" required></div>
                <div class="col-md-6"><label class="form-label">Gedung</label><input name="gedung" class="form-control form-control-solid" maxlength="255" value="{{ $penempatan->gedung }}"></div>
                <div class="col-md-6"><label class="form-label required">Tempat / Ruangan</label><input name="tempat_lokasi" class="form-control form-control-solid" maxlength="500" value="{{ $penempatan->tempat_lokasi }}" required></div>
                <div class="col-md-6"><label class="form-label">Penanggung Jawab</label><input name="penanggung_jawab" class="form-control form-control-solid" maxlength="255" value="{{ $penempatan->penanggung_jawab }}"></div>
            </div></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button></div>
        </form>
    </div></div>
</div>
