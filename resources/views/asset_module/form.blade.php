@extends('layouts.main')

@section('content')
    @php
        $isEdit = (bool) $asset;
        $displayValue = fn ($field, $default = null) => old($field, $asset ? $asset->{$field} : $default);
        $modeLabel = $isEdit ? 'PEMELIHARAAN DATA' : ($isManual ? 'ASET HIBAH / MANUAL' : 'TRANSFER DARI BELANJA');
    @endphp

    <div class="asset-form-page">
        <div class="asset-form-hero mb-6">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-4">
                <div class="d-flex align-items-center gap-4">
                    <span class="asset-form-hero-icon"><i class="fas fa-cube"></i></span>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="badge badge-light-primary">{{ $modeLabel }}</span>
                            @if ($sourceItem)<span class="badge badge-light-success">Penerimaan &amp; pembayaran selesai</span>@endif
                        </div>
                        <h1 class="fs-2 fw-bold text-gray-900 mb-1">{{ $title }}</h1>
                        <div class="text-muted">Lengkapi data aset agar mudah dikenali, ditelusuri, dan dikelola oleh unit.</div>
                    </div>
                </div>
                <a href="{{ route('asset-module.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-2"></i>Kembali ke daftar</a>
            </div>
        </div>

        <div class="asset-form-steps mb-6">
            <div class="asset-form-step is-complete"><span><i class="fas fa-check"></i></span><div><strong>Perolehan</strong><small>Asal dan nilai barang</small></div></div>
            <div class="asset-form-step is-active"><span>2</span><div><strong>Identitas</strong><small>Kode dan kondisi aset</small></div></div>
            <div class="asset-form-step"><span>3</span><div><strong>Penanggung jawab</strong><small>Pengguna dan lokasi</small></div></div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-start gap-3 mb-6">
                <i class="fas fa-circle-exclamation mt-1"></i>
                <div><div class="fw-bold mb-1">Periksa kembali data berikut:</div><ul class="mb-0 ps-4">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            </div>
        @endif

        @if ($sourceItem)
            <section class="asset-source-card card border-0 shadow-sm mb-6">
                <div class="card-body p-5 p-lg-6">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-5">
                        <div class="d-flex align-items-center gap-3">
                            <span class="asset-section-icon bg-light-primary text-primary"><i class="fas fa-link"></i></span>
                            <div><h2 class="fs-4 fw-bold text-gray-900 mb-1">Asal Perolehan Terhubung</h2><div class="text-muted fs-7">Informasi ini diambil dari RKBU dan SPJ, dan menjadi jejak asal aset.</div></div>
                        </div>
                        <span class="badge badge-light-success">Terverifikasi</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-lg-6"><div class="asset-source-main"><small>Nama barang</small><div class="fw-bold fs-5">{{ $sourceItem->nama_barang ?: 'Item modal' }}</div><div class="text-muted mt-1">{{ $sourceItem->spesifikasi ?: 'Spesifikasi tidak dicantumkan' }}</div></div></div>
                        <div class="col-sm-6 col-lg-3"><div class="asset-source-stat"><small>Nomor RKBU</small><strong>{{ $sourceItem->nomor_rkbu }}</strong></div></div>
                        <div class="col-sm-6 col-lg-3"><div class="asset-source-stat"><small>Unit pemilik</small><strong>{{ $sourceItem->nama_unit ?: '-' }}</strong></div></div>
                        <div class="col-sm-6 col-lg-3"><div class="asset-source-stat"><small>Jumlah diterima</small><strong>{{ $sourceItem->volume_diterima }} {{ $sourceItem->satuan }}</strong></div></div>
                        <div class="col-sm-6 col-lg-3"><div class="asset-source-stat"><small>Tanggal pembayaran</small><strong>{{ $sourceItem->tanggal_pembayaran ?: '-' }}</strong></div></div>
                        <div class="col-sm-6 col-lg-3"><div class="asset-source-stat"><small>Tanggal penerimaan</small><strong>{{ $sourceItem->tgl_barang_datang ?: '-' }}</strong></div></div>
                        <div class="col-sm-6 col-lg-3"><div class="asset-source-stat"><small>Nomor BAST</small><strong>{{ $sourceItem->no_bast_serah_terima_unit ?: '-' }}</strong></div></div>
                    </div>
                </div>
            </section>
        @endif

        <form method="POST" action="{{ $isEdit ? route('asset-module.update', $asset) : ($isManual ? route('asset-module.manual-store') : route('asset-module.store', $sourceItem->spj_detail_id)) }}" enctype="multipart/form-data">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            @if ($isManual)
                <section class="card asset-form-card border-0 shadow-sm mb-6">
                    <div class="card-header border-0 pt-6 px-6 px-lg-8">
                        <div class="d-flex align-items-center gap-3">
                            <span class="asset-section-icon bg-light-warning text-warning"><i class="fas fa-hand-holding-heart"></i></span>
                            <div><h2 class="fs-4 fw-bold text-gray-900 mb-1">Informasi Perolehan Hibah</h2><div class="text-muted fs-7">Catat dokumen pemberian dan nilai saat barang diterima.</div></div>
                        </div>
                    </div>
                    <div class="card-body px-6 px-lg-8 pb-7">
                        <div class="row g-5">
                            @if (auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin'))
                                <div class="col-md-6"><label class="form-label required fw-semibold">Unit Pemilik</label><select name="unit_id" class="form-select form-select-lg" required><option value="">Pilih unit</option>@foreach ($units as $unit)<option value="{{ $unit->id }}" @selected(old('unit_id') === $unit->id)>{{ $unit->nama_unit }}</option>@endforeach</select></div>
                            @else
                                <div class="col-md-6"><label class="form-label fw-semibold">Unit Pemilik</label><div class="asset-readonly-field"><i class="fas fa-building text-primary"></i><span>{{ auth()->user()->unit?->nama_unit }}</span></div></div>
                            @endif
                            <div class="col-md-6"><label class="form-label required fw-semibold">Asal Hibah / Nomor Berita Acara</label><input name="hibah" value="{{ old('hibah') }}" class="form-control form-control-lg" maxlength="1000" required placeholder="Nama pemberi hibah atau nomor dokumen"><div class="form-text">Gunakan nomor berita acara jika tersedia.</div></div>
                            <div class="col-md-6"><label class="form-label required fw-semibold">Nama Aset</label><input name="nama_asset" value="{{ old('nama_asset') }}" class="form-control" maxlength="255" required placeholder="Contoh: Laptop operasional"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Spesifikasi</label><textarea name="spek" class="form-control" rows="3" maxlength="5000" placeholder="Merek, tipe, ukuran, atau informasi teknis lainnya">{{ old('spek') }}</textarea></div>
                            <div class="col-md-3 col-6"><label class="form-label required fw-semibold">Tanggal Perolehan</label><input type="date" name="tgl_asset" value="{{ old('tgl_asset') }}" class="form-control" required></div>
                            <div class="col-md-3 col-6"><label class="form-label required fw-semibold">Tahun Perolehan</label><input type="number" name="tahun_perolehan" value="{{ old('tahun_perolehan', now()->year) }}" class="form-control" min="1900" max="{{ now()->year + 1 }}" required></div>
                            <div class="col-md-3 col-6"><label class="form-label required fw-semibold">Nilai Perolehan</label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="harga_asset" value="{{ old('harga_asset') }}" class="form-control" min="0" step="0.01" required placeholder="0"></div></div>
                            <div class="col-md-3 col-6"><label class="form-label required fw-semibold">Jumlah</label><input type="number" name="jumlah_asset" value="{{ old('jumlah_asset', 1) }}" class="form-control" min="0.001" step="0.001" required></div>
                            <div class="col-md-3"><label class="form-label fw-semibold">Satuan</label><input name="satuan" value="{{ old('satuan') }}" class="form-control" maxlength="255" placeholder="Unit, buah, set"></div>
                            <div class="col-md-3"><label class="form-label fw-semibold">Pemberi Hibah</label><input name="penyedia" value="{{ old('penyedia') }}" class="form-control" maxlength="255"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Sumber Anggaran / Keterangan</label><input name="sumber_anggaran" value="{{ old('sumber_anggaran', 'Hibah') }}" class="form-control" maxlength="255"></div>
                        </div>
                    </div>
                </section>
            @endif

            <section class="card asset-form-card border-0 shadow-sm mb-6">
                <div class="card-header border-0 pt-6 px-6 px-lg-8">
                    <div class="d-flex align-items-center gap-3"><span class="asset-section-icon bg-light-primary text-primary"><i class="fas fa-fingerprint"></i></span><div><h2 class="fs-4 fw-bold text-gray-900 mb-1">Identitas Aset</h2><div class="text-muted fs-7">Nomor register dibuat otomatis jika dikosongkan.</div></div></div>
                </div>
                <div class="card-body px-6 px-lg-8 pb-7">
                    <div class="row g-5">
                        @foreach ([['kode_asset','Kode Aset'], ['no_register','Nomor Register'], ['merk','Merk'], ['type','Tipe / Model'], ['serial_number','Nomor Seri'], ['kapitalisasi','Kapitalisasi']] as [$field, $label])
                            <div class="col-md-6"><label class="form-label fw-semibold">{{ $label }}</label><input name="{{ $field }}" value="{{ $displayValue($field) }}" class="form-control" maxlength="255" @if ($field === 'no_register') placeholder="Dibuat otomatis jika kosong" @endif></div>
                        @endforeach
                        <div class="col-md-6"><label class="form-label required fw-semibold">Kondisi Aset</label><select name="kondisi_asset" class="form-select" required>@foreach (['Baik', 'Rusak Ringan', 'Rusak Berat', 'Belum Dinilai'] as $condition)<option value="{{ $condition }}" @selected(old('kondisi_asset', $asset?->kondisi_asset ?? 'Baik') === $condition)>{{ $condition }}</option>@endforeach</select></div>
                    </div>
                </div>
            </section>

            <section class="card asset-form-card border-0 shadow-sm mb-6">
                <div class="card-header border-0 pt-6 px-6 px-lg-8">
                    <div class="d-flex align-items-center gap-3"><span class="asset-section-icon bg-light-success text-success"><i class="fas fa-user-tag"></i></span><div><h2 class="fs-4 fw-bold text-gray-900 mb-1">Penggunaan dan Lokasi</h2><div class="text-muted fs-7">Isi penempatan saat ini untuk mempermudah inventarisasi.</div></div></div>
                </div>
                <div class="card-body px-6 px-lg-8 pb-7">
                    <div class="row g-5">
                        <div class="col-md-6"><label class="form-label fw-semibold" for="asset-penanggung-jawab">Penanggung Jawab</label><select name="penanggung_jawab_user_id" id="asset-penanggung-jawab" class="form-select"><option value="">Pilih penanggung jawab</option>@foreach ($users as $user)<option value="{{ $user->id }}" data-position="{{ $user->position?->nama_jabatan }}" @selected(old('penanggung_jawab_user_id', $asset?->penanggung_jawab_user_id) === $user->id || (! old('penanggung_jawab_user_id') && ! $asset?->penanggung_jawab_user_id && $asset?->penanggung_jawab === $user->nama))>{{ $user->nama }}{{ $user->nip ? ' · '.$user->nip : '' }}</option>@endforeach</select><div class="form-text">Daftar diambil dari user aktif.</div></div>
                        <div class="col-md-6"><label class="form-label fw-semibold" for="asset-jabatan">Jabatan Pengguna</label><input name="jabatan_pengguna" id="asset-jabatan" value="{{ old('jabatan_pengguna', $asset?->jabatan_pengguna) }}" class="form-control" maxlength="255" readonly placeholder="Terisi otomatis dari penanggung jawab"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold" for="asset-penempatan">Lokasi / Ruangan</label><select name="id_penempatan" id="asset-penempatan" class="form-select"><option value="">Pilih lokasi / ruangan</option>@foreach ($penempatans as $penempatan)@php($locationLabel = collect([$penempatan->lokasi_barang, $penempatan->gedung, $penempatan->tempat_lokasi])->filter()->implode(' - '))<option value="{{ $penempatan->id_penempatan }}" @selected(old('id_penempatan', $asset?->id_penempatan) === $penempatan->id_penempatan)>{{ $locationLabel }}</option>@endforeach</select><div class="form-text">Lokasi dikelola melalui data Penempatan.</div></div>
                        @foreach ([['pengguna_asset','Nama Pengguna'], ['nip_pengguna','NIP Pengguna'], ['kategori_asset_bergerak','Kategori Aset Bergerak']] as [$field, $label])
                            <div class="col-md-6"><label class="form-label fw-semibold">{{ $label }}</label><input name="{{ $field }}" value="{{ $displayValue($field) }}" class="form-control" maxlength="255" placeholder="{{ $label }}"></div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="card asset-form-card border-0 shadow-sm mb-6">
                <div class="card-header border-0 pt-6 px-6 px-lg-8">
                    <div class="d-flex align-items-center gap-3"><span class="asset-section-icon bg-light-info text-info"><i class="fas fa-car"></i></span><div><h2 class="fs-4 fw-bold text-gray-900 mb-1">Data Kendaraan</h2><div class="text-muted fs-7">Bagian ini opsional dan hanya diisi untuk aset kendaraan.</div></div></div>
                </div>
                <div class="card-body px-6 px-lg-8 pb-7">
                    <div class="row g-5">
                        <div class="col-md-3"><label class="form-label fw-semibold">Tanggal BPKB</label><input type="date" name="tgl_bpkb" value="{{ old('tgl_bpkb', $asset?->tgl_bpkb?->format('Y-m-d')) }}" class="form-control"></div>
                        @foreach ([['no_rangka','Nomor Rangka'], ['no_mesin','Nomor Mesin'], ['no_polisi','Nomor Polisi']] as [$field, $label])
                            <div class="col-md-3"><label class="form-label fw-semibold">{{ $label }}</label><input name="{{ $field }}" value="{{ $displayValue($field) }}" class="form-control" maxlength="255"></div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="card asset-form-card border-0 shadow-sm mb-6">
                <div class="card-header border-0 pt-6 px-6 px-lg-8">
                    <div class="d-flex align-items-center gap-3"><span class="asset-section-icon bg-light-warning text-warning"><i class="fas fa-paperclip"></i></span><div><h2 class="fs-4 fw-bold text-gray-900 mb-1">Dokumen dan Foto</h2><div class="text-muted fs-7">Lampirkan dokumentasi aset untuk membantu pemeriksaan.</div></div></div>
                </div>
                <div class="card-body px-6 px-lg-8 pb-7">
                    <div class="row g-5">
                        @if (! $isManual)
                            <div class="col-12"><label class="form-label fw-semibold">Informasi Hibah</label><input name="hibah" value="{{ $displayValue('hibah') }}" class="form-control" maxlength="1000" placeholder="Isi jika aset berasal dari hibah"></div>
                        @endif
                        <div class="col-md-6"><label class="asset-upload-box"><span class="asset-upload-icon"><i class="fas fa-image"></i></span><span><strong>Foto Aset</strong><small>Format JPG/PNG, maksimal 5 MB</small></span><input type="file" name="foto" accept="image/png,image/jpeg"></label></div>
                        <div class="col-md-6"><label class="asset-upload-box"><span class="asset-upload-icon"><i class="fas fa-file-pdf"></i></span><span><strong>Dokumen SPTJM</strong><small>PDF/JPG/PNG, maksimal 5 MB</small></span><input type="file" name="upload_sptjm" accept="application/pdf,image/png,image/jpeg"></label></div>
                    </div>
                </div>
            </section>

            <div class="asset-form-actions d-flex flex-wrap justify-content-between align-items-center gap-3 mb-8">
                <div class="text-muted fs-7"><i class="fas fa-circle-info me-1"></i>Pastikan kondisi dan penempatan aset sudah sesuai.</div>
                <div class="d-flex gap-3"><a href="{{ route('asset-module.index') }}" class="btn btn-light">Batal</a><button type="submit" class="btn btn-primary px-6"><i class="fas {{ $isEdit ? 'fa-floppy-disk' : 'fa-check' }} me-2"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Registrasikan Aset' }}</button></div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const person = document.getElementById('asset-penanggung-jawab');
            const position = document.getElementById('asset-jabatan');
            if (!person || !position) return;
            const syncPosition = () => { position.value = person.selectedOptions[0]?.dataset.position || ''; };
            person.addEventListener('change', syncPosition);
            if (person.value) syncPosition();
        });
    </script>
    <style>
        .asset-form-page { max-width: 1320px; margin: 0 auto; }
        .asset-form-hero { padding: 1.5rem 1.75rem; border: 1px solid #e8edf5; border-radius: 1rem; background: linear-gradient(115deg, #eff6ff 0%, #fff 76%); }
        .asset-form-hero-icon { display: inline-flex; width: 58px; height: 58px; flex: 0 0 58px; align-items: center; justify-content: center; border-radius: 16px; background: #fff; color: #3699ff; box-shadow: 0 5px 18px rgba(40, 90, 140, .08); font-size: 1.4rem; }
        .asset-form-steps { display: flex; gap: 1rem; padding: 1.1rem 1.4rem; border: 1px solid #edf0f5; border-radius: .9rem; background: #fff; }
        .asset-form-step { display: flex; align-items: center; gap: .7rem; flex: 1; color: #9aa4b2; }
        .asset-form-step > span { display: inline-flex; width: 32px; height: 32px; flex: 0 0 32px; align-items: center; justify-content: center; border-radius: 50%; background: #f2f4f7; color: #7e8998; font-weight: 700; }
        .asset-form-step strong, .asset-form-step small { display: block; }
        .asset-form-step strong { color: #7c8798; font-size: .9rem; }
        .asset-form-step small { margin-top: 2px; color: #a0a9b6; font-size: .75rem; }
        .asset-form-step.is-complete > span { background: #e8fff3; color: #17c653; }
        .asset-form-step.is-complete strong, .asset-form-step.is-active strong { color: #181c32; }
        .asset-form-step.is-active > span { background: #e8f3ff; color: #3699ff; }
        .asset-form-card { border-radius: .95rem; }
        .asset-section-icon { display: inline-flex; width: 42px; height: 42px; flex: 0 0 42px; align-items: center; justify-content: center; border-radius: 12px; font-size: 1rem; }
        .asset-source-card { overflow: hidden; border-radius: .95rem; background: linear-gradient(120deg, #f5f9ff, #fff 68%); }
        .asset-source-main, .asset-source-stat { height: 100%; padding: 1rem 1.1rem; border: 1px solid #e7edf6; border-radius: .75rem; background: rgba(255,255,255,.86); }
        .asset-source-stat small, .asset-source-main small { display: block; margin-bottom: .4rem; color: #98a2b3; font-size: .72rem; font-weight: 700; letter-spacing: .035em; text-transform: uppercase; }
        .asset-source-stat strong { color: #252f43; font-size: .9rem; }
        .asset-readonly-field { display: flex; align-items: center; gap: .7rem; min-height: 48px; padding: .75rem 1rem; border: 1px solid #e4e6ef; border-radius: .65rem; background: #f8fafc; font-weight: 600; }
        .asset-upload-box { position: relative; display: flex; min-height: 90px; align-items: center; gap: 1rem; padding: 1rem 1.1rem; border: 1px dashed #cbd5e1; border-radius: .75rem; background: #fbfcfe; cursor: pointer; transition: .15s ease; }
        .asset-upload-box:hover { border-color: #3699ff; background: #f5faff; }
        .asset-upload-box input { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
        .asset-upload-icon { display: inline-flex; width: 42px; height: 42px; flex: 0 0 42px; align-items: center; justify-content: center; border-radius: 12px; background: #e8f3ff; color: #3699ff; }
        .asset-upload-box strong, .asset-upload-box small { display: block; }
        .asset-upload-box small { margin-top: .25rem; color: #98a2b3; }
        .asset-form-page .form-control, .asset-form-page .form-select { border-radius: .6rem; }
        .asset-form-actions { padding: 1rem 1.25rem; border: 1px solid #edf0f5; border-radius: .85rem; background: #fff; }
        @media (max-width: 767.98px) {
            .asset-form-hero { padding: 1.15rem; }
            .asset-form-steps { gap: .5rem; padding: .9rem; }
            .asset-form-step { align-items: flex-start; }
            .asset-form-step small { display: none; }
            .asset-form-step strong { font-size: .75rem; }
        }
    </style>
@endsection
