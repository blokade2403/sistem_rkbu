<div class="modal fade" id="modal_tanggal_perencanaan_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content rounded-3">
        <div class="modal-header border-0 pb-0"><div><span class="badge badge-light-primary mb-2">PENGATURAN CETAK</span><h2 class="modal-title fs-2 fw-bolder">Tambah Tanggal Pengesahan</h2></div><button type="button" class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal"><i class="fas fa-times"></i></button></div>
        <form method="POST" action="{{ route('tanggal_perencanaans.store') }}">
            @csrf
            <div class="modal-body"><div class="row g-5">
                <div class="col-md-6"><label class="form-label required">Tahun anggaran</label><select class="form-select tanggal-tahun-select" name="id_tahun_anggaran" required><option value="">Pilih tahun anggaran</option>@foreach ($tahun_anggarans as $tahun)<option value="{{ $tahun->id }}" data-fase-id="{{ $tahun->fase_id }}" data-fase-nama="{{ $tahun->fase?->nama_fase }}">{{ $tahun->nama_tahun_anggaran }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Fase (mengikuti tahun anggaran)</label><input type="text" class="form-control tanggal-fase-label" value="Pilih tahun anggaran terlebih dahulu" readonly><input type="hidden" name="fase_id"></div>
                <div class="col-md-6"><label class="form-label required">Tanggal pengesahan</label><input class="form-control" type="date" name="tanggal" required></div>
                <div class="col-md-6"><label class="form-label">Kota</label><input class="form-control" name="kota" maxlength="255" placeholder="Contoh: Jakarta"></div>
                <div class="col-md-6"><label class="form-label">Nomor DPA</label><input class="form-control" name="no_dpa" maxlength="255" placeholder="Opsional"></div>
                <div class="col-md-6"><label class="form-label required">Status</label><select class="form-select" name="status" required><option value="aktif">Aktif</option><option value="tidak aktif">Tidak aktif</option></select></div>
            </div><div class="form-text mt-4">Tanggal aktif untuk tahun dan fase ini akan digunakan pada cetakan semua RKBU dan RBA.</div></div>
            <div class="modal-footer border-0 pt-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-check me-2"></i>Simpan</button></div>
        </form>
    </div></div>
</div>
