@extends('layouts.main')
@section('content')
    <div class="row gy-4 mb-4">
        <!-- Congratulations card -->
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="row">
                    <div class="col-md-7 col-12 order-2 order-md-0">
                        <div class="card-header">
                            <h5 class="mb-0">Laporan Asset</h5>
                        </div>
                        <div class="card-body">
                            <div id="totalTransactionChart"></div>
                        </div>
                    </div>
                    <div class="col-md-5 col-12 border-start">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-1"></h5>
                            </div>
                            <p class="text-muted mb-0">Data Inventory Asset </p>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-6 border-end">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="avatar">
                                            <div class="avatar-initial bg-label-success rounded">
                                                <div class="mdi mdi-trending-up mdi-24px"></div>
                                            </div>
                                        </div>
                                        <p class="text-muted my-2">Asset Baik</p>
                                        <h6 class="mb-0 fw-semibold"> {{ $totalBaik }}</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="avatar">
                                            <div class="avatar-initial bg-label-primary rounded">
                                                <div class="mdi mdi-trending-down mdi-24px"></div>
                                            </div>
                                        </div>
                                        <p class="text-muted my-2">Asset Rusak </p>
                                        <h6 class="mb-0 fw-semibold">{{ $totalRusak }}</h6>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4" />
                            <div class="d-flex justify-content-around">
                                <div>
                                    <p class="text-muted mb-1">Total Inventory Asset</p>
                                    <h6 class="mb-0 fw-semibold">{{ $totalAsset }}</h6>
                                </div>
                                <a href="{{ route('barang_assets.index') }}" class="btn btn-primary" type="button">Lihat
                                    Data</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Total Transactions & Report Chart -->

        <!-- Performance Chart -->
        <div class="col-12 col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header pb-1">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-1">Performance</h5>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div id="performanceChart"></div>
                </div>
            </div>
        </div>
        <!--/ Performance Chart -->
        <!-- Top Referral Source  -->
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0">
                        <h5 class="mb-0">Laporan KIR</h5>
                        <small class="text-muted">82% Activity Growth</small>
                    </div>
                </div>
                <div class="card-body pb-3">
                    <div class="tab-content p-0 ms-0 ms-sm-2">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Lokasi</th>
                                        <th>Lantai Gedung</th>
                                        <th>Lokasi Gedung</th>
                                        <th>Penanggung Jawab</th>
                                        <th>Total Aset</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($assetsGrouped as $group)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $group->penempatan->lokasi_barang ?? '-' }}</td>
                                            <td>{{ $group->penempatan->tempat_lokasi ?? '-' }}</td>
                                            <td>{{ $group->penempatan->gedung ?? '-' }}</td>
                                            <td>{{ $group->penempatan->penanggung_jawab ?? '-' }}</td>
                                            <td>{{ $group->total_assets }}</td>
                                            <td><a href="{{ route('laporan_kirs.print', $group->id_penempatan) }}"
                                                    class="btn btn-icon btn-outline-primary waves-effect"
                                                    data-bs-toggle="tooltip" title="Print KIR"
                                                    style="display: inline-block;">
                                                    <span class="tf-icons mdi mdi-printer-search"></span>
                                                </a>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Top Referral Source  -->
    @endsection
