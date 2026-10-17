<div class="modal fade" id="kt_modal_new_komponen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded">

            <div class="modal-header">
                <h2>Pilih Komponen Barang</h2>

                <div class="btn btn-sm btn-icon btn-active-color-primary"
                     data-bs-dismiss="modal">

                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="24"
                             height="24"
                             viewBox="0 0 24 24"
                             fill="none">

                            <rect opacity="0.5"
                                  x="6"
                                  y="17.3137"
                                  width="16"
                                  height="2"
                                  rx="1"
                                  transform="rotate(-45 6 17.3137)"
                                  fill="black"/>

                            <rect x="7.41422"
                                  y="6"
                                  width="16"
                                  height="2"
                                  rx="1"
                                  transform="rotate(45 7.41422 6)"
                                  fill="black"/>
                        </svg>
                    </span>

                </div>
            </div>

            <div class="modal-body">

                @include('components.partials.table.showentrysearch')

                <div class="table-responsive">

                    <table id="kt_customers_table"
                           class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">

                        <thead>
                            <tr>
                                <th width="5%">Pilih</th>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Spesifikasi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($komponens as $item)
                            <tr>

                                <td>
                                    <input type="radio"
                                           name="selected_barang"

                                           value="{{ $item->nama_barang }}"

                                           data-kode="{{ $item->kode_barang }}"

                                           data-spek="{{ $item->spek }}"

                                           data-harga="{{ $item->harga_barang }}"

                                           data-satuan="{{ $item->satuan }}">
                                </td>

                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $item->kode_barang }}</td>

                                <td>{{ $item->nama_barang }}</td>

                                <td>{{ $item->satuan }}</td>

                                <td>
                                    {{ number_format($item->harga_barang,0,',','.') }}
                                </td>

                                <td>{{ $item->spek }}</td>

                            </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                    Tutup
                </button>

                <button type="button"
                        class="btn btn-primary"
                        id="btnPilihKomponen">

                    Pilih Komponen

                </button>

            </div>

        </div>
    </div>
</div>