@if ($key->status_spj == 'Proses SPJ')
    <div class="br-wrapper br-theme-bars-movie">
        <span class="f-12">Status Proses SPJ</span>
        <div class="br-widget">
            <a href="#" data-rating-value="Bad" data-rating-text="Bad" class="br-selected"></a>
            <a href="#" data-rating-value="Mediocre" data-rating-text="Mediocre" class="br-selected"></a>
            <a href="#" data-rating-value="Good" data-rating-text="Good" class=""></a>
            <a href="#" data-rating-value="Awesome" data-rating-text="Awesome" class=""></a>
            <div class="br-current-rating f-12"><label class="label label-inverse">Proses SPJ</label></div>
        </div>
    </div>
@elseif ($key->status_spj == 'Pending Pengadaan')
    <div class="br-wrapper br-theme-bars-movie">
        <span class="f-12">Status Pending Pengadaan</span>
        <div class="br-widget">
            <a href="#" data-rating-value="Bad" data-rating-text="Bad" class=""></a>
            <div class="br-current-rating f-12"><label class="label label-danger">Pending Pengadaan</label></div>
        </div>
    </div>
@elseif ($key->status_spj == 'Proses Pengadaan Barang')
    <div class="br-wrapper br-theme-bars-movie">
        <span class="f-12">Status Proses Pengadaan Barang</span>
        <div class="br-widget">
            <a href="#" data-rating-value="Bad" data-rating-text="Bad" class="br-selected"></a>
            <a href="#" data-rating-value="Mediocre" data-rating-text="Mediocre" class="br-selected"></a>
            <a href="#" data-rating-value="Good" data-rating-text="Good" class="br-selected"></a>
            <a href="#" data-rating-value="Awesome" data-rating-text="Awesome" class="br-selected"></a>
            <div class="br-current-rating f-12"><label class="label label-success">Proses Pengadaan Barang</label></div>
        </div>
    </div>
    </div>
@endif
