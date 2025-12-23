@foreach ($usulan_barangs as $index => $yek)
    <div class="modal fade bs-example-modal-pegawai" id="large-Modal{{ $index }}" tabindex="-1" role="dialog"
        aria-labelledby="modalPegawaiLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPegawaiLabel">Form Usulan Belanja Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="col-lg-12">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-evenly">
                            <p class="text-muted mb-0"><i
                                    class="mdi mdi-numeric-1-circle text-success fs-18 align-middle me-2 rounded-circle shadow"></i>Completed
                            </p>
                            <p class="text-muted mb-0"><i
                                    class="mdi mdi-numeric-3-circle text-info fs-18 align-middle me-2 rounded-circle shadow"></i>In
                                Progress</p>
                            <p class="text-muted mb-0"><i
                                    class="mdi mdi-numeric-2-circle text-primary fs-18 align-middle me-2 rounded-circle shadow"></i>To
                                Do</p>
                        </div>
                    </div>
                    <div class="progress animated-progress bg-soft-primary rounded-bottom rounded-0"
                        style="height: 6px;">
                        <div class="progress-bar bg-success rounded-0" role="progressbar" style="width: 30%"
                            aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-info rounded-0" role="progressbar" style="width: 50%"
                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar rounded-0" role="progressbar" style="width: 20%" aria-valuenow="20"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="modal-body">
                    <h6 class="fs-15">Pilih komponen usulan belanja</h6>
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-checkbox-circle-fill text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="text-muted mb-0">Komponen ini adalah komponen
                                yang sudah divalidasi.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <form id="cartForm{{ $index }}" method="POST"
                                action="{{ route('add-to-cart-multiple-barjas') }}">
                                @csrf
                                <table id="example{{ $index }}" class="table table-striped mb-0 datatable"
                                    style="min-width: 845px">
                                    <thead class="table-light border-top">
                                        <tr>
                                            <th>No</th>
                                            <th>Add Cart</th>
                                            <th>Barang</th>
                                            <th>Qty</th>
                                            <th>Sisa Stok</th>
                                            <th>Harga Satuan</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($get_barangs[$yek->id_usulan_barang] ?? [] as $rkbu)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <input type="checkbox" name="selected_ids[]"
                                                        value="{{ $rkbu->id_rkbu }}"
                                                        {{ $rkbu->sisa_anggaran_rkbu < $rkbu->harga_satuan ? 'disabled title=Tidak cukup anggaran' : '' }} />
                                                    <input type="hidden" name="id_usulan_barang"
                                                        value="{{ $yek->id_usulan_barang }}" />
                                                </td>
                                                <td class="text-wrap"
                                                    style="white-space: normal; word-wrap: break-word;">
                                                    <span
                                                        class="text-primary"><strong>{{ $rkbu->nama_barang }}</strong></span></br>
                                                    <small class="fs-12">Spesifikasi:
                                                        {{ $rkbu->spek }}</small>
                                                </td>
                                                <td>{{ $rkbu->jumlah_vol }}</td>
                                                <td>{{ $rkbu->sisa_vol_rkbu }}</td>
                                                <td>
                                                    <span class="fs-12">
                                                        Harga Satuan:
                                                        Rp.{{ number_format($rkbu->harga_satuan, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fs-12">
                                                        Total Anggaran:
                                                        Rp.{{ number_format($rkbu->total_anggaran, 0, ',', '.') }}
                                                    </span><br />
                                                    <p class="fs-12 text-danger">
                                                        Sisa Anggaran:
                                                        Rp.{{ number_format($rkbu->sisa_anggaran_rkbu, 0, ',', '.') }}
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Add Cart</th>
                                            <th>No</th>
                                            <th>Barang</th>
                                            <th>Qty</th>
                                            <th>Sisa Stok</th>
                                            <th>Harga Satuan</th>
                                            <th>Detail</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium"
                            data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                        <button type="submit" form="cartForm{{ $index }}" class="btn btn-primary ">Save
                            changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
@endforeach
