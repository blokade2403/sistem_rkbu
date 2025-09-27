<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog" style="z-index: 1050; display: none;"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="login-card card-block login-card-modal">
            <form class="md-float-material" method="POST" action="{{ route($routePrefix . '.add') }}">
                @csrf
                <div class="text-center">
                    <img src="libraries\assets\images\auth\logo.png" alt="logo.png">
                </div>
                <div class="card m-t-15">
                    <div class="auth-box card-block">
                        <div class="row m-b-20">
                            <div class="col-md-12 confirm">
                                <h3 class="text-center txt-primary"><i
                                        class="icofont icofont-check-circled text-primary"></i> Informasi</h3>
                            </div>
                        </div>
                        <p class="text-inverse text-left m-t-15 f-16"><b>Dear {{ session('nama_lengkap') }}, <br> Pilih
                                Sub Kategori </b></p>
                        <p class="text-inverse text-left m-b-0">Pilihan Sub Kategori Usulan Belanja ini, berdasarkan Sub
                            Kategori yang sudah diusulkan dan tervalidasi pimpinan!</p></br>
                        <p class="text-inverse text-left m-b-30">Terima Kasih atas perhatiannya.</p>
                        <div class="row m-t-15">
                            <div class="col-md-12">
                                <select name="id_usulan_barang" id="subKategoriSelect2"
                                    class="form-control js-example-basic-single">
                                    <option value="" disable>-- Pilih Usulan Belanja --</option>
                                    @foreach ($usulan_barangs as $sub_kategori)
                                        <option value="{{ $sub_kategori->id_usulan_barang }}">
                                            {{ $sub_kategori->no_usulan_barang }}
                                            [{{ $sub_kategori->kode_sub_kategori_rkbu }}.{{ $sub_kategori->sub_kategori_rkbu->nama_sub_kategori_rkbu }}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row m-t-15">
                            <div class="col-md-12">
                                <input type="hidden" name="id_user" value="{{ session('id_user') }}">
                                <input type="hidden" name="status_spj" value="Proses SPJ">
                                <button type="submit"
                                    class="btn btn-primary btn-md btn-block waves-effect text-center">Confirm</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-inverse text-left m-b-0 m-t-10">Terima Kasih atas perhatiannya.</p>
                            </div>
                        </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
