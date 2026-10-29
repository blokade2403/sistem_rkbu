@if (auth()->check() && (auth()->user()->roles->contains(fn ($role) => in_array(strtolower(trim($role->nama_role)), ['administrator', 'super admin'], true)) || (auth()->user()->status_edit === 'aktif' && auth()->user()->roles->contains('nama_role', 'Operator'))))
    <a href="{{ route('rkbu.' . $module . '.template') }}" class="btn btn-sm btn-light-success me-2"><i class="fas fa-download me-1"></i> Template Upload</a>
    <form action="{{ route('rkbu.' . $module . '.import') }}" method="POST" enctype="multipart/form-data" class="d-inline-flex align-items-center gap-2 me-3">
        @csrf
        <input type="file" name="file" accept=".csv,.txt,.xlsx,.xls" class="form-control form-control-sm" required style="max-width: 220px" aria-label="File data RKBU">
        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-upload me-1"></i> Upload</button>
    </form>
@endif
