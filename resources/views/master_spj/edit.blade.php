<x-layouts.main class="main">
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-header start -->
                <x-partials.breadcrumb>
                </x-partials.breadcrumb>
                <!-- Page-header end -->
                <!-- Page-body start -->
                <!-- Page body start -->
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            @include('master_spj.detail_komponen_edit', ['routePrefix' => 'master_spjs'])
                            @include('master_spj.ceklis_verifikasi', ['routePrefix' => 'master_spjs'])
                            @include('master_spj.form_pesanan_barang_admin', [
                                'routePrefix' => 'master_spjs',
                            ])
                            @include('master_spj.form_upload_gambar_pendukung_ppk', [
                                'routePrefix' => 'master_spjs',
                            ])
                            @include('master_spj.hasil_foto_barang_datang', [
                                'routePrefix' => 'master_spjs',
                            ])
                            @include('master_spj.form_berita_acara_serah_terima', [
                                'routePrefix' => 'master_spjs',
                            ])


                        </div>
                    </div>
                </div>
                <!-- Page body end -->
            </div>
        </div>
    </div>

</x-layouts.main>
