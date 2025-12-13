<div class="stepper">
    {{-- Step 1: Pemesanan Barang --}}
    @if ($key->status_proses_pesanan == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_proses_pemesanan)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content"><span class="fs-10">Pemesanan Barang</span></div>
        </div>
    @endif

    {{-- Step 2: Barang Datang --}}
    @if ($key->status_proses_pengiriman_barang == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_barang_datang)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content"><span class="fs-10">Barang Datang</span></div>
        </div>
    @endif

    {{-- Step 3: Verifikasi PB --}}
    @if ($key->status_verifikasi_pengurus_barang == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_pengurus_barang)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content"><span class="fs-10">Verifikasi PB</span></div>
        </div>
    @endif

    {{-- Step 4: BAST --}}
    @if ($key->status_proses_bast == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_bast)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content"><span class="fs-10">BAST</span></div>
        </div>
    @endif

    {{-- Step 5: Faktur --}}
    @if ($key->status_proses_tukar_faktur == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_proses_faktur)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content"><span class="fs-10">Faktur</span></div>
        </div>
    @endif

    {{-- Step 6–12: Semua Verifikasi --}}
    @php
        $verifikasiSteps = [
            [
                'label' => 'PPBJ',
                'status' => $key->status_verifikasi_ppbj,
                'tgl' => $key->tgl_verif_ppbj,
                'ket' => $key->ket_verif_ppbj ?? null,
            ],
            [
                'label' => 'PPK',
                'status' => $key->status_verifikasi_ppk,
                'tgl' => $key->tgl_verif,
                'ket' => $key->ket_verif_ppk ?? null,
            ],
            [
                'label' => 'PPTK',
                'status' => $key->status_verifikasi_pptk,
                'tgl' => $key->tgl_verif_pptk,
                'ket' => $key->ket_verif_pptk ?? null,
            ],
            [
                'label' => 'Verifikator',
                'status' => $key->status_verifikasi_verifikator,
                'tgl' => $key->tgl_verif_verifikator,
                'ket' => $key->ket_verif_verifikator ?? null,
            ],
            [
                'label' => 'PPK Keuangan',
                'status' => $key->status_verifikasi_ppk_keuangan,
                'tgl' => $key->tgl_verif_ppk_keuangan,
                'ket' => $key->ket_verif_ppk_keuangan ?? null,
            ],
            [
                'label' => 'Direktur',
                'status' => $key->status_verifikasi_direktur,
                'tgl' => $key->tgl_verif_direktur,
                'ket' => $key->ket_verif_direktur ?? null,
            ],
        ];
    @endphp

    @foreach ($verifikasiSteps as $v)
        @if ($v['status'] == 'Selesai')
            <div class="step completed">
                <div class="time">{{ \Carbon\Carbon::parse($v['tgl'])->translatedFormat('d F Y') }}</div>
                <div class="circle2"><i class="ri-check-line text-success"></i></div>
                <div class="content"><span class="fs-10">Verifikasi {{ $v['label'] }}</span></div>
            </div>
        @elseif($v['status'] == 'Revisi')
            <div class="step active">
                <div class="time">{{ \Carbon\Carbon::parse($v['tgl'])->translatedFormat('d F Y') }}</div>
                <div class="circle2"><i class="ri-alarm-warning-line text-danger"></i></div>
                <div class="content">
                    <span class="fs-10">Verifikasi {{ $v['label'] }}
                        @if ($v['ket'])
                            <i class="ri-user-voice-line text-danger" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-placement="top" data-bs-original-title="{{ $v['ket'] }}"></i>
                        @endif
                    </span>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Step 13: Penyerahan SPJ --}}
    @if ($key->status_serah_terima_bendahara == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tanggal_penyerahan_spj)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content"><span class="fs-10">Penyerahan SPJ ke Bendahara</span></div>
        </div>
    @elseif($key->status_serah_terima_bendahara == 'Revisi')
        <div class="step active">
            <div class="time">{{ \Carbon\Carbon::parse($key->tanggal_revisi_spj)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-alarm-warning-line text-danger"></i></div>
            <div class="content"><span class="fs-10">Penyerahan SPJ ke Bendahara</span></div>
        </div>
    @endif

    {{-- Step 14: Penerimaan SPJ --}}
    @if ($key->keterangan == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_bendahara)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content"><span class="fs-10">Penerimaan SPJ Bendahara</span></div>
        </div>
    @elseif($key->keterangan == 'Revisi')
        <div class="step active">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_bendahara)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-alarm-warning-line text-danger"></i></div>
            <div class="content"><span class="fs-10">Penerimaan SPJ Bendahara</span></div>
        </div>
    @endif

    {{-- Step 15: Pembayaran --}}
    @if ($key->status_pembayaran == 'Sudah di Bayar')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tanggal_pembayaran)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content"><span class="fs-10">Pembayaran Selesai</span></div>
        </div>
    @endif

    {{-- Step aktif umum jika masih ada proses berjalan --}}
    @if (
        $key->status_pembayaran != 'Sudah di Bayar' &&
            ($key->status_proses_pesanan != 'Selesai' ||
                $key->status_proses_pengiriman_barang != 'Selesai' ||
                $key->status_verifikasi_pengurus_barang != 'Selesai' ||
                $key->status_proses_bast != 'Selesai' ||
                $key->status_proses_tukar_faktur != 'Selesai' ||
                $key->status_verifikasi_ppbj != 'Selesai' ||
                $key->status_verifikasi_ppk != 'Selesai' ||
                $key->status_verifikasi_pptk != 'Selesai' ||
                $key->status_verifikasi_verifikator != 'Selesai' ||
                $key->status_verifikasi_ppk_keuangan != 'Selesai' ||
                $key->status_verifikasi_direktur != 'Selesai' ||
                $key->status_serah_terima_bendahara != 'Selesai' ||
                $key->keterangan != 'Selesai'))
        <div class="step active">
            <div class="time warning">{{ now()->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="fa fa-exclamation text-warning"></i></div>
            <div class="content">
                <p><small><strong>Proses Pengadaan Barang</strong></small></p>
            </div>
        </div>
    @endif
</div>
