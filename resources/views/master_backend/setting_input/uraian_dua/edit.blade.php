<x-layouts.main class="main">
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-header start -->
                <x-partials.breadcrumb>
                </x-partials.breadcrumb>
                <!-- Page-header end -->

                <!-- Page body start -->
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- Basic Form Inputs card start -->
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-header-right">
                                        <i class="icofont icofont-spinner-alt-5"></i>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <h4 class="sub-title">Form Inputs</h4>
                                    <form id="confirmSubmitForm" method="POST"
                                        action="{{ route('uraian_duas.update', $uraianDua->id_uraian_2) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama Uraian</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_uraian_2"
                                                    value="{{ $uraianDua->nama_uraian_2 }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="card-block card-header-right">
                                            <a href="{{ route('uraian_duas.index') }}"
                                                class="btn btn-sm btn-secondary btn-outline-secondary me-4">
                                                <i class="icofont icofont-circled-left"></i> Kembali
                                            </a>
                                            <button class="btn btn-sm btn-primary btn-outline-primary me-4">
                                                <i class="icofont icofont-check-circled"></i> Submit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page body end -->
            </div>
        </div>
    </div>

</x-layouts.main>
