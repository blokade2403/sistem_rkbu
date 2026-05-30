@php
    use Illuminate\Support\Str;

    $segments = request()->segments();
    $url = '';
@endphp

<div class="row">
    <div class="col-xl-12">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Selamat Datang, {{ session('nama_lengkap') }} !!!</h4>
                        <p class="text-muted mb-0">Anda sedang membuka halaman
                            @foreach ($segments as $key => $segment)
                                @if ($loop->last)
                                    {{ Str::title(str_replace('-', ' ', $segment)) }}
                                @else
                                    <a href="{{ url($url) }}">
                                        {{ Str::title(str_replace('-', ' ', $segment)) }}
                                    </a>
                                @endif
                            @endforeach

                        </p>
                    </div>
                    <div class="mt-3 mt-lg-0">
                        <form action="javascript:void(0);">
                            <div class="row g-3 mb-0 align-items-center">
                                <div class="col-sm-auto">
                                    <div class="input-group">
                                        <input type="text" class="form-control border-0 dash-filter-picker shadow"
                                            data-provider="flatpickr" data-range-date="true" data-date-format="d M, Y"
                                            data-deafult-date="01 Jan 2022 to 31 Jan 2022">
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                @if (session())
                                @endif
                                <div class="col-auto">
                                    <button class="btn btn-soft-success shadow-none" data-bs-toggle="modal"
                                        data-bs-target="#createModal"><i
                                            class="ri-add-circle-line align-middle me-1"></i>
                                        Add Product</button>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route($routePrefix . '.create') }}"
                                        class="btn btn-soft-success shadow-none"><i
                                            class="ri-add-circle-line align-middle me-1"></i>
                                        Tambah Data</a>
                                </div>
                        </form>

                        <!--end col-->
                        @if (request()->routeIs('users.*'))
                            <div class="col-auto">
                                <form action="{{ route('update.status', '9cff8aee-6b6d-49e8-a1d4-cb44f96377d8') }}"
                                    method="POST" class="form-status">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status_edit" value="aktif">

                                    <button type="submit" class="btn btn-soft-success btn-icon" data-status="AKTIF"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="tooltip-primary" data-bs-original-title="Aktif Edit User">
                                        <i class="ri-user-follow-line"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="col-auto">
                                <form action="{{ route('update.status', '9cff8aee-6b6d-49e8-a1d4-cb44f96377d8') }}"
                                    method="POST" class="form-status">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status_edit" value="tidak aktif">

                                    <button type="submit" class="btn btn-soft-danger btn-icon" data-status="NON AKTIF"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="tooltip-primary"
                                        data-bs-original-title="Non Aktif Edit User">
                                        <i class="ri-user-unfollow-fill"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <!--end row-->
                </div>
            </div><!-- end card header -->
        </div>
        <!--end col-->
    </div>
</div>
</div>
