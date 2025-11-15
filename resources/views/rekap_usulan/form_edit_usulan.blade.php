<div class="card border card-border-danger card ribbon-box border mb-lg-0 right">
    <div class="card-header">
        <div class="d-flex">
            <h5 class="card-title flex-grow-1 mb-0">Ubah Tanggal Usulan</h5>

        </div>
        <div class="ribbon ribbon-danger ribbon-shape">Tanggal Usulan</div>
        <hr>
        <form action="{{ route($routePrefix . '.updateValidasi', $no_inv->no_usulan_barang) }}" method="POST"
            id="confirmUpdateForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" value="{{ $no_inv->no_usulan_barang }}" name="no_usulan_barang">
            {{-- Status Validasi --}}
            <div class="col-lg-12 mb-3">
                <label class="form-label">Tanggal Usulan Barang</label>
                <input type="datetime-local" name="created_at"
                    value="{{ \Carbon\Carbon::parse($no_inv->created_at)->format('Y-m-d\TH:i') }}" class="form-control"
                    aria-label="Amount (to the nearest dollar)" />
            </div>

            {{-- Tombol --}}
            <div class="col-lg-12">
                <button class="btn btn-primary" type="submit">Submit form</button>
            </div>
        </form>
    </div>
</div><!--end card-->

<div class="card">

</div>
