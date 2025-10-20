<div class="stepper">
    {{-- Step 1: Pemesanan Barang --}}
    @if ($key->status_proses_pesanan == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_proses_pemesanan)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Pemesanan Barang</span>
            </div>
        </div>
    @endif

    {{-- Step 2: Barang Datang --}}
    @if ($key->status_proses_pengiriman_barang == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_barang_datang)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Barang Datang</span>
            </div>
        </div>
    @endif

    {{-- Step 3: Verifikasi PB --}}
    @if ($key->status_verifikasi_pengurus_barang == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_pengurus_barang)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Verifikasi PB</span>
            </div>
        </div>
    @endif

    {{-- Step 4: BAST --}}
    @if ($key->status_proses_bast == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_bast)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">BAST</span>
            </div>
        </div>
    @endif

    {{-- Step 5: Faktur --}}
    @if ($key->status_proses_tukar_faktur == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_proses_faktur)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Faktur</span>
            </div>
        </div>
    @endif

    {{-- Step 5: PPBJ --}}
    @if ($key->status_verifikasi_ppbj == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_ppbj)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Verifikasi PPBJ</span>
            </div>
        </div>
    @elseif($key->status_verifikasi_ppbj == 'Revisi')
        <div class="step active">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_ppbj)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-alarm-warning-line text-danger"></i>
            </div>
            <div class="content">
                <span class="fs-10">Verifikasi PPBJ <i class="ri-user-voice-line text-danger" data-bs-toggle="tooltip"
                        data-bs-trigger="hover" data-bs-placement="top" title=""
                        data-bs-original-title="{{ $key->ket_verif_ppbj }}"></i>
                </span>
            </div>
        </div>
    @endif

    {{-- Step 6: PPK --}}
    @if ($key->status_verifikasi_ppk == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_ppk)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Verifikasi PPK</span>
            </div>
        </div>
    @elseif($key->status_verifikasi_ppk == 'Revisi')
        <div class="step active">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_ppk)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-alarm-warning-line text-danger"></i>
            </div>
            <div class="content">
                <span class="fs-10">Verifikasi PPK <i class="ri-user-voice-line text-danger" data-bs-toggle="tooltip"
                        data-bs-trigger="hover" data-bs-placement="top" title=""
                        data-bs-original-title="{{ $key->ket_verif_ppk }}"></i>
                </span>
            </div>
        </div>
    @endif

    {{-- Step 7: PPTK --}}
    @if ($key->status_verifikasi_pptk == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_pptk)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Verifikasi PPTK</span>
            </div>
        </div>
    @elseif($key->status_verifikasi_pptk == 'Revisi')
        <div class="step active">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_pptk)->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="ri-alarm-warning-line text-danger"></i>
            </div>
            <div class="content">
                <span class="fs-10">Verifikasi PPTK <i class="ri-user-voice-line text-danger" data-bs-toggle="tooltip"
                        data-bs-trigger="hover" data-bs-placement="top" title=""
                        data-bs-original-title="{{ $key->ket_verif_pptk }}"></i>
                </span>
            </div>
        </div>
    @endif

    {{-- Step 8: Verifikator --}}
    @if ($key->status_verifikasi_verifikator == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_verifikator)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Verifikasi Verifikator</span>
            </div>
        </div>
    @elseif($key->status_verifikasi_verifikator == 'Revisi')
        <div class="step active">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_verifikator)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-alarm-warning-line text-danger"></i>
            </div>
            <div class="content">
                <span class="fs-10">Verifikasi Verifikator <i class="ri-user-voice-line text-danger"
                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                        data-bs-original-title="{{ $key->ket_verif_verifikator }}"></i>
                </span>
            </div>
        </div>
    @endif

    {{-- Step 9: PPK Keuangan --}}
    @if ($key->status_verifikasi_ppk_keuangan == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_ppk_keuangan)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Verifikasi PPK Keuangan</span>
            </div>
        </div>
    @elseif($key->status_verifikasi_ppk_keuangan == 'Revisi')
        <div class="step active">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_ppk_keuangan)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-alarm-warning-line text-danger"></i>
            </div>
            <div class="content">
                <span class="fs-10">Verifikasi PPK Keuangan <i class="ri-user-voice-line text-danger"
                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                        data-bs-original-title="{{ $key->ket_verif_ppk_keuangan }}"></i>
                </span>
            </div>
        </div>
    @endif

    {{-- Step 10: Direktur --}}
    @if ($key->status_verifikasi_direktur == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_direktur)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Verifikasi Direktur</span>
            </div>
        </div>
    @elseif($key->status_verifikasi_direktur == 'Revisi')
        <div class="step active">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_direktur)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-alarm-warning-line text-danger"></i>
            </div>
            <div class="content">
                <span class="fs-10">Verifikasi Direktur <i class="ri-user-voice-line text-danger"
                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                        data-bs-original-title="{{ $key->ket_verif_direktur }}"></i>
                </span>
            </div>
        </div>
    @endif

    {{-- Step 11: Penyerahan SPJ --}}
    @if ($key->status_serah_terima_bendahara == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tanggal_penyerahan_spj)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Penyerahan SPJ ke Bendahara</span>
            </div>
        </div>
    @elseif($key->status_serah_terima_bendahara == 'Revisi')
        <div class="step active">
            <div class="time">{{ \Carbon\Carbon::parse($key->tanggal_revisi_spj)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-alarm-warning-line text-danger"></i>
            </div>
            <div class="content">
                <span class="fs-10">Penyerahan SPJ ke Bendahara <i class="ri-user-voice-line text-danger"
                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                        data-bs-original-title="Revisi"></i>
                </span>
            </div>
        </div>
    @endif

    {{-- Step 12: Penerimaan SPJ --}}
    @if ($key->keterangan == 'Selesai')
        <div class="step completed">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_bendahara)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-check-line text-success"></i></div>
            <div class="content">
                <span class="fs-10">Penerimaan SPJ Bendahara</span>
            </div>
        </div>
    @elseif($key->keterangan == 'Revisi')
        <div class="step active">
            <div class="time">{{ \Carbon\Carbon::parse($key->tgl_verif_bendahara)->translatedFormat('d F Y') }}
            </div>
            <div class="circle2"><i class="ri-alarm-warning-line text-danger"></i>
            </div>
            <div class="content">
                <span class="fs-10">Penerimaan SPJ Bendahara<i class="ri-user-voice-line text-danger"
                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                        data-bs-original-title="Revisi"></i>
                </span>
            </div>
        </div>
    @endif

    {{-- Jika ada salah satu proses yang belum selesai, tampilkan hanya satu step aktif --}}

    @if (
        $key->status_proses_pesanan != 'Selesai' ||
            $key->status_proses_pengiriman_barang != 'Selesai' ||
            $key->status_verifikasi_pengurus_barang != 'Selesai' ||
            $key->status_proses_bast != 'Selesai' ||
            $key->status_proses_tukar_faktur != 'Selesai' ||
            ($key->status_verifikasi_ppbj != 'Selesai' && $key->status_verifikasi_ppbj != 'Revisi') ||
            ($key->status_verifikasi_ppk != 'Selesai' && $key->status_verifikasi_ppk != 'Revisi') ||
            ($key->status_verifikasi_pptk != 'Selesai' && $key->status_verifikasi_pptk != 'Revisi') ||
            ($key->status_verifikasi_verifikator != 'Selesai' && $key->status_verifikasi_verifikator != 'Revisi') ||
            ($key->status_verifikasi_ppk_keuangan != 'Selesai' && $key->status_verifikasi_ppk_keuangan != 'Revisi') ||
            ($key->status_verifikasi_direktur != 'Selesai' && $key->status_verifikasi_direktur != 'Revisi') ||
            ($key->keterangan != 'Selesai' && $key->keterangan != 'Revisi'))
        <div class="step active">
            <div class="time warning">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div class="circle2"><i class="fa fa-exclamation text-warning"></i></div>
            <div class="content">
                <p><small><strong>Proses Pengadaan Barang</strong></small></p>
            </div>
        </div>
    @endif
</div>
