<style>
    .rkbu-hero { position: relative; overflow: hidden; border: 0; border-radius: 1rem; background: linear-gradient(115deg, #f1f7ff 0%, #fff 72%); }
    .rkbu-hero::after { content: ''; position: absolute; width: 210px; height: 210px; right: -55px; top: -90px; border-radius: 50%; background: rgba(54, 153, 255, .08); pointer-events: none; }
    .rkbu-hero > .card-body { z-index: 1; }
    .rkbu-hero-icon { display: inline-flex; flex: 0 0 52px; align-items: center; justify-content: center; width: 52px; height: 52px; border-radius: .9rem; background: #e8f3ff; color: #3699ff; font-size: 1.35rem; }
    @media (max-width: 575.98px) {
        .rkbu-hero > .card-body { align-items: flex-start !important; flex-direction: column; padding: 1.15rem !important; }
        .rkbu-hero-action { width: 100%; }
    }
</style>

<div class="card rkbu-hero mb-6 shadow-sm">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-4 p-5 p-lg-6 position-relative">
        <div class="d-flex align-items-center gap-4">
            <span class="rkbu-hero-icon" aria-hidden="true"><i class="fas fa-clipboard-list"></i></span>
            <div>
                <h1 class="fs-2 fw-bold text-gray-900 mb-1">{{ $title ?? 'RKBU' }}</h1>
                <div class="text-muted">Kelola data kebutuhan dan pengajuan RKBU.</div>
            </div>
        </div>
        @isset($routePrefix)
            <a href="{{ route($routePrefix . '.create') }}" class="btn btn-primary rkbu-hero-action">
                <i class="fas fa-plus me-2"></i>Tambah Data
            </a>
        @endisset
    </div>
</div>
