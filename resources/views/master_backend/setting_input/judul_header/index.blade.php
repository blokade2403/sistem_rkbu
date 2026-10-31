@extends('layouts.main')

@section('content')
    <style>
        .jh-hero { position: relative; overflow: hidden; border: 0; border-radius: 1rem; background: linear-gradient(115deg, #f1f7ff 0%, #fff 72%); }
        .jh-hero::after { content: ''; position: absolute; width: 210px; height: 210px; right: -55px; top: -90px; border-radius: 50%; background: rgba(54, 153, 255, .08); }
        .jh-icon { width: 52px; height: 52px; display: inline-flex; align-items: center; justify-content: center; border-radius: .9rem; background: #e8f3ff; color: #3699ff; font-size: 1.35rem; }
        .jh-table th { white-space: nowrap; color: #78849a; font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; }
        .jh-table td { vertical-align: middle; }
        .jh-preview { width: 54px; height: 54px; object-fit: cover; border: 1px solid #e4e9f0; border-radius: .7rem; background: #f5f8fa; }
        .jh-modal-section { padding: 1rem; border: 1px solid #edf0f5; border-radius: .85rem; background: #fff; }
        .jh-modal-section + .jh-modal-section { margin-top: 1rem; }
        .jh-section-heading { display: flex; align-items: center; gap: .65rem; margin-bottom: 1rem; color: #34445b; font-weight: 700; }
        .jh-section-heading i { color: #3699ff; }
        .jh-upload-box { height: 100%; padding: .85rem; border: 1px dashed #cbd5e1; border-radius: .75rem; background: #fafcff; }
        .jh-upload-box img { width: 64px; height: 64px; object-fit: cover; border-radius: .7rem; background: #eef3f8; }
        #judulHeaderCreateModal .modal-dialog, #judulHeaderEditModal .modal-dialog { width: min(1140px, calc(100% - 1rem)); max-width: none; height: calc(100vh - 2rem); height: calc(100dvh - 2rem); margin: 1rem auto; }
        #judulHeaderCreateModal .modal-content, #judulHeaderEditModal .modal-content { display: flex; flex-direction: column; height: 100%; max-height: 100%; min-height: 0; overflow: hidden; }
        #judulHeaderCreateModal form, #judulHeaderEditModal form { display: flex; flex: 1 1 auto; flex-direction: column; min-height: 0; overflow: hidden; }
        #judulHeaderCreateModal .modal-header, #judulHeaderEditModal .modal-header, #judulHeaderCreateModal .modal-footer, #judulHeaderEditModal .modal-footer { flex: 0 0 auto; }
        #judulHeaderCreateModal .modal-body, #judulHeaderEditModal .modal-body { flex: 1 1 auto; min-height: 0; overflow-x: hidden; overflow-y: auto; overscroll-behavior: contain; -webkit-overflow-scrolling: touch; }
        @media (max-width: 575.98px) {
            .jh-hero .card-body { padding: 1.15rem; }
            .jh-modal-section { padding: .8rem; }
            .jh-modal-actions { display: grid !important; grid-template-columns: 1fr 1fr; width: 100%; }
            .jh-modal-actions .btn { width: 100%; }
            #judulHeaderCreateModal .modal-dialog, #judulHeaderEditModal .modal-dialog { width: calc(100% - .5rem); height: calc(100vh - .5rem); height: calc(100dvh - .5rem); margin: .25rem auto; }
        }
    </style>

    <div class="card jh-hero mb-6 shadow-sm">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-4 p-6 position-relative" style="z-index:1;">
            <div class="d-flex align-items-center gap-4">
                <span class="jh-icon"><i class="fas fa-heading"></i></span>
                <div>
                    <h1 class="fs-2 fw-bold text-gray-900 mb-1">Pengaturan Judul Header</h1>
                    <div class="text-muted">Kelola identitas instansi, informasi surat, dan gambar kop dokumen.</div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#judulHeaderCreateModal">
                <i class="fas fa-plus me-2"></i>Tambah Judul Header
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-6">
            <div class="card-title d-flex flex-column">
                <h2 class="fw-bold text-gray-900 mb-1">Data Header Dokumen</h2>
                <span class="text-muted fs-7">{{ $judul_headers->count() }} data tersimpan</span>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-row-bordered table-hover align-middle gs-5 gy-4 jh-table mb-0">
                    <thead>
                        <tr>
                            <th>Instansi</th>
                            <th>Kontak</th>
                            <th>Wilayah</th>
                            <th>Header Surat</th>
                            <th>Gambar Kop</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($judul_headers as $judulHeader)
                            @php
                                $imageFields = [
                                    'gambar1' => 'Logo 1',
                                    'gambar2' => 'Logo 2',
                                    'header7' => 'Gambar 3',
                                    'gambar3' => 'Gambar 4',
                                    'gambar4' => 'Gambar 5',
                                ];
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="symbol symbol-45px me-3"><span class="symbol-label bg-light-primary text-primary fw-bold">{{ mb_strtoupper(mb_substr($judulHeader->nama_rs ?: 'I', 0, 1)) }}</span></span>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-gray-900">{{ $judulHeader->nama_rs }}</span>
                                            <span class="text-muted fs-7 text-truncate" style="max-width: 280px;">{{ $judulHeader->alamat_rs }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-gray-800">{{ $judulHeader->tlp_rs }}</div>
                                    <div class="text-muted fs-7">{{ $judulHeader->email_rs }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-gray-800">{{ $judulHeader->wilayah }}</div>
                                    <div class="text-muted fs-7">Kode pos {{ $judulHeader->kode_pos ?: '-' }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-gray-800">{{ $judulHeader->header1 ?: 'Belum diatur' }}</div>
                                    <div class="text-muted fs-7">{{ collect([$judulHeader->header2, $judulHeader->header3, $judulHeader->header4, $judulHeader->header5, $judulHeader->header6])->filter()->take(2)->implode(' · ') ?: 'Header tambahan belum diatur' }}</div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($imageFields as $field => $label)
                                            @if ($judulHeader->{$field})
                                                <img class="jh-preview" src="{{ asset('storage/judul_header/' . basename($judulHeader->{$field})) }}" alt="{{ $label }}" title="{{ $label }}">
                                            @endif
                                        @endforeach
                                        @if (!$judulHeader->gambar1 && !$judulHeader->gambar2 && !$judulHeader->header7 && !$judulHeader->gambar3 && !$judulHeader->gambar4)
                                            <span class="text-muted fs-7">Belum ada gambar</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-light-primary btn-edit-judul-header"
                                            data-record="{{ $judulHeader->toJson() }}"
                                            data-update-url="{{ route('judul_headers.update', $judulHeader->id_judul_header) }}">
                                            <i class="fas fa-pen me-1"></i>Edit
                                        </button>
                                        <form action="{{ route('judul_headers.destroy', $judulHeader->id_judul_header) }}" method="POST" class="delete-form mb-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger" aria-label="Hapus {{ $judulHeader->nama_rs }}"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-10">
                                <div class="jh-icon mx-auto mb-3"><i class="fas fa-file-signature"></i></div>
                                <div class="fw-bold text-gray-800">Belum ada data header</div>
                                <div class="text-muted mb-4">Tambahkan identitas instansi untuk digunakan pada dokumen.</div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#judulHeaderCreateModal"><i class="fas fa-plus me-2"></i>Tambah Judul Header</button>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create modal --}}
    <div class="modal fade" id="judulHeaderCreateModal" tabindex="-1" aria-labelledby="judulHeaderCreateTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <div><h3 class="modal-title fs-3 fw-bold" id="judulHeaderCreateTitle">Tambah Judul Header</h3><div class="text-muted fs-7 mt-1">Lengkapi informasi instansi dan elemen kop surat.</div></div>
                    <button type="button" class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal" aria-label="Tutup"><i class="ki-duotone ki-cross fs-2"></i></button>
                </div>
                <form method="POST" action="{{ route('judul_headers.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="form_mode" value="create">
                    <div class="modal-body">
                        @include('master_backend.setting_input.judul_header._form_fields', ['formPrefix' => 'create', 'judulHeader' => null])
                    </div>
                    <div class="modal-footer jh-modal-actions">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-check me-2"></i>Simpan Judul Header</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit modal --}}
    <div class="modal fade" id="judulHeaderEditModal" tabindex="-1" aria-labelledby="judulHeaderEditTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <div><h3 class="modal-title fs-3 fw-bold" id="judulHeaderEditTitle">Ubah Judul Header</h3><div class="text-muted fs-7 mt-1">Perbarui informasi instansi atau gambar kop surat.</div></div>
                    <button type="button" class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal" aria-label="Tutup"><i class="ki-duotone ki-cross fs-2"></i></button>
                </div>
                <form id="judulHeaderEditForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_mode" value="edit">
                    <input type="hidden" name="judul_header_id" id="edit_judul_header_id">
                    <div class="modal-body">
                        @include('master_backend.setting_input.judul_header._form_fields', ['formPrefix' => 'edit', 'judulHeader' => null])
                    </div>
                    <div class="modal-footer jh-modal-actions">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const editModalElement = document.getElementById('judulHeaderEditModal');
            const editModal = bootstrap.Modal.getOrCreateInstance(editModalElement);
            const editForm = document.getElementById('judulHeaderEditForm');
            const fields = ['nama_rs', 'alamat_rs', 'tlp_rs', 'email_rs', 'wilayah', 'kode_pos', 'header1', 'header2', 'header3', 'header4', 'header5', 'header6'];
            const images = { gambar1: 'gambar1', gambar2: 'gambar2', header7: 'header7', gambar3: 'gambar3', gambar4: 'gambar4' };
            const defaultImage = @json(asset('assets/images/users/user-dummy-img.jpg'));
            const imageUrl = function (path) {
                return path ? @json(asset('storage/judul_header')) + '/' + String(path).split('/').pop() : defaultImage;
            };
            const populateEdit = function (record, updateUrl, oldValues) {
                editForm.action = updateUrl;
                document.getElementById('edit_judul_header_id').value = record.id_judul_header || '';
                fields.forEach(function (field) {
                    const input = editForm.querySelector('[name="' + field + '"]');
                    if (input) input.value = oldValues && Object.prototype.hasOwnProperty.call(oldValues, field) ? oldValues[field] : (record[field] || '');
                });
                Object.keys(images).forEach(function (field) {
                    const preview = document.getElementById('edit_preview_' + field);
                    if (preview) preview.src = imageUrl(record[field]);
                });
                editModal.show();
            };
            document.querySelectorAll('.btn-edit-judul-header').forEach(function (button) {
                button.addEventListener('click', function () {
                    populateEdit(JSON.parse(button.dataset.record), button.dataset.updateUrl, null);
                });
            });
            Object.keys(images).forEach(function (field) {
                ['create', 'edit'].forEach(function (prefix) {
                    const input = document.getElementById(prefix + '_' + field);
                    const preview = document.getElementById(prefix + '_preview_' + field);
                    if (!input || !preview) return;
                    input.addEventListener('change', function () {
                        if (input.files && input.files[0]) preview.src = URL.createObjectURL(input.files[0]);
                    });
                });
            });
            @if ($errors->any() && old('form_mode') === 'create')
                bootstrap.Modal.getOrCreateInstance(document.getElementById('judulHeaderCreateModal')).show();
            @elseif ($errors->any() && old('form_mode') === 'edit' && old('judul_header_id'))
                const oldRecord = @json($judul_headers->firstWhere('id_judul_header', old('judul_header_id')));
                if (oldRecord) populateEdit(oldRecord, @json(route('judul_headers.update', old('judul_header_id'))), @json(old()));
            @elseif (request('modal') === 'create')
                bootstrap.Modal.getOrCreateInstance(document.getElementById('judulHeaderCreateModal')).show();
            @elseif (request('edit_id'))
                const requestedRecord = @json($judul_headers->firstWhere('id_judul_header', request('edit_id')));
                if (requestedRecord) populateEdit(requestedRecord, @json(route('judul_headers.update', request('edit_id'))), null);
            @endif
        })();
    </script>
@endpush
