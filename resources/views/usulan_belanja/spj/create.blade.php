@extends('layouts.main')

@section('title', 'Buat SPJ dari Usulan Belanja')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bagi Item Usulan ke dalam SPJ</h3>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="alert alert-info">
            Usulan <strong>{{ $usulanBelanja->no_usulan_belanja }}</strong> memiliki {{ $items->count() }} item dengan sisa volume.
            Anda dapat mengalokasikan sebagian volume sekarang dan melanjutkan sisanya nanti. Volume item yang sama bisa dibagi ke beberapa SPJ atau penyedia.
        </div>

        @if($items->isEmpty())
            <div class="alert alert-success">Semua item usulan ini sudah dimasukkan ke SPJ.</div>
        @else
            @php($formGroups = old('groups', [['items' => [], 'perusahaan_id' => '']]))
            <form method="POST" action="{{ route('usulan-belanja.spj.store', $usulanBelanja->id) }}" id="spj-group-form">
                @csrf
                <div id="spj-groups" class="d-flex flex-column gap-5">
                    @foreach($formGroups as $groupIndex => $group)
                        <div class="card card-bordered spj-group">
                            <div class="card-header min-h-50px">
                                <h4 class="card-title fs-5">Kelompok SPJ {{ $loop->iteration }}</h4>
                                @if(!$loop->first)
                                    <button type="button" class="btn btn-sm btn-light-danger remove-spj-group">Hapus Kelompok</button>
                                @endif
                            </div>
                            <div class="card-body py-4">
                                <div class="mb-4">
                                    <label class="form-label">Penyedia untuk SPJ ini</label>
                                    <select class="form-select" name="groups[{{ $groupIndex }}][perusahaan_id]" required>
                                        <option value="">Pilih penyedia</option>
                                        @foreach($perusahaans as $perusahaan)
                                            <option value="{{ $perusahaan->id_perusahaan }}" @selected(old('groups.'.$groupIndex.'.perusahaan_id', $group['perusahaan_id'] ?? '') == $perusahaan->id_perusahaan)>{{ $perusahaan->nama_perusahaan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row g-3">
                                    @foreach($items as $item)
                                        <div class="col-lg-6">
                                            <label class="border rounded p-3 d-flex gap-3 align-items-start h-100 spj-item-option">
                                                <input class="form-control form-control-sm spj-item-volume" type="number" min="0" max="{{ $item->spj_remaining_volume }}" step="0.001"
                                                       name="groups[{{ $groupIndex }}][items][{{ $item->link_id }}]"
                                                       value="{{ old('groups.'.$groupIndex.'.items.'.$item->link_id, 0) }}"
                                                       data-item-id="{{ $item->link_id }}" data-max-volume="{{ $item->spj_remaining_volume }}">
                                                <span>
                                                    <span class="fw-bold d-block">{{ $item->nama_barang }}</span>
                                                    <span class="text-muted fs-7 d-block">{{ $item->spesifikasi ?: '-' }}</span>
                                                    <span class="text-muted fs-7 d-block">Sisa belum dialokasikan: <strong class="spj-remaining-display" data-item-id="{{ $item->link_id }}">{{ $item->spj_remaining_volume }}</strong> {{ $item->satuan_1 }}</span>
                                                    <span class="text-muted fs-7 d-block">Alokasi kelompok ini ({{ $item->satuan_1 }}):</span>
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between mt-5">
                    <button type="button" id="add-spj-group" class="btn btn-light-primary">Tambah Kelompok SPJ</button>
                    <div class="d-flex gap-2">
                        <a href="{{ route('usulan-belanja.spj.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Buat SPJ</button>
                    </div>
                </div>
            </form>

            <template id="spj-group-template">
                <div class="card card-bordered spj-group">
                    <div class="card-header min-h-50px">
                        <h4 class="card-title fs-5">Kelompok SPJ __NUMBER__</h4>
                        <button type="button" class="btn btn-sm btn-light-danger remove-spj-group">Hapus Kelompok</button>
                    </div>
                    <div class="card-body py-4">
                        <div class="mb-4">
                            <label class="form-label">Penyedia untuk SPJ ini</label>
                            <select class="form-select" name="groups[__INDEX__][perusahaan_id]" required>
                                <option value="">Pilih penyedia</option>
                                @foreach($perusahaans as $perusahaan)
                                    <option value="{{ $perusahaan->id_perusahaan }}">{{ $perusahaan->nama_perusahaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3">
                            @foreach($items as $item)
                                <div class="col-lg-6">
                                    <label class="border rounded p-3 d-flex gap-3 align-items-start h-100 spj-item-option">
                                        <input class="form-control form-control-sm spj-item-volume" type="number" min="0" max="{{ $item->spj_remaining_volume }}" step="0.001"
                                               name="groups[__INDEX__][items][{{ $item->link_id }}]"
                                               value="0" data-item-id="{{ $item->link_id }}" data-max-volume="{{ $item->spj_remaining_volume }}">
                                        <span>
                                            <span class="fw-bold d-block">{{ $item->nama_barang }}</span>
                                            <span class="text-muted fs-7 d-block">{{ $item->spesifikasi ?: '-' }}</span>
                                            <span class="text-muted fs-7 d-block">Sisa belum dialokasikan: <strong class="spj-remaining-display" data-item-id="{{ $item->link_id }}">{{ $item->spj_remaining_volume }}</strong> {{ $item->satuan_1 }}</span>
                                            <span class="text-muted fs-7 d-block">Alokasi kelompok ini ({{ $item->satuan_1 }}):</span>
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </template>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const groups = document.getElementById('spj-groups');
        if (!groups) return;
        const template = document.getElementById('spj-group-template');
        let nextIndex = Math.max(-1, ...Array.from(groups.querySelectorAll('.spj-item-volume')).map(input => {
            const match = input.name.match(/^groups\[(\d+)\]/);
            return match ? Number(match[1]) : -1;
        })) + 1;

        function refreshOptions() {
            const remainingByItem = new Map();
            groups.querySelectorAll('.spj-item-volume').forEach(input => {
                const allocatedElsewhere = Array.from(groups.querySelectorAll('.spj-item-volume'))
                    .filter(other => other.dataset.itemId === input.dataset.itemId && other !== input)
                    .reduce((sum, other) => sum + Number(other.value || 0), 0);
                input.max = Math.max(0, Number(input.dataset.maxVolume) - allocatedElsewhere).toFixed(3);
                remainingByItem.set(input.dataset.itemId, Math.max(0, Number(input.dataset.maxVolume) - allocatedElsewhere - Number(input.value || 0)));
            });
            groups.querySelectorAll('.spj-remaining-display').forEach(display => {
                const remaining = remainingByItem.get(display.dataset.itemId);
                if (remaining !== undefined) display.textContent = remaining.toFixed(3).replace(/\.?0+$/, '');
            });
            groups.querySelectorAll('.spj-group').forEach((group, index) => {
                const title = group.querySelector('.card-title');
                title.textContent = 'Kelompok SPJ ' + (index + 1);
            });
        }

        document.getElementById('add-spj-group').addEventListener('click', function () {
            const index = nextIndex++;
            const html = template.innerHTML
                .replaceAll('__INDEX__', String(index))
                .replaceAll('__NUMBER__', String(groups.querySelectorAll('.spj-group').length + 1));
            groups.insertAdjacentHTML('beforeend', html);
            refreshOptions();
        });

        groups.addEventListener('change', function (event) {
            if (event.target.matches('.spj-item-volume')) refreshOptions();
        });
        groups.addEventListener('click', function (event) {
            const remove = event.target.closest('.remove-spj-group');
            if (!remove) return;
            remove.closest('.spj-group').remove();
            refreshOptions();
        });
        document.getElementById('spj-group-form').addEventListener('submit', function (event) {
            const allocations = Array.from(groups.querySelectorAll('.spj-item-volume'));
            const itemIds = new Set(allocations.map(input => input.dataset.itemId));
            const excessive = Array.from(itemIds).some(itemId => {
                const matching = allocations.filter(input => input.dataset.itemId === itemId);
                const total = matching.reduce((sum, input) => sum + Number(input.value || 0), 0);
                return total - Number(matching[0].dataset.maxVolume) > 0.001;
            });
            const groupWithoutItems = Array.from(groups.querySelectorAll('.spj-group')).some(group =>
                !Array.from(group.querySelectorAll('.spj-item-volume')).some(input => Number(input.value || 0) > 0));
            if (excessive || groupWithoutItems) {
                event.preventDefault();
                alert('Volume yang dialokasikan tidak boleh melebihi sisa volume item. Setiap kelompok SPJ harus memiliki volume item lebih dari 0.');
            }
        });
        refreshOptions();
    });
</script>
@endpush
