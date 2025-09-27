<!-- Validasi KSP -->
<div class="mb-3">
    <span class="f-12">Validasi KSP</span>
    <div class="br-wrapper br-theme-bars-movie">
        <div class="br-widget">
            @php
                $statusKsp = $rkbus->id_status_validasi;
            @endphp

            <a href="#"
                class="{{ in_array($statusKsp, ['9cfb1f38-dc3f-436f-8c4a-ec4c4de2fcaf', '9cfb1edc-2263-401f-b249-361db4017932'])
                    ? 'br-selected'
                    : '' }}"
                data-rating-value="Bad" data-rating-text="Bad"></a>

            <a href="#" class="{{ $statusKsp == '9cfb1edc-2263-401f-b249-361db4017932' ? 'br-selected' : '' }}"
                data-rating-value="Mediocre" data-rating-text="Mediocre"></a>

            <a href="#" class="{{ $statusKsp == '9cfb1edc-2263-401f-b249-361db4017932' ? 'br-selected' : '' }}"
                data-rating-value="Good" data-rating-text="Good"></a>

            <a href="#" class="{{ $statusKsp == '9cfb1edc-2263-401f-b249-361db4017932' ? 'br-selected' : '' }}"
                data-rating-value="Awesome" data-rating-text="Awesome"></a>

            <div class="br-current-rating f-12">
                @if ($statusKsp == '9cfb1f38-dc3f-436f-8c4a-ec4c4de2fcaf')
                    <span class="badge badge-label bg-warning"><i class="mdi mdi-circle-medium"></i>
                        Perlu Validasi KSP</span>
                @elseif ($statusKsp == '9cfb1f45-ca37-4bd3-8dc9-514c6b9f436c')
                    <span class="badge badge-label bg-danger"><i class="mdi mdi-circle-medium"></i>
                        Usulan Ditolak KSP</span>
                @elseif ($statusKsp == '9cfb1edc-2263-401f-b249-361db4017932')
                    <span class="badge badge-label bg-success"><i class="mdi mdi-circle-medium"></i>
                        Sudah Validasi KSP</span>
                @endif
            </div>
        </div>
    </div>
</div>
</br>
<!-- Validasi Pimpinan -->
<div class="mb-3">
    <span class="f-12">Validasi Pimpinan</span>
    <div class="br-wrapper br-theme-bars-movie">
        <div class="br-widget">
            @php
                $statusRka = $rkbus->id_status_validasi_rka;
            @endphp

            <a href="#"
                class="{{ in_array($statusRka, ['9cfb1f93-70fb-4b88-bff9-a3ae6e81ae34', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1'])
                    ? 'br-selected'
                    : '' }}"
                data-rating-value="Bad" data-rating-text="Bad"></a>

            <a href="#" class="{{ $statusRka == '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' ? 'br-selected' : '' }}"
                data-rating-value="Mediocre" data-rating-text="Mediocre"></a>

            <a href="#" class="{{ $statusRka == '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' ? 'br-selected' : '' }}"
                data-rating-value="Good" data-rating-text="Good"></a>

            <a href="#" class="{{ $statusRka == '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' ? 'br-selected' : '' }}"
                data-rating-value="Awesome" data-rating-text="Awesome"></a>

            <div class="br-current-rating f-12">
                @if ($statusRka == '9cfb1f93-70fb-4b88-bff9-a3ae6e81ae34')
                    <span class="badge badge-label bg-warning"><i class="mdi mdi-circle-medium"></i>
                        Perlu Validasi Pimpinan</span>
                @elseif ($statusRka == '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
                    <span class="badge badge-label bg-success"><i class="mdi mdi-circle-medium"></i>
                        Sudah Validasi Pimpinan</span>
                @elseif ($statusRka == '9cfb1fa1-d0de-4e99-a368-8c218deda960')
                    <span class="badge badge-label bg-danger"><i class="mdi mdi-circle-medium"></i>
                        Usulan Ditolak Pimpinan</span>
                @endif
            </div>
        </div>
    </div>
</div>
