    @if (session('nama_level_user') == 'Direktur' && $item->status_permintaan_barang == 'Perlu Validasi PPTK')
        <a href="">
            <div class="step completed">
                <div class="circle">
                    <i class="fa fa-warning"></i>
                </div>
            </div>
        </a>
    @elseif (session('nama_level_user') == 'Direktur' && $item->status_permintaan_barang == 'Disetujui PPTK')
        <a href="">
            <div class="step stop">
                <div class="circle">
                    <i class="fa fa-warning text-danger"></i>
                </div>
            </div>
        </a>
    @elseif (session('nama_level_user') == 'Direktur' && $item->status_permintaan_barang == 'Disetujui Kabag')
        <a href="{{ route($routePrefix . '.keranjang', $item->id_usulan_barang) }}">
            <div class="step active">
                <div class="circle">
                    <i class="fa fa-warning text-warning"></i>
                </div>
            </div>
        </a>
    @elseif (session('nama_level_user') == 'Direktur' && $item->status_permintaan_barang == 'Disetujui Direktur')
        <a href="{{ route($routePrefix . '.show', $item->id_usulan_barang) }}">
            <div class="step completed">
                <div class="circle">
                    <i class="fa fa-expeditedssl"></i>
                </div>
            </div>
        </a>
    @elseif (session('nama_level_user') == 'Direktur' && $item->status_permintaan_barang == 'Di Tolak')
        <a href="{{ route($routePrefix . '.show', $item->id_usulan_barang) }}">
            <div class="step stop">
                <div class="circle">
                    <i class="fa fa-times text-danger"></i>
                </div>
            </div>
        </a>
    @endif


    @if (session('nama_level_user') == 'Validasi RKA' && $item->status_permintaan_barang == 'Perlu Validasi PPTK')
        <a href="">
            <div class="step stop">
                <div class="circle">
                    <i class="fa fa-warning text-danger"></i>
                </div>
            </div>
        </a>
    @elseif (session('nama_level_user') == 'Validasi RKA' && $item->status_permintaan_barang == 'Disetujui PPTK')
        <a href="{{ route($routePrefix . '.keranjang', $item->id_usulan_barang) }}">
            <div class="step active">
                <div class="circle">
                    <i class="fa fa-warning text-warning"></i>
                </div>
            </div>
        </a>
    @elseif (session('nama_level_user') == 'Validasi RKA' && $item->status_permintaan_barang == 'Perlu Validasi Perencana')
        <a href="#" class="btn btn-icon btn-danger waves-effect waves-light">
            <span class="tf-icon mdi mdi-account-lock-outline"></span>
        </a>
    @elseif (session('nama_level_user') == 'Validasi RKA' && $item->status_permintaan_barang == 'Disetujui Kabag')
        <a href="{{ route($routePrefix . '.show', $item->id_usulan_barang) }}">
            <div class="step completed">
                <div class="circle">
                    <i class="fa fa-expeditedssl"></i>
                </div>
            </div>
        </a>
    @elseif (session('nama_level_user') == 'Validasi RKA' && $item->status_permintaan_barang == 'Disetujui Direktur')
        <a href="{{ route($routePrefix . '.show', $item->id_usulan_barang) }}">
            <div class="step completed">
                <div class="circle">
                    <i class="fa fa-expeditedssl"></i>
                </div>
            </div>
        </a>
    @elseif (session('nama_level_user') == 'Validasi RKA' && $item->status_permintaan_barang == 'Di Tolak')
        <a href="{{ route($routePrefix . '.show', $item->id_usulan_barang) }}"
            class="btn btn-icon btn-danger waves-effect waves-light">
            <span class="tf-icon mdi mdi-checkbox-marked-circle-outline"></span>
        </a>
    @endif


    @php
        $pptks = [
            '9d5b9ca9-8699-4456-8267-153db7acb631',
            '9ce173c5-b49f-41e1-b17c-f55e25544a58',
            '9d5b9cbe-e3f4-4cfc-bb3a-3e5b4fca35db',
        ];
    @endphp
    @if (in_array(session('id_pptk'), $pptks) && $item->status_permintaan_barang == 'Perlu Validasi PPTK')
        <a href="{{ route($routePrefix . '.keranjang', $item->id_usulan_barang) }}">
            <div class="step active">
                <div class="circle">
                    <i class="fa fa-warning text-warning"></i>
                </div>
            </div>
        </a>
    @elseif(in_array(session('id_pptk'), $pptks) && $item->status_permintaan_barang == 'Disetujui PPTK')
        <a href="{{ route($routePrefix . '.show', $item->id_usulan_barang) }}">
            <div class="step completed">
                <div class="circle">
                    <i class="fa fa-expeditedssl"></i>
                </div>
            </div>
        </a>
    @elseif(in_array(session('id_pptk'), $pptks) && $item->status_permintaan_barang == 'Disetujui Kabag')
        <a href="{{ route($routePrefix . '.show', $item->id_usulan_barang) }}">
            <div class="step completed">
                <div class="circle">
                    <i class="fa fa-expeditedssl"></i>
                </div>
            </div>
        </a>
    @elseif(in_array(session('id_pptk'), $pptks) && $item->status_permintaan_barang == 'Disetujui Direktur')
        <a href="{{ route($routePrefix . '.show', $item->id_usulan_barang) }}">
            <div class="step completed">
                <div class="circle">
                    <i class="fa fa-expeditedssl"></i>
                </div>
            </div>
        </a>
    @endif
    @if (session('nama_level_user') == 'Administrator' && $item->status_permintaan_barang == 'Perlu Validasi Perencana')
        <a href="{{ route($routePrefix . '.keranjang', $item->id_usulan_barang) }}"
            class="btn btn-icon btn-warning waves-effect waves-light">
            <span class="tf-icon mdi mdi-account-alert-outline"></span>
        </a>
    @elseif (session('nama_level_user') == 'Administrator' && $item->status_permintaan_barang == 'Disetujui Perencana')
        <a href="{{ route($routePrefix . '.show', $item->id_usulan_barang) }}"
            class="btn btn-icon btn-primary waves-effect waves-light">
            <span class="tf-icon mdi mdi-checkbox-marked-circle-outline"></span>
        </a>
    @endif
