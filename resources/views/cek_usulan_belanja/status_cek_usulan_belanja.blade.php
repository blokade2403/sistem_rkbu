<div class="stepper">
    @if ($item->status_permintaan_barang == 'Perlu Validasi PPTK')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
            </div>
            <div class="circle">
                <i class="ri-check-double-line"></i>
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
                <i class="ri-error-warning-line"></i>
            </div>
            <div class="content">
                <h6>Perlu Validasi PPTK</h6>
            </div>
        </div>
    @elseif ($item->status_permintaan_barang == 'Disetujui PPTK')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
            </div>
            <div class="circle">
                <i class="ri-check-double-line"></i>
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
                <i class="ri-check-double-line"></i>
            </div>
            <div class="content">
                <span class="f-14">Validasi PPTK</span></br>
                <small> PIC: {{ $item->ValidPptk->nama_lengkap }}</small>
            </div>
        </div>
        <div class="step active">
            <div class="time warning">
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div class="circle">
                <i class="ri-error-warning-line"></i>
            </div>
            <div class="content">
                <h6>Perlu Validasi Kabag/Kabid</h6>
            </div>
        </div>
    @elseif ($item->status_permintaan_barang == 'Disetujui Kabag')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
            </div>
            <div class="circle">
                <i class="ri-check-double-line"></i>
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
                <i class="ri-check-double-line"></i>
            </div>
            <div class="content">
                <span class="f-14">Validasi PPTK</span></br>
                <small> PIC: {{ $item->nama_valid_pptk }}</small>
            </div>
        </div>
        <div class="step completed">
            <div class="time">
                {{ \Carbon\Carbon::parse($item->tgl_validasi_kabag)->translatedFormat('d F Y') }}</div>
            <div class="circle">
                <i class="ri-check-double-line"></i>
            </div>
            <div class="content">
                <span class="f-14">Validasi Kabag</span></br>
                <small> PIC: {{ $item->nama_valid_rka }}</small>
            </div>
        </div>
        <div class="step active">
            <div class="time warning">
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div class="circle">
                <i class="ri-error-warning-line"></i>
            </div>
            <div class="content">
                <h6>Perlu Validasi Direktur</h6>
            </div>
        </div>
    @elseif ($item->status_permintaan_barang == 'Disetujui Direktur')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
            </div>
            <div class="circle">
                <i class="ri-check-double-line"></i>
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
                <i class="ri-check-double-line"></i>
            </div>
            <div class="content">
                <span class="f-14">Validasi PPTK</span></br>
                <small> PIC: {{ $item->ValidPptk->nama_lengkap }}</small>
            </div>
        </div>
        <div class="step completed">
            <div class="time">
                {{ \Carbon\Carbon::parse($item->tgl_validasi_kabag)->translatedFormat('d F Y') }}</div>
            <div class="circle">
                <i class="ri-check-double-line"></i>
            </div>
            <div class="content">
                <span class="f-14">Validasi Kabag</span></br>
                <small> PIC: {{ $item->validRka->nama_lengkap }}</small>
            </div>
        </div>
        <div class="step completed">
            <div class="time">
                {{ \Carbon\Carbon::parse($item->tgl_validasi_direktur)->translatedFormat('d F Y') }}</div>
            <div class="circle">
                <i class="ri-check-double-line"></i>
            </div>
            <div class="content">
                <span class="f-14">Validasi Direktur</span></br>
                <small> PIC: {{ $item->validDirektur->nama_lengkap }}</small>
            </div>
        </div>
    @elseif ($item->status_permintaan_barang == 'Di Tolak')
        <div class="step stop">
            <div class="time">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
            </div>
            <div class="circle">
                <i class="ri-timer-line"></i>
            </div>
            <div class="content">
                <span class="f-14">Usulan Belanja</span></br>
                <small> PIC: {{ $item->validDirektur->nama_lengkap ?? '' }}</small>
            </div>
        </div>
    @endif
