<div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Komponen RKBU</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="page-body">
                    <div class="table-responsive">
                        <div class="dt-responsive table-responsive">
                            <table id="responsive-reorder" class="table table-striped table-bordered wrap"
                                width="100%">
                                <thead>
                                    <tr class="text f-12">
                                        <th style="width: 5%;">Chek</th>
                                        <th>No</th>
                                        <th>Kode Komponen</th>
                                        <th>Nama Barang</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        <th style="width: 70%;">Spek</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($komponens as $item)
                                        <tr>
                                            <td class="f-14">
                                                <div class="checkbox-fade fade-in-primary checkbox">
                                                    <input type="radio" name="selected_barang"
                                                        value="{{ $item->nama_barang }}"
                                                        data-harga="{{ $item->harga_barang }}"
                                                        data-spek="{{ $item->spek }}"
                                                        data-satuan_1="{{ $item->satuan_1 }}" />
                                                </div>
                                            </td>
                                            <td class="f-14"><small>{{ $loop->iteration }}</small></td>
                                            <td class="f-14"><small>{{ $item->kode_barang }}</small></td>
                                            <td class="f-14"><small>{{ $item->nama_barang }}</small></td>
                                            <td class="f-14"><small>{{ $item->satuan }}</small></td>
                                            <td class="f-14">
                                                <small>{{ number_format($item->harga_barang, 0, '.', '.') }}</small>
                                            </td>
                                            <td class="f-14"><small>{{ $item->spek }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Chek</th>
                                        <th>No</th>
                                        <th>Kode Komponen</th>
                                        <th>Nama Barang</th>
                                        <th>Satuan</th>
                                        <th>Spek</th>
                                        <th>Harga</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary waves-effect waves-light " id="pilihBarang">Save
                    changes</button>
            </div>
        </div>
    </div>
</div>
