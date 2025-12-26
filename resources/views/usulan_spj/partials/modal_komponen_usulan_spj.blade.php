@foreach ($id_usulan as $index => $yek)
    <div class="modal fade bs-example-modal-xl" id="large-Modal{{ $index }}" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Komponen Usulan Barang</h4>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
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
                <div class="modal-body" style="padding: 1rem 1.5rem;">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive" style="margin:0; padding:0;">
                                <form id="cartForm{{ $index }}" action="{{ route($routePrefix . '.store') }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="id_spj" value="{{ $yek->id_spj }}">
                                    <input type="hidden" name="id_usulan_barang" value="{{ $yek->id_usulan_barang }}">
                                    <table id="example{{ $index }}" class="table table-striped mb-2 datatable"
                                        style="min-width: 445px; margin:0; padding:30;">
                                        <thead class="table-light border-top">
                                            <tr>
                                                @php
                                                    $isPegawai = Route::is('usulan_spj_pegawai.*');
                                                @endphp
                                                <th>No</th>
                                                <th>
                                                    <div class="form-check custom-checkbox ms-2">
                                                        <input type="checkbox" id="checkAll{{ $index }}"
                                                            class="form-check-input">
                                                        <label class="form-check-label"
                                                            for="checkAll{{ $index }}"></label>
                                                    </div>
                                                </th>
                                                <th>SUB Kegiatan</th>
                                                @if ($isPegawai)
                                                    <th>Nama Pegawai</th>
                                                @else
                                                    <th>Nama Barang dan Spek</th>
                                                @endif

                                                <th>Qty</th>
                                                <th>Sisa Stok</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($get_barangs[$yek->id_usulan_barang] as $rkbu)
                                                <tr>

                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        <div class="form-check custom-checkbox md-2">
                                                            <input type="checkbox" name="id_usulan_barang_detail[]"
                                                                value="{{ $rkbu->id_usulan_barang_detail }}"
                                                                class="form-check-input check-item{{ $index }}">
                                                        </div>
                                                    </td>
                                                    <td class="text-wrap"
                                                        style="white-space: normal; word-wrap: break-word;">
                                                        {{ optional($rkbu->sub_kategori_rkbu)->kode_sub_kategori_rkbu }}.
                                                        {{ optional($rkbu->sub_kategori_rkbu)->nama_sub_kategori_rkbu }}
                                                    </td>
                                                    <td class="text-wrap"
                                                        style="white-space: normal; word-wrap: break-word;">
                                                        @if ($isPegawai)
                                                            <strong>{{ $rkbu->nama_pegawai }}</strong></br>
                                                        @else
                                                            <strong>{{ $rkbu->nama_barang }}</strong></br>
                                                        @endif

                                                        <span class="f-12">
                                                            Spesifikasi: {{ $rkbu->spek_detail }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $rkbu->jumlah_usulan_barang }}</td>
                                                    <td>{{ $rkbu->sisa_vol_rkbu }}</td>
                                                    <td>
                                                        Total Usulan:
                                                        Rp.{{ number_format($rkbu->total_anggaran_usulan_barang, 0, ',', '.') }}<br>
                                                        <span class="label label-danger">
                                                            Sisa Anggaran:
                                                            Rp.{{ number_format($rkbu->sisa_anggaran_rkbu, 0, ',', '.') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" form="cartForm{{ $index }}" class="btn btn-primary">Submit</button>
                </div>

            </div>
        </div>
    </div>
@endforeach

<script>
    @foreach ($id_usulan as $index => $yek)
        document.getElementById('checkAll{{ $index }}').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.check-item{{ $index }}');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    @endforeach
</script>
