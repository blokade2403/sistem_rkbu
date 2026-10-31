<div class="modal fade" id="kt_modal_new_target" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content rounded-3">
        <div class="modal-header"><div><h2 class="modal-title fs-4 fw-bold">Tambah Penempatan</h2><div class="text-muted fs-7">Masukkan informasi lokasi aset.</div></div><button type="button" class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal" aria-label="Tutup"><i class="fas fa-times"></i></button></div>
        <form method="POST" action="{{ route('penempatans.store') }}">@csrf
            <div class="modal-body"><div class="row g-4">
                <div class="col-md-6"><label class="form-label required">Lokasi Barang</label><input name="lokasi_barang" class="form-control form-control-solid" maxlength="500" value="{{ old('lokasi_barang') }}" required placeholder="Contoh: Instalasi Farmasi"></div>
                <div class="col-md-6"><label class="form-label">Gedung</label><input name="gedung" class="form-control form-control-solid" maxlength="255" value="{{ old('gedung') }}" placeholder="Nama gedung (opsional)"></div>
                <div class="col-md-6"><label class="form-label required">Tempat / Ruangan</label><input name="tempat_lokasi" class="form-control form-control-solid" maxlength="500" value="{{ old('tempat_lokasi') }}" required placeholder="Contoh: Ruang Penyimpanan"></div>
                <div class="col-md-6"><label class="form-label">Penanggung Jawab</label><input name="penanggung_jawab" class="form-control form-control-solid" maxlength="255" value="{{ old('penanggung_jawab') }}" placeholder="Nama penanggung jawab (opsional)"></div>
            </div></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Penempatan</button></div>
        </form>
    </div></div>
</div>
