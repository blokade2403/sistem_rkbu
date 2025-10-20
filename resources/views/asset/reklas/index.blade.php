@extends('layouts.main')
@section('content')
    {{-- @include('partials.add_buttons_asset', ['routePrefix' => $routePrefix]) --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                </div><!-- end card header -->
                <div class="card-block">
                    @include('asset.filter_asset')
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="responsive-reorder"
                                class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th style="width: 4%;">No</th>
                                        <th style="width: 10%;">Nama Barang</th>
                                        <th style="width: 7%;">Kode Asset</th>
                                        <th style="width: 10%;">Aksi</th>
                                        <th style="width: 5%;">QRCode</th>
                                        <th style="width: 10%;">Harga Asset</th>
                                        <th>Action</th>
                                        <th>Kondisi Asset</th>
                                        <th style="width: 10%; white-space: normal; word-wrap: break-word;">
                                            Lokasi Asset</th>
                                        <th>Status Asset</th>
                                        <th>Spesifikasi</th>
                                        <th>Create</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($assets as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-light rounded p-1 me-2">
                                                        <a href="{{ asset('storage/foto_asset/' . basename($row->foto)) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('storage/foto_asset/' . basename($row->foto)) }}"
                                                                alt="" class="img-fluid d-block">
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <h3 class="fs-14 my-1"><a href="apps-ecommerce-product-details.html"
                                                                class="text-reset">{{ $row->nama_asset }}</a></h3>
                                                        <span class="text-muted">{{ $row->tahun_perolehan }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-soft-info text-info">{{ $row->kode_asset }}-{{ $row->no_register }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="hstack gap-2 mt-4 mt-sm-0">
                                                    <a href="{{ route('barang_assets.details', ['id' => $row->id_asset]) }}"
                                                        class="btn btn-outline-primary btn-icon waves-effect waves-light shadow-none"
                                                        title="Detail Asset">
                                                        <i class="ri-restart-fill"></i>
                                                    </a>

                                                    <a href="{{ route('barang_assets.generate_qrcode', ['id_asset' => $row->id_asset]) }}"
                                                        class="btn btn-outline-info btn-icon waves-effect waves-light shadow-none"
                                                        title="Generate QR Code">
                                                        <i class="ri-qr-code-line"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                @if (empty($row->qrcode_path))
                                                    <span class="text-muted">Belum Digenerate</span>
                                                @else
                                                    <a href="{{ asset('storage/qrcode_assets/' . basename($row->qrcode_path)) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('storage/qrcode_assets/' . basename($row->qrcode_path)) }}"
                                                            width="80" height="80">
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                Rp. {{ number_format($row->harga_asset) }}
                                            </td>
                                            <td>
                                                <x-partials.action-buttons :edit-url="route($routePrefix . '.edit', $row->id_asset)" :delete-url="route($routePrefix . '.destroy', $row->id_asset)" />
                                            </td>
                                            <td>
                                                @php
                                                    $badgeClass = match ($row->kondisi_asset) {
                                                        'Baik' => 'badge badge-outline-success',
                                                        'Rusak Ringan' => 'badge badge-outline-warning',
                                                        'Rusak Berat' => 'badge badge-outline-danger',
                                                        default => 'badge badge-outline-secondary',
                                                    };
                                                @endphp
                                                <span class="{{ $badgeClass }}">{{ $row->kondisi_asset }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-soft-info text-info">{{ $row->Penempatan->lokasi_barang ?? 'Belum Ditempatkan' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-soft-info text-info">{{ $row->status_asset }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-soft-info text-info">{{ $row->spek }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="f-12 text-wrap">#&nbsp;{{ $row->created_at }}</span>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div> <!-- .card-->
        </div> <!-- .col-->
    </div> <!-- end row-->
@endsection
