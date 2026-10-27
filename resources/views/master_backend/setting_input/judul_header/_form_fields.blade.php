@php
    $record = $judulHeader ?? null;
    $textFields = [
        ['name' => 'header1', 'label' => 'Header 1'],
        ['name' => 'header2', 'label' => 'Header 2'],
        ['name' => 'header3', 'label' => 'Header 3'],
        ['name' => 'header4', 'label' => 'Header 4'],
        ['name' => 'header5', 'label' => 'Header 5'],
        ['name' => 'header6', 'label' => 'Header 6'],
    ];
    $imageFields = [
        ['name' => 'gambar1', 'label' => 'Gambar 1'],
        ['name' => 'gambar2', 'label' => 'Gambar 2'],
        ['name' => 'header7', 'label' => 'Gambar 3'],
        ['name' => 'gambar3', 'label' => 'Gambar 4'],
        ['name' => 'gambar4', 'label' => 'Gambar 5'],
    ];
@endphp

<div class="jh-modal-section">
    <div class="jh-section-heading"><i class="fas fa-hospital"></i>Informasi Instansi</div>
    <div class="row g-4">
        <div class="col-md-6">
            <label for="{{ $formPrefix }}_nama_rs" class="form-label required">Nama Instansi</label>
            <input id="{{ $formPrefix }}_nama_rs" type="text" name="nama_rs" class="form-control @error('nama_rs') is-invalid @enderror" value="{{ old('nama_rs', $record?->nama_rs) }}" placeholder="Contoh: RSUD ..." required>
            @error('nama_rs')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="{{ $formPrefix }}_email_rs" class="form-label required">Email</label>
            <input id="{{ $formPrefix }}_email_rs" type="email" name="email_rs" class="form-control @error('email_rs') is-invalid @enderror" value="{{ old('email_rs', $record?->email_rs) }}" placeholder="nama@instansi.go.id" required>
            @error('email_rs')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label for="{{ $formPrefix }}_tlp_rs" class="form-label required">Nomor Telepon</label>
            <input id="{{ $formPrefix }}_tlp_rs" type="text" name="tlp_rs" class="form-control @error('tlp_rs') is-invalid @enderror" value="{{ old('tlp_rs', $record?->tlp_rs) }}" placeholder="Nomor telepon instansi" required>
            @error('tlp_rs')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label for="{{ $formPrefix }}_wilayah" class="form-label required">Wilayah</label>
            <input id="{{ $formPrefix }}_wilayah" type="text" name="wilayah" class="form-control @error('wilayah') is-invalid @enderror" value="{{ old('wilayah', $record?->wilayah) }}" placeholder="Kabupaten/Kota" required>
            @error('wilayah')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label for="{{ $formPrefix }}_kode_pos" class="form-label">Kode Pos</label>
            <input id="{{ $formPrefix }}_kode_pos" type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $record?->kode_pos) }}" placeholder="Kode pos">
        </div>
        <div class="col-12">
            <label for="{{ $formPrefix }}_alamat_rs" class="form-label required">Alamat</label>
            <textarea id="{{ $formPrefix }}_alamat_rs" name="alamat_rs" class="form-control @error('alamat_rs') is-invalid @enderror" rows="3" placeholder="Alamat lengkap instansi" required>{{ old('alamat_rs', $record?->alamat_rs) }}</textarea>
            @error('alamat_rs')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="jh-modal-section">
    <div class="jh-section-heading"><i class="fas fa-align-left"></i>Teks Kop Dokumen</div>
    <div class="row g-4">
        @foreach ($textFields as $field)
            <div class="col-md-6">
                <label for="{{ $formPrefix }}_{{ $field['name'] }}" class="form-label">{{ $field['label'] }}</label>
                <input id="{{ $formPrefix }}_{{ $field['name'] }}" type="text" name="{{ $field['name'] }}" class="form-control" value="{{ old($field['name'], $record?->{$field['name']}) }}" placeholder="Masukkan {{ strtolower($field['label']) }}">
            </div>
        @endforeach
    </div>
</div>

<div class="jh-modal-section">
    <div class="jh-section-heading"><i class="fas fa-image"></i>Gambar Kop Dokumen</div>
    <div class="row g-3">
        @foreach ($imageFields as $field)
            <div class="col-md-6 col-xl-4">
                <div class="jh-upload-box">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img id="{{ $formPrefix }}_preview_{{ $field['name'] }}" src="{{ $record?->{$field['name']} ? asset('storage/judul_header/' . basename($record->{$field['name']})) : asset('assets/images/users/user-dummy-img.jpg') }}" alt="Preview {{ $field['label'] }}">
                        <div>
                            <div class="fw-bold text-gray-800">{{ $field['label'] }}</div>
                            <div class="text-muted fs-8">JPG, JPEG, atau PNG · Maks. 2 MB</div>
                        </div>
                    </div>
                    <input id="{{ $formPrefix }}_{{ $field['name'] }}" type="file" name="{{ $field['name'] }}" class="form-control form-control-sm @error($field['name']) is-invalid @enderror" accept="image/png,image/jpeg">
                    @error($field['name'])<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        @endforeach
    </div>
</div>
