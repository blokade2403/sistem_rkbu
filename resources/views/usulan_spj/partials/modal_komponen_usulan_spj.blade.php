@foreach ($id_usulan as $index => $yek)
    <div class="modal fade" id="large-Modal{{ $index }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl custom-modal" role="document"> {{-- ganti modal-xl --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Komponen Usulan Barang</h4>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
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
                                                <th>Nama Barang dan Spek</th>
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
                                                        <strong>{{ $rkbu->nama_barang }}</strong></br>
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
