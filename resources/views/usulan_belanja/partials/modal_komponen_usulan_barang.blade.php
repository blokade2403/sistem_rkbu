@foreach ($usulan_barangs as $index => $yek)
    <div class="modal fade" id="large-Modal{{ $index }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl custom-modal" role="document"> {{-- ganti modal-xl --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Komponen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
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
                                                        <span class="f-12">Spesifikasi: {{ $rkbu->spek }}</span>
                                                    </td>
                                                    <td>{{ $rkbu->jumlah_vol }}</td>
                                                    <td>{{ $rkbu->sisa_vol_rkbu }}</td>
                                                    <td>
                                                        <span class="fa-14 text-secondary">
                                                            Harga Satuan:
                                                            Rp.{{ number_format($rkbu->harga_satuan, 0, ',', '.') }}
                                                        </span><br />
                                                        <span class="fa-14 text-secondary">
                                                            Total Anggaran:
                                                            Rp.{{ number_format($rkbu->total_anggaran, 0, ',', '.') }}
                                                        </span><br />
                                                        <span class="text-danger">
                                                            Sisa Anggaran:
                                                            Rp.{{ number_format($rkbu->sisa_anggaran_rkbu, 0, ',', '.') }}
                                                        </span>
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
                                                <th>Detail</th>
                                            </tr>
                                        </tfoot>
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
