@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ $title }}</h4>
                </div><!-- end card header -->
                <div class="card-body form-steps">
                    <div class="row gy-5">
                        <div class="col-lg-3">
                            <div class="nav flex-column custom-nav nav-pills" role="tablist" aria-orientation="vertical">
                                <button class="nav-link done" id="v-pills-bill-info-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-info" type="button" role="tab"
                                    aria-controls="v-pills-bill-info" aria-selected="true">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                        Step create
                                    </span>
                                    {{ $title }} Info
                                </button>
                            </div>
                            <!-- end nav -->
                        </div> <!-- end col-->
                        <div class="col-lg-6">
                            <div class="px-lg-4">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="v-pills-bill-address" role="tabpanel"
                                        aria-labelledby="v-pills-bill-address-tab">
                                        <div>
                                            <h5>Form {{ $title2 }}</h5>
                                            <p class="text-muted">Fill all information below</p>
                                        </div>
                                        <form id="confirmSubmitForm" method="POST"
                                            action="{{ route($routePrefix . '.update', $komponens->id_komponen) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Barang</label>
                                                        <input type="text" class="form-control" placeholder="Nama Barang"
                                                            name="nama_barang"
                                                            value="{{ old('nama_barang', $komponens->nama_barang) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Kode Barang</label>
                                                        <input type="text" class="form-control" placeholder="Kode Barang"
                                                            name="kode_barang"
                                                            value="{{ old('kode_barang', $komponens->kode_barang) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Kode Komponen</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Kode Komponen" name="kode_komponen"
                                                            value="{{ old('kode_komponen', $komponens->kode_komponen) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Satuan</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Satuan Komponen" name="satuan"
                                                            value="{{ old('satuan', $komponens->satuan) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Spesifikasi</label>
                                                        <textarea name="spek" class="form-control" id="VertimeassageInput" cols="3" rows="3">{{ $komponens->spek }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Harga Barang</label>
                                                        <input type="text" name="harga_barang" class="form-control"
                                                            value="{{ $komponens->harga_barang }}">
                                                    </div>
                                                </div>

                                                {{-- Atasan komponen --}}
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Jenis Kategori</label>
                                                        <select name="id_jenis_kategori_rkbu" class="form-select"
                                                            data-choices data-choices-sorting="true">
                                                            <option disabled
                                                                {{ old('id_jenis_kategori_rkbu', $komponens->id_jenis_kategori_rkbu) ? '' : 'selected' }}>
                                                                Pilih Kategori</option>
                                                            @foreach ($jenis_kategori_rkbu as $item)
                                                                <option value="{{ $item->id_jenis_kategori_rkbu }}"
                                                                    {{ old('id_jenis_kategori_rkbu', $komponens->id_jenis_kategori_rkbu) == $item->id_jenis_kategori_rkbu ? 'selected' : '' }}>
                                                                    {{ $item->nama_jenis_kategori_rkbu }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <hr class="my-4 text-muted">

                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" id="same-address">
                                                    <label class="form-check-label" for="same-address">Yakin isian anda
                                                        pada
                                                        form ini</label>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                @include('partials.submit_buttons')
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- end tab content -->
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-lg-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fs-14 text-primary mb-0"><i class="ri-shopping-cart-fill align-middle me-2"></i>
                                    Your Form</h5>
                                <span class="badge bg-danger rounded-pill">3</span>
                            </div>
                            <ul class="list-group mb-3">
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0">Product name</h6>
                                        <small class="text-muted">Brief description</small>
                                    </div>
                                    <span class="text-muted">$12</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div>
            <!-- end -->
        </div>
        <!-- end col -->
    </div>
@endsection
