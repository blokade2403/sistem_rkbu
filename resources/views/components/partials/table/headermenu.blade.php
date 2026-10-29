<style>
    .page-hero { position: relative; overflow: hidden; border: 0; border-radius: 1rem; background: linear-gradient(115deg, #f1f7ff 0%, #fff 72%); }
    .page-hero::after { content: ''; position: absolute; width: 210px; height: 210px; right: -55px; top: -90px; border-radius: 50%; background: rgba(54, 153, 255, .08); pointer-events: none; }
    .page-hero > .card-body { position: relative; z-index: 1; }
    .page-hero-icon { display: inline-flex; flex: 0 0 52px; align-items: center; justify-content: center; width: 52px; height: 52px; border-radius: .9rem; background: #e8f3ff; color: #3699ff; font-size: 1.35rem; }
    .page-hero-summary { margin-top: 1.25rem; }
    .page-hero-summary table { margin-bottom: 0; }
    .page-hero-summary th { white-space: nowrap; color: #78849a; font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; }
    .page-hero-summary td { vertical-align: middle; }
    @media (max-width: 575.98px) {
        .page-hero > .card-body { align-items: flex-start !important; flex-direction: column; padding: 1.15rem !important; }
        .page-hero-action { width: 100%; }
    }
</style>

<div class="card page-hero mb-6 shadow-sm">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-4 p-5 p-lg-6">
        <div class="d-flex align-items-center gap-4">
            <span class="page-hero-icon" aria-hidden="true"><i class="fas fa-layer-group"></i></span>
            <div>
                <h1 class="fs-2 fw-bold text-gray-900 mb-1">{{ $title ?? 'Data' }}</h1>
                <div class="text-muted">Kelola data {{ strtolower($title ?? 'aplikasi') }}.</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="#" class="btn btn-primary page-hero-action" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">
                <i class="fas fa-plus me-2"></i>Tambah Data
            </a>
        </div>
        @isset($paguAccountSummary)
            <div class="page-hero-summary w-100">
                @include('components.partials.table.pagu-summary')
            </div>
        @endisset
    </div>
</div>
