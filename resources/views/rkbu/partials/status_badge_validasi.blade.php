@php
    $statusValidasi = [
        '9cfb1f38-dc3f-436f-8c4a-ec4c4de2fcaf' => 'warning',
        '9cfb1edc-2263-401f-b249-361db4017932' => 'success',
        '9cfb1f45-ca37-4bd3-8dc9-514c6b9f436c' => 'danger',
    ];

    $statusValidasiRka = [
        '9cfb1f93-70fb-4b88-bff9-a3ae6e81ae34' => 'warning',
        '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' => 'success',
        '9cfb1fa1-d0de-4e99-a368-8c218deda960' => 'danger',
    ];
@endphp
<span>
    @if (isset($statusValidasi[$rkbus->id_status_validasi]))
        <span class="badge badge-soft-{{ $statusValidasi[$rkbus->id_status_validasi] }} badge-border">ksp</span>
    @endif

    @if (isset($statusValidasiRka[$rkbus->id_status_validasi_rka]))
        <span class="badge badge-soft-{{ $statusValidasiRka[$rkbus->id_status_validasi_rka] }} badge-border">rka</span>
    @endif
</span>
