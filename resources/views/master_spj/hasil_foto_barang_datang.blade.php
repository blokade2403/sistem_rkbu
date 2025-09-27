<div class="card">
    <div class="card-header">
        <h4 class="sub-title">Foto Barang Datang</h4>
        <div class="card-header-right">
            <ul class="list-unstyled card-option">
                <li><i class="feather icon-maximize full-card"></i></li>
                <li><i class="feather icon-minus minimize-card"></i></li>
            </ul>
        </div>
    </div>
    <div class="card-block">
        <p> Tanggal Update : <code>{{ $master_spj->tgl_barang_datang ?? 'Belum update Foto' }}</code> Verifikasi
            Barang Datang
        </p>
        <div class="row">
            <div class="col-xl-2 col-lg-3 col-sm-3 col-xs-12">
                <a href="{{ asset('libraries\assets\images\light-box\l1.jpg') }}" data-lightbox="example-set">
                    <img src="{{ asset('libraries\assets\images\light-box\sl1.jpg') }}" class="img-fluid m-b-10"
                        alt="">
                </a>
            </div>
            <div class="col-xl-2 col-lg-3 col-sm-3 col-xs-12">
                <a href="{{ asset('libraries\assets\images\light-box\l2.jpg') }}" data-lightbox="example-set"
                    data-title="Lorem ipsum dolor sit amet, consectetur adipiscing lorem impus dolorsit.onsectetur adipiscing.">
                    <img src="{{ asset('libraries\assets\images\light-box\sl2.jpg') }}" class="img-fluid m-b-10"
                        alt="">
                </a>
            </div>
            <div class="col-xl-2 col-lg-3 col-sm-3 col-xs-12">
                <a href="{{ asset('libraries\assets\images\light-box\l5.jpg"') }} data-lightbox="example-set"
                    data-title="Lorem ipsum dolor sit amet, consectetur adipiscing lorem impus dolorsit.onsectetur adipiscing.">
                    <img src="{{ asset('libraries\assets\images\light-box\sl5.jpg') }}" class="img-fluid m-b-10"
                        alt="">
                </a>
            </div>
            <div class="col-xl-2 col-lg-3 col-sm-3 col-xs-12">
                <a href="{{ asset('libraries\assets\images\light-box\l6.jpg') }}" data-lightbox="example-set"
                    data-title="Lorem ipsum dolor sit amet, consectetur adipiscing lorem impus dolorsit.onsectetur adipiscing.">
                    <img src="{{ asset('libraries\assets\images\light-box\sl6.jpg') }}" class="img-fluid m-b-10"
                        alt="">
                </a>
            </div>
        </div>
    </div>
</div>
