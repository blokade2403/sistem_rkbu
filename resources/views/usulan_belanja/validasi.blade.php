
@extends('layouts.main')

@section('title', 'Validasi Usulan Belanja')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Validasi Usulan Belanja</h3>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('usulan-belanja.validasi.index') }}" class="row g-3 mb-6">
            <div class="col-md-3">
                <label for="filter-jenis-rkbu" class="form-label">Jenis Kategori RKBU</label>
                <select id="filter-jenis-rkbu" name="jenis_kategori_rkbu_id" class="form-select" data-control="select2">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisKategoriRkbus as $jenis)
                        <option value="{{ $jenis->id_jenis_kategori_rkbu }}" @selected(request('jenis_kategori_rkbu_id') === $jenis->id_jenis_kategori_rkbu)>{{ $jenis->nama_jenis_kategori_rkbu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-subkategori-rkbu" class="form-label">Subkategori RKBU</label>
                <select id="filter-subkategori-rkbu" name="sub_kategori_rkbu_id" class="form-select" data-control="select2">
                    <option value="">Semua Subkategori</option>
                    @foreach($subKategoriRkbus as $subkategori)
                        <option value="{{ $subkategori->id_sub_kategori_rkbu }}" data-jenis="{{ $subkategori->jenis_kategori_rkbu_id }}" @selected(request('sub_kategori_rkbu_id') === $subkategori->id_sub_kategori_rkbu)>{{ $subkategori->nama_sub_kategori_rkbu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-pengusul" class="form-label">Pengusul</label>
                <select id="filter-pengusul" name="pengusul_id" class="form-select" data-control="select2">
                    <option value="">Semua Pengusul</option>
                    @foreach($pengusuls as $pengusul)
                        <option value="{{ $pengusul->user_id }}" @selected(request('pengusul_id') === $pengusul->user_id)>{{ $pengusul->nama_pengusul }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('usulan-belanja.validasi.index') }}" class="btn btn-light">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-row-dashed align-middle gy-4">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nomor Usulan</th>
                        <th>Subkategori RKBU</th>
                        <th>Total Usulan</th>
                        <th>Pengusul</th>
                        <th>Tahun Anggaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($usulans as $usulan)
                        <tr>
                            <td>{{ $usulans->firstItem() + $loop->index }}</td>
                            <td>{{ $usulan->no_usulan_belanja }}</td>
                            <td>{{ $usulan->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format((float) $usulan->total_nominal, 0, ',', '.') }}</td>
                            <td>{{ $usulan->nama_pengusul }}</td>
                            <td>
                                {{ $usulan->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}
                            </td>
                            <td>
                                <span class="badge badge-light-warning">
                                    {{ strtoupper($usulan->status_usulan) }}
                                </span>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-light-primary"
                                   href="{{ route(
                                       'usulan-belanja.show',
                                       $usulan->id
                                   ) }}">
                                    Periksa
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8">
                                Tidak ada usulan yang menunggu validasi Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $usulans->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        const jenis = $('#filter-jenis-rkbu');
        const subkategori = $('#filter-subkategori-rkbu');
        const selectedSubkategori = @json(request('sub_kategori_rkbu_id'));

        function filterSubkategori() {
            const jenisId = jenis.val();
            subkategori.find('option').each(function (index) {
                if (index === 0) return;
                const matches = !jenisId || String($(this).data('jenis')) === String(jenisId);
                $(this).prop('disabled', !matches).prop('hidden', !matches);
            });
            if (subkategori.find(':selected').prop('disabled')) subkategori.val('').trigger('change.select2');
        }

        jenis.on('change', filterSubkategori);
        filterSubkategori();
        if (selectedSubkategori && !subkategori.find('option:selected').prop('disabled')) {
            subkategori.val(selectedSubkategori).trigger('change.select2');
        }
    });
</script>
@endpush
