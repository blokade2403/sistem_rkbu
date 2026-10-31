<nav class="d-flex flex-wrap gap-2 mb-6" aria-label="Menu manajemen persediaan">
    <a href="{{ route('persediaan.inventory.index') }}" class="btn {{ $page === 'overview' ? 'btn-primary' : 'btn-light' }}">Ringkasan</a>
    @if ($canManage)<a href="{{ route('persediaan.inventory.barang-masuk') }}" class="btn {{ $page === 'barang_masuk' ? 'btn-primary' : 'btn-light' }}">Barang Masuk</a>@endif
    <a href="{{ route('persediaan.inventory.stok') }}" class="btn {{ $page === 'stok' ? 'btn-primary' : 'btn-light' }}">Stok Barang</a>
    <a href="{{ route('persediaan.inventory.permintaan') }}" class="btn {{ $page === 'permintaan' ? 'btn-primary' : 'btn-light' }}">Permintaan Barang</a>
    @if ($canManage)<a href="{{ route('persediaan.inventory.opname') }}" class="btn {{ $page === 'opname' ? 'btn-primary' : 'btn-light' }}">Stok Opname</a>@endif
    <a href="{{ route('persediaan.inventory.laporan') }}" class="btn {{ $page === 'laporan' ? 'btn-primary' : 'btn-light' }}">Laporan Mutasi</a>
</nav>
