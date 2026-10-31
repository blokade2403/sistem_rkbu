@extends('layouts.main')

@section('content')
    <div class="module-portal">
        <section class="portal-hero card border-0 shadow-sm mb-7">
            <div class="card-body p-6 p-lg-9">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-5">
                    <div>
                        <span class="badge badge-light-primary mb-3">SISTEM INFORMASI TERINTEGRASI</span>
                        <h1 class="fs-2x fw-bold text-gray-900 mb-2">Selamat datang, {{ auth()->user()->nama ?? auth()->user()->name ?? 'Pengguna' }}</h1>
                        <p class="fs-5 text-gray-600 mb-0">Pilih modul untuk melanjutkan pekerjaan Anda.</p>
                    </div>
                    <div class="portal-context">
                        <div class="text-muted fs-8 fw-bold text-uppercase mb-1">Unit Kerja</div>
                        <div class="fw-bold text-gray-800">{{ session('nama_unit') ?: auth()->user()->unit?->nama_unit ?: 'Unit belum diatur' }}</div>
                        <div class="text-muted fs-8 fw-bold text-uppercase mt-4 mb-1">Tahun Anggaran</div>
                        <div class="fw-bold text-gray-800">{{ session('tahun_anggaran') ?: 'Belum dipilih' }}</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <h2 class="fs-3 fw-bold text-gray-900 mb-1">Modul Aplikasi</h2>
                <div class="text-muted">Akses modul sesuai pekerjaan dan kewenangan akun Anda.</div>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light-primary">
                <i class="fas fa-chart-line me-2"></i>Dashboard Anggaran
            </a>
        </div>

        <div class="row g-5 mb-8">
            <div class="col-md-6 col-xxl-4">
                <a href="{{ route('rkbu.index') }}" class="module-card card h-100 border-0 shadow-sm text-decoration-none">
                    <div class="card-body p-6">
                        <div class="module-icon bg-light-primary text-primary"><i class="fas fa-clipboard-list"></i></div>
                        <div class="d-flex align-items-center gap-2 mt-5 mb-2">
                            <h3 class="fs-4 fw-bold text-gray-900 mb-0">RKBU</h3>
                            <span class="badge badge-light-success">Aktif</span>
                        </div>
                        <p class="text-gray-600 mb-4">Susun kebutuhan, ajukan, dan pantau proses validasi anggaran unit.</p>
                        <span class="module-link text-primary fw-bold">Buka RKBU <i class="fas fa-arrow-right ms-1"></i></span>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xxl-4">
                <a href="{{ route('usulan-belanja.index') }}" class="module-card card h-100 border-0 shadow-sm text-decoration-none">
                    <div class="card-body p-6">
                        <div class="module-icon bg-light-warning text-warning"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="d-flex align-items-center gap-2 mt-5 mb-2">
                            <h3 class="fs-4 fw-bold text-gray-900 mb-0">Usulan Belanja</h3>
                            <span class="badge badge-light-success">Aktif</span>
                        </div>
                        <p class="text-gray-600 mb-4">Kelola usulan, pengadaan, dokumen, penerimaan, dan proses pembayaran.</p>
                        <span class="module-link text-primary fw-bold">Buka Usulan Belanja <i class="fas fa-arrow-right ms-1"></i></span>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xxl-4">
                <a href="{{ route('persediaan.inventory.index') }}" class="module-card card h-100 border-0 shadow-sm text-decoration-none">
                    <div class="card-body p-6">
                        <div class="module-icon bg-light-info text-info"><i class="fas fa-boxes"></i></div>
                        <div class="d-flex align-items-center gap-2 mt-5 mb-2">
                            <h3 class="fs-4 fw-bold text-gray-900 mb-0">Persediaan</h3>
                        <span class="badge badge-light-success">Aktif</span>
                        </div>
                        <p class="text-gray-600 mb-4">Catat penerimaan barang yang telah dibayar, permintaan barang keluar, dan stok opname.</p>
                        <span class="module-link text-primary fw-bold">Buka Persediaan <i class="fas fa-arrow-right ms-1"></i></span>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xxl-4">
                <a href="{{ route('pendapatan.index') }}" class="module-card card h-100 border-0 shadow-sm text-decoration-none">
                    <div class="card-body p-6">
                        <div class="module-icon bg-light-success text-success"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="d-flex align-items-center gap-2 mt-5 mb-2">
                            <h3 class="fs-4 fw-bold text-gray-900 mb-0">Keuangan Pendapatan</h3>
                        <span class="badge badge-light-success">Aktif</span>
                        </div>
                        <p class="text-gray-600 mb-4">Catat target rekening dan realisasi bulanan serta bandingkan dengan realisasi belanja.</p>
                        <span class="module-link text-primary fw-bold">Buka Keuangan Pendapatan <i class="fas fa-arrow-right ms-1"></i></span>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xxl-4">
                @if (auth()->user()->hasRole('Pengurus Barang') || auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin'))
                    <a href="{{ route('asset-module.index') }}" class="module-card card h-100 border-0 shadow-sm text-decoration-none">
                        <div class="card-body p-6">
                            <div class="module-icon bg-light-danger text-danger"><i class="fas fa-laptop"></i></div>
                            <div class="d-flex align-items-center gap-2 mt-5 mb-2">
                                <h3 class="fs-4 fw-bold text-gray-900 mb-0">Aset Barang</h3>
                                <span class="badge badge-light-success">Aktif</span>
                            </div>
                            <p class="text-gray-600 mb-0">Transfer barang modal dari usulan belanja atau catat aset hibah secara manual.</p>
                        </div>
                    </a>
                @else
                    <div class="module-card card h-100 border-0 shadow-sm">
                        <div class="card-body p-6">
                            <div class="module-icon bg-light-danger text-danger"><i class="fas fa-laptop"></i></div>
                            <div class="d-flex align-items-center gap-2 mt-5 mb-2">
                                <h3 class="fs-4 fw-bold text-gray-900 mb-0">Aset Barang</h3>
                                <span class="badge badge-light-secondary">Pengurus Barang</span>
                            </div>
                            <p class="text-gray-600 mb-0">Modul registrasi aset dari belanja dan pencatatan manual aset hibah.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <section class="card border-0 shadow-sm mb-7">
            <div class="card-header border-0 pt-6">
                <div>
                    <h2 class="card-title fs-3 fw-bold text-gray-900">Alur Data Antar Modul</h2>
                    <div class="text-muted mt-1">Transaksi tetap terhubung ke unit dan item RKBU asal.</div>
                </div>
            </div>
            <div class="card-body pt-2">
                <div class="row g-5">
                    <div class="col-lg-6">
                        <div class="flow-panel h-100 p-5 rounded-3 bg-light-primary">
                            <div class="fw-bold text-gray-900 mb-4"><i class="fas fa-boxes text-primary me-2"></i>Alur Persediaan</div>
                            <div class="flow-steps">
                                <span>RKBU Persediaan</span><i class="fas fa-chevron-right"></i>
                                <span>Usulan &amp; Pembayaran</span><i class="fas fa-chevron-right"></i>
                                <span>Konfirmasi Penerimaan</span><i class="fas fa-chevron-right"></i>
                                <span>Barang Masuk &amp; Stok</span>
                            </div>
                            <div class="text-muted fs-7 mt-4">Stok bertambah setelah barang diterima dan jumlahnya dikonfirmasi.</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="flow-panel h-100 p-5 rounded-3 bg-light-warning">
                            <div class="fw-bold text-gray-900 mb-4"><i class="fas fa-laptop text-warning me-2"></i>Alur Aset Barang</div>
                            <div class="flow-steps">
                                <span>RKBU Modal</span><i class="fas fa-chevron-right"></i>
                                <span>Usulan &amp; Pembayaran</span><i class="fas fa-chevron-right"></i>
                                <span>Penerimaan &amp; Verifikasi</span><i class="fas fa-chevron-right"></i>
                                <span>Registrasi Aset</span>
                            </div>
                            <div class="text-muted fs-7 mt-4">Pengurus barang memeriksa barang sebelum aset dicatat dan diberi penanggung jawab.</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="revenue-flow p-5 rounded-3 bg-light-success">
                            <div class="fw-bold text-gray-900 mb-3"><i class="fas fa-money-bill-wave text-success me-2"></i>Alur Keuangan Pendapatan</div>
                            <div class="d-flex flex-wrap align-items-center gap-3 text-gray-700">
                                <span class="flow-chip">Pencatatan Pendapatan</span><i class="fas fa-arrow-right text-success"></i>
                                <span class="flow-chip">Rekap per Unit &amp; Sumber Dana</span><i class="fas fa-arrow-right text-success"></i>
                                <span class="flow-chip">Referensi Perencanaan dan Pagu</span>
                            </div>
                            <div class="text-muted fs-7 mt-3">Rincian integrasi pendapatan dan pagu dapat disesuaikan dengan kebijakan pencatatan keuangan yang berlaku.</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .portal-hero { overflow: hidden; border-radius: 1rem; background: linear-gradient(115deg, #eef6ff 0%, #fff 72%); }
        .portal-context { min-width: 220px; padding: 1rem 1.25rem; border: 1px solid rgba(54, 153, 255, .14); border-radius: .85rem; background: rgba(255, 255, 255, .78); }
        .module-card { border-radius: .9rem; transition: transform .18s ease, box-shadow .18s ease; }
        a.module-card:hover { transform: translateY(-3px); box-shadow: 0 1rem 2rem rgba(30, 50, 80, .1) !important; }
        .module-icon { display: inline-flex; width: 52px; height: 52px; align-items: center; justify-content: center; border-radius: 14px; font-size: 1.35rem; }
        .module-card p { min-height: 48px; }
        .flow-panel { border: 1px solid rgba(0, 0, 0, .035); }
        .flow-steps { display: flex; flex-wrap: wrap; align-items: center; gap: .65rem; color: #344054; font-size: .875rem; font-weight: 600; }
        .flow-steps span, .flow-chip { padding: .55rem .75rem; border-radius: .55rem; background: rgba(255, 255, 255, .82); }
        .flow-steps i { color: #8b96a8; font-size: .7rem; }
        @media (max-width: 575.98px) { .portal-context { width: 100%; } .flow-steps { align-items: flex-start; } }
    </style>
@endsection
