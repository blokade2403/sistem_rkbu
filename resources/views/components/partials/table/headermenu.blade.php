<div class="card mb-6 mb-xl-9">
    <div class="card-body pt-9 pb-0">
        <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
            <div class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-150px h-lg-150px me-7 mb-4">
                <img class="mw-50px mw-lg-75px" src="assets/media/svg/brand-logos/volicity-9.svg" alt="image">
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-1">
                            <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bolder me-3">{{ $title }}</a>
                            <span class="badge badge-light-success me-auto">In Progress</span>
                        </div>
                        <div class="d-flex flex-wrap fw-bold mb-4 fs-5 text-gray-400">#1 Tool to get started with Web Apps any Kind &amp; size</div>
                    </div>
                    <div class="d-flex mb-4" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to add a user">
                        <a href="#" class="btn btn-sm btn-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">Tambah Data</a>
                        <div class="me-0">
                            <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <i class="bi bi-three-dots fs-3"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @isset($paguAccountSummary)
                    @include('components.partials.table.pagu-summary')
                @endisset
            </div>
        </div>
    </div>
</div>
