<div class="stepper">
    @if ($item->status_permintaan_barang == 'Perlu Validasi PPTK')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
            </div>
            <div class="circle">
                <i class="fa fa-check text-success"></i>
            </div>
            <div class="content">
                <span class="f-14">Usulan Belanja</span></br>
                <small> PIC: {{ $item->nama_pengusul_barang }}</small>
            </div>
        </div>
        <div class="step active">
            <div class="time warning">
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div class="circle">
                <i class="fa fa-exclamation text-warning"></i>
            </div>
            <div class="content">
                <h6>Perlu Validasi PPTK</h6>
            </div>
        </div>
    @elseif ($item->status_permintaan_barang == 'Validasi PPTK')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
            </div>
            <div class="circle">
                <i class="fa fa-check text-success"></i>
            </div>
            <div class="content">
                <span class="f-14">Usulan Belanja</span></br>
                <small> PIC: {{ $item->nama_pengusul_barang }}</small>
            </div>
        </div>
        <div class="step completed">
            <div class="time">
                {{ \Carbon\Carbon::parse($item->tgl_validasi_pptk)->translatedFormat('d F Y') }}</div>
            <div class="circle">
                <i class="fa fa-check text-success"></i>
            </div>
            <div class="content">
                <span class="f-14">Validasi PPTK</span></br>
                <small> PIC: {{ $item->nama_valid_pptk }}</small>
            </div>
        </div>
        <div class="step active">
            <div class="time warning">
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div class="circle">
                <i class="fa fa-exclamation text-warning"></i>
            </div>
            <div class="content">
                <h6>Perlu Validasi Kabag/Kabid</h6>
            </div>
        </div>
    @endif
    {{-- <div class="step active">
            <div class="time warning">14:00</div>
            <div class="circle">
                <i class="fa fa-exclamation text-warning"></i>
            </div>
            <div class="content">
                <h6>Step Title</h6>
                <p><small> Explain your step here...</small></p>
            </div>
        </div>

        <div class="step">
            <div class="time"></div>
            <div class="circle"></div>
            <div class="content">
                <h6>Step Title</h6>
                <p><small> Explain your step here...</small></p>
            </div>
        </div>

        <div class="step">
            <div class="time"></div>
            <div class="circle"></div>
            <div class="content">
                <h6>Step Title</h6>
                <p>Explain your step here...</p>
            </div>
        </div> --}}
</div>
