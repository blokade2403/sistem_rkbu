@extends('layouts.main')

@section('content')
 <div class="card mb-5 mb-xxl-12">
        <div class="card-body pt-9 pb-0">
            @include('rkbu.partials.headermenu')
            {{-- @include('rkbu.rkbu_barjas.create') --}}
        </div>
    </div>
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">{{ $config['title'] }}</h3>
            <div class="card-toolbar">
                <span id="module-selected-count" class="text-muted me-3">0 dipilih</span>
                <button type="button" id="module-bulk-submit" class="btn btn-sm btn-light-primary me-3"
                    data-url="{{ route('rkbu.' . $config['route'] . '.bulk-submit') }}" disabled>
                    <i class="fas fa-paper-plane me-1"></i> Ajukan Terpilih
                </button>
                <a href="{{ route('rkbu.' . $config['route'] . '.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Data
                </a>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="GET" action="{{ route('rkbu.' . $config['route'] . '.index') }}" class="row g-3 align-items-end mb-5">
                <div class="col-md-5">
                    <label for="module-subcategory-filter" class="form-label">Subkategori RKBU</label>
                    <select id="module-subcategory-filter" name="sub_kategori_rkbu_id" class="form-select"
                        data-control="select2" data-placeholder="Semua Subkategori RKBU">
                        <option value="">Semua Subkategori RKBU</option>
                        @foreach ($subcategories as $subcategory)
                            <option value="{{ $subcategory->id_sub_kategori_rkbu }}"
                                @selected($selectedSubcategory === $subcategory->id_sub_kategori_rkbu)>
                                {{ $subcategory->kode_sub_kategori_rkbu }} - {{ $subcategory->nama_sub_kategori_rkbu }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <a href="{{ route('rkbu.' . $config['route'] . '.index') }}" class="btn btn-light">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="module-select-all"
                                    aria-label="Pilih semua RKBU pada halaman">
                            </th>
                            <th>Nomor RKBU</th>
                            @foreach ($config['columns'] as $label)
                                <th>{{ $label }}</th>
                            @endforeach
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td>
                                    @if ($record->rkbu?->created_by === auth()->id() &&
                                            in_array($record->rkbu?->status, ['draft', 'revision', 'rejected'], true))
                                        <input type="checkbox" class="form-check-input module-row-select"
                                            value="{{ $record->id }}" aria-label="Pilih RKBU">
                                    @endif
                                </td>
                                <td>{{ $record->rkbu?->nomor_rkbu ?? '-' }}</td>
                                @foreach ($config['columns'] as $field => $label)
                                    <td>
                                        @if (str_contains($field, 'anggaran') || $field === 'harga_satuan')
                                            Rp {{ number_format((float) $record->{$field}, 2, ',', '.') }}
                                        @else
                                            {{ $record->{$field} ?? '-' }}
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <span class="badge badge-light-primary">
                                        {{ ucfirst($record->rkbu?->status ?? '-') }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('rkbu.' . $config['route'] . '.show', $record->id) }}"
                                        class="btn btn-sm btn-light-info">Detail</a>
                                    @if ($record->rkbu?->created_by === auth()->id() &&
                                            in_array($record->rkbu?->status, ['draft', 'revision', 'rejected'], true))
                                        <a href="{{ route('rkbu.' . $config['route'] . '.edit', $record->id) }}"
                                            class="btn btn-sm btn-light-primary">Edit</a>
                                        <form method="POST"
                                            action="{{ route('rkbu.' . $config['route'] . '.submit', $record->id) }}"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light-success">
                                                Ajukan
                                            </button>
                                        </form>
                                        <form method="POST"
                                            action="{{ route('rkbu.' . $config['route'] . '.destroy', $record->id) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Hapus data RKBU ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($config['columns']) + 4 }}" class="text-center text-muted py-8">
                                    Belum ada data {{ $config['title'] }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $records->links() }}
        </div>
    </div>

    <script>
        (() => {
            const selectAll = document.getElementById('module-select-all');
            const submitButton = document.getElementById('module-bulk-submit');
            const selectedCount = document.getElementById('module-selected-count');
            const checkboxes = () => Array.from(document.querySelectorAll('.module-row-select'));

            const refreshSelection = () => {
                const selected = checkboxes().filter((checkbox) => checkbox.checked);
                selectedCount.textContent = `${selected.length} dipilih`;
                submitButton.disabled = selected.length === 0;
                selectAll.checked = checkboxes().length > 0 && selected.length === checkboxes().length;
                selectAll.indeterminate = selected.length > 0 && selected.length < checkboxes().length;
            };

            selectAll.addEventListener('change', () => {
                checkboxes().forEach((checkbox) => {
                    checkbox.checked = selectAll.checked;
                });
                refreshSelection();
            });

            checkboxes().forEach((checkbox) => checkbox.addEventListener('change', refreshSelection));

            submitButton.addEventListener('click', async () => {
                const ids = checkboxes().filter((checkbox) => checkbox.checked).map((checkbox) => checkbox.value);
                if (ids.length === 0 || !window.confirm(`Ajukan ${ids.length} RKBU terpilih?`)) {
                    return;
                }

                submitButton.disabled = true;
                try {
                    const response = await fetch(submitButton.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ ids }),
                    });
                    const result = await response.json();
                    window.alert(result.message || 'Pengajuan selesai.');
                    if (response.ok && result.success) {
                        window.location.reload();
                    }
                } catch (error) {
                    window.alert('Pengajuan gagal dikirim. Silakan coba kembali.');
                } finally {
                    refreshSelection();
                }
            });

            refreshSelection();
        })();
    </script>
@endsection
