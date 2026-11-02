@extends('layouts.main')

@section('content')
@php
    $start = $calendarMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
    $end = $calendarMonth->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);
    $calendarDays = [];
    for ($day = $start->copy(); $day->lte($end); $day->addDay()) $calendarDays[] = $day->copy();
    $tasksByDay = $tasks->groupBy(fn ($task) => $task->dateline_tugas->format('Y-m-d'));
    $taskPayload = $tasks->map(fn ($task) => [
        'id' => $task->id,
        'tanggal_informasi' => $task->tanggal_informasi->format('Y-m-d'),
        'informasi_tugas' => $task->informasi_tugas,
        'tugas_dari' => $task->tugas_dari,
        'dateline_tugas' => $task->dateline_tugas->format('Y-m-d\TH:i'),
        'tanggal_selesai' => $task->tanggal_selesai?->format('Y-m-d'),
        'link_tugas' => $task->link_tugas,
        'keterangan' => $task->keterangan,
    ])->values();
@endphp
<div class="task-page">
    <section class="task-hero card border-0 mb-6">
        <div class="card-body p-6 p-lg-8 d-flex flex-wrap justify-content-between align-items-center gap-4">
            <div>
                <span class="badge badge-light-primary mb-3">RUANG KERJA PERENCANA</span>
                <h1 class="fs-2x fw-bolder text-gray-900 mb-2">Tugas Perencanaan</h1>
                <p class="text-gray-600 fs-5 mb-0">Kelola agenda, pantau tenggat, dan pastikan setiap tugas selesai tepat waktu.</p>
            </div>
            <button class="btn btn-primary px-5" type="button" onclick="openTaskForm()"><i class="fas fa-plus me-2"></i>Tambah tugas</button>
        </div>
    </section>

    @if (session('success')) <div class="alert alert-success d-flex align-items-center"><i class="fas fa-check-circle me-3"></i>{{ session('success') }}</div> @endif
    @if ($errors->any()) <div class="alert alert-danger"><div class="fw-bold mb-1">Periksa kembali data tugas.</div><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif

    @if ($urgentTasks->isNotEmpty())
        <div class="alert task-alert d-flex align-items-start gap-3 mb-6">
            <span class="alert-icon"><i class="fas fa-bell"></i></span>
            <div class="flex-grow-1"><div class="fw-bold fs-5 mb-1">{{ $urgentTasks->count() }} tugas perlu perhatian</div><div class="text-gray-700">Dateline kurang dari 1 hari atau sudah terlewati. Segera tindak lanjuti tugas berikut:</div>
                <div class="d-flex flex-wrap gap-2 mt-3">@foreach ($urgentTasks as $task)<a class="urgent-chip" href="#task-{{ $task->id }}">{{ \Illuminate\Support\Str::limit($task->informasi_tugas, 55) }} <span>{{ $task->dateline_tugas->format('d/m H:i') }}</span></a>@endforeach</div>
            </div>
        </div>
    @endif

    <div class="row g-5 mb-6">
        <div class="col-sm-4"><div class="metric-card"><span class="metric-icon bg-light-primary text-primary"><i class="fas fa-list-check"></i></span><div><div class="metric-label">Total tugas</div><div class="metric-value">{{ $tasks->count() }}</div></div></div></div>
        <div class="col-sm-4"><div class="metric-card"><span class="metric-icon bg-light-warning text-warning"><i class="fas fa-hourglass-half"></i></span><div><div class="metric-label">Belum selesai</div><div class="metric-value">{{ $tasks->whereNull('tanggal_selesai')->count() }}</div></div></div></div>
        <div class="col-sm-4"><div class="metric-card"><span class="metric-icon bg-light-success text-success"><i class="fas fa-circle-check"></i></span><div><div class="metric-label">Selesai</div><div class="metric-value">{{ $tasks->whereNotNull('tanggal_selesai')->count() }}</div></div></div></div>
    </div>

    <section class="card task-card border-0 mb-6">
        <div class="card-header border-0 pt-6 pb-2 flex-wrap">
            <div><h2 class="card-title fs-3 fw-bold text-gray-900">Kalender deadline</h2><div class="text-muted mt-1">Agenda berdasarkan tanggal dateline tugas.</div></div>
            <div class="d-flex align-items-center gap-3 ms-auto">
                <a class="btn btn-sm btn-light" href="{{ route('perencanaan.tugas.index', ['month' => $calendarMonth->copy()->subMonth()->format('Y-m')]) }}"><i class="fas fa-chevron-left"></i></a>
                <span class="calendar-month">{{ $calendarMonth->locale('id')->translatedFormat('F Y') }}</span>
                <a class="btn btn-sm btn-light" href="{{ route('perencanaan.tugas.index', ['month' => $calendarMonth->copy()->addMonth()->format('Y-m')]) }}"><i class="fas fa-chevron-right"></i></a>
                <a class="btn btn-sm btn-light-primary" href="{{ route('perencanaan.tugas.index') }}">Hari ini</a>
            </div>
        </div>
        <div class="card-body pt-3">
            <div class="calendar-grid calendar-head">@foreach (['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $weekday)<div>{{ $weekday }}</div>@endforeach</div>
            <div class="calendar-grid">
                @foreach ($calendarDays as $day)
                    @php $dayTasks = $tasksByDay->get($day->format('Y-m-d'), collect()); @endphp
                    <div class="calendar-cell {{ $day->month !== $calendarMonth->month ? 'outside-month' : '' }} {{ $day->isToday() ? 'is-today' : '' }}">
                        <span class="calendar-date">{{ $day->day }}</span>
                        @foreach ($dayTasks->take(2) as $task)
                            <button type="button" class="calendar-event {{ $task->tanggal_selesai ? 'event-done' : ($task->dateline_tugas->lte(now()->addDay()) ? 'event-urgent' : '') }}" onclick="editTask({{ $task->id }})" title="{{ $task->informasi_tugas }}">{{ \Illuminate\Support\Str::limit($task->informasi_tugas, 22) }}</button>
                        @endforeach
                        @if ($dayTasks->count() > 2)<span class="calendar-more">+{{ $dayTasks->count() - 2 }} tugas lainnya</span>@endif
                    </div>
                @endforeach
            </div>
            <div class="calendar-legend"><span><i class="legend-dot urgent"></i>Segera / terlambat</span><span><i class="legend-dot normal"></i>Terjadwal</span><span><i class="legend-dot done"></i>Selesai</span></div>
        </div>
    </section>

    <section class="card task-card border-0">
        <div class="card-header border-0 pt-6 flex-wrap"><div><h2 class="card-title fs-3 fw-bold text-gray-900">Daftar tugas</h2><div class="text-muted mt-1">Informasi dan progres seluruh tugas perencanaan.</div></div><div class="ms-auto"><input id="taskSearch" type="search" class="form-control form-control-sm" placeholder="Cari informasi atau pemberi tugas…"></div></div>
        <div class="table-responsive">
            <table class="table task-table align-middle mb-0" id="taskTable">
                <thead><tr><th>No.</th><th>Tanggal informasi</th><th>Informasi tugas</th><th>Tugas dari</th><th>Dateline</th><th>Status</th><th>Tanggal selesai</th><th>Link / keterangan</th><th></th></tr></thead>
                <tbody>
                    @forelse ($tasks as $index => $task)
                        @php $isUrgent = !$task->tanggal_selesai && $task->dateline_tugas->lte(now()->addDay()); @endphp
                        <tr id="task-{{ $task->id }}" data-search="{{ strtolower($task->informasi_tugas.' '.$task->tugas_dari) }}">
                            <td><span class="row-number">{{ $index + 1 }}</span></td>
                            <td class="text-nowrap">{{ $task->tanggal_informasi->format('d M Y') }}</td>
                            <td><div class="fw-bold text-gray-900">{{ $task->informasi_tugas }}</div>@if($task->keterangan)<div class="text-muted fs-7 mt-1">{{ \Illuminate\Support\Str::limit($task->keterangan, 90) }}</div>@endif</td>
                            <td>{{ $task->tugas_dari }}</td>
                            <td class="text-nowrap"><div class="fw-bold {{ $isUrgent ? 'text-danger' : 'text-gray-800' }}">{{ $task->dateline_tugas->format('d M Y') }}</div><div class="text-muted fs-7">{{ $task->dateline_tugas->format('H:i') }} WIB</div></td>
                            <td>@if($task->tanggal_selesai)<span class="status-pill status-done">Selesai</span>@elseif($isUrgent)<span class="status-pill status-urgent">Perlu perhatian</span>@else<span class="status-pill status-open">Berjalan</span>@endif</td>
                            <td class="text-nowrap">{{ $task->tanggal_selesai?->format('d M Y') ?? '—' }}</td>
                            <td>@if($task->link_tugas)<a class="link-pill" href="{{ $task->link_tugas }}" target="_blank" rel="noopener">Buka link <i class="fas fa-arrow-up-right-from-square ms-1"></i></a>@else<span class="text-muted">—</span>@endif</td>
                            <td class="text-nowrap"><button class="btn btn-sm btn-light-primary" type="button" onclick="editTask({{ $task->id }})" aria-label="Edit tugas"><i class="fas fa-pen"></i></button><form class="d-inline" method="POST" action="{{ route('perencanaan.tugas.destroy', $task) }}" onsubmit="return confirm('Hapus tugas ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light-danger" aria-label="Hapus tugas"><i class="fas fa-trash"></i></button></form></td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="empty-state"><span class="empty-icon"><i class="fas fa-calendar-plus"></i></span><div class="fw-bold fs-4 mt-3">Belum ada tugas perencanaan</div><div class="text-muted mt-1">Tambahkan tugas pertama agar jadwal dan dateline dapat dipantau.</div><button class="btn btn-primary mt-4" onclick="openTaskForm()"><i class="fas fa-plus me-2"></i>Tambah tugas</button></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content task-modal">
        <form id="taskForm" method="POST" action="{{ route('perencanaan.tugas.store') }}">
            @csrf <input type="hidden" id="taskMethod" name="_method" value="POST">
            <div class="modal-header border-0 pb-0"><div><span class="badge badge-light-primary mb-2">AGENDA PERENCANAAN</span><h3 class="modal-title fs-2 fw-bold" id="taskModalTitle">Tambah tugas</h3></div><button type="button" class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal"><i class="fas fa-times"></i></button></div>
            <div class="modal-body pt-5"><div class="row g-4">
                <div class="col-md-6"><label class="form-label required">Tanggal informasi tugas</label><input class="form-control" type="date" name="tanggal_informasi" id="formTanggalInformasi" required></div>
                <div class="col-md-6"><label class="form-label required">Tugas dari</label><input class="form-control" name="tugas_dari" id="formTugasDari" maxlength="150" placeholder="Nama / unit pemberi tugas" required></div>
                <div class="col-12"><label class="form-label required">Informasi tugas</label><textarea class="form-control" name="informasi_tugas" id="formInformasi" rows="3" placeholder="Ringkasan pekerjaan yang perlu dilakukan" required></textarea></div>
                <div class="col-md-6"><label class="form-label required">Dateline tugas</label><input class="form-control" type="datetime-local" name="dateline_tugas" id="formDateline" required></div>
                <div class="col-md-6"><label class="form-label">Tanggal selesai</label><input class="form-control" type="date" name="tanggal_selesai" id="formTanggalSelesai"><div class="form-text">Isi saat tugas telah diselesaikan.</div></div>
                <div class="col-12"><label class="form-label">Link tugas <span class="text-muted fw-normal">(opsional)</span></label><input class="form-control" type="url" name="link_tugas" id="formLink" placeholder="https://"></div>
                <div class="col-12"><label class="form-label">Keterangan</label><textarea class="form-control" name="keterangan" id="formKeterangan" rows="2" placeholder="Catatan tambahan"></textarea></div>
            </div></div>
            <div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary px-5" type="submit"><i class="fas fa-check me-2"></i>Simpan tugas</button></div>
        </form>
    </div></div>
</div>

<style>
.task-hero{border-radius:18px;background:radial-gradient(circle at 90% 0%,rgba(69,132,255,.14),transparent 33%),linear-gradient(115deg,#f2f7ff,#fff 70%);box-shadow:0 8px 30px rgba(31,55,90,.05)}
.task-card{border-radius:16px;box-shadow:0 6px 24px rgba(31,55,90,.055)}
.metric-card{display:flex;align-items:center;gap:16px;padding:20px 22px;height:100%;background:#fff;border-radius:15px;box-shadow:0 6px 24px rgba(31,55,90,.055)}
.metric-icon{display:grid;place-items:center;width:48px;height:48px;border-radius:14px;font-size:19px}.metric-label{color:#7b8495;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.05em}.metric-value{color:#172b4d;font-size:26px;font-weight:800;line-height:1.2;margin-top:3px}
.task-alert{border:1px solid #ffdfad;border-radius:14px;background:linear-gradient(110deg,#fff8e9,#fffdf8);color:#8a4b08}.alert-icon{display:grid;place-items:center;min-width:40px;height:40px;border-radius:12px;background:#fff0ce;color:#e99b1b}.urgent-chip{padding:7px 10px;background:#fff;border:1px solid #f1d5a8;border-radius:9px;color:#654719;font-size:12px;text-decoration:none}.urgent-chip span{font-weight:700;color:#d97706;margin-left:6px}
.calendar-month{min-width:115px;text-align:center;font-weight:800;color:#253858}.calendar-grid{display:grid;grid-template-columns:repeat(7,minmax(0,1fr));border-left:1px solid #edf0f5;border-top:1px solid #edf0f5}.calendar-head{border:0;margin-bottom:7px}.calendar-head div{text-align:center;padding:8px;color:#8690a2;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em}.calendar-cell{min-height:112px;padding:8px;border-right:1px solid #edf0f5;border-bottom:1px solid #edf0f5;background:#fff}.calendar-date{display:inline-grid;place-items:center;width:27px;height:27px;color:#475467;font-weight:700;font-size:12px}.is-today .calendar-date{background:#246bfd;color:white;border-radius:50%}.outside-month{background:#fafbfc}.outside-month .calendar-date{color:#c2c8d2}.calendar-event{display:block;width:100%;border:0;text-align:left;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;margin-top:5px;padding:5px 7px;border-radius:5px;background:#eaf2ff;color:#3163b4;font-size:11px;font-weight:700}.calendar-event.event-urgent{background:#fff0e9;color:#c74826}.calendar-event.event-done{background:#e6f7ef;color:#18835b}.calendar-more{display:block;color:#7b8495;font-size:10px;margin:5px 0 0 4px}.calendar-legend{display:flex;gap:18px;justify-content:flex-end;margin-top:13px;color:#7b8495;font-size:11px}.calendar-legend span{display:flex;align-items:center;gap:6px}.legend-dot{width:8px;height:8px;border-radius:50%;background:#7aa7ee}.legend-dot.urgent{background:#ed815c}.legend-dot.done{background:#4bb58b}
.task-table{min-width:1080px}.task-table thead th{padding:13px 14px;background:#f8faff;color:#7b8495;font-size:10px;text-transform:uppercase;letter-spacing:.055em;border-bottom:1px solid #edf0f5}.task-table tbody td{padding:15px 14px;border-color:#f0f2f6;color:#475467;font-size:12px}.task-table tbody tr:hover{background:#fbfcff}.row-number{display:grid;place-items:center;width:27px;height:27px;background:#f0f4fb;color:#476183;border-radius:8px;font-weight:800}.status-pill{padding:6px 9px;border-radius:20px;font-size:10px;font-weight:800;white-space:nowrap}.status-done{background:#e8f7ef;color:#17845a}.status-open{background:#eaf2ff;color:#3163b4}.status-urgent{background:#fff0e9;color:#c74826}.link-pill{padding:6px 9px;border-radius:7px;background:#f0f5ff;color:#3163b4;text-decoration:none;font-weight:700;font-size:11px;white-space:nowrap}.empty-state{text-align:center;padding:60px 20px!important}.empty-icon{display:grid;place-items:center;width:58px;height:58px;margin:auto;border-radius:18px;background:#eff5ff;color:#4c7ddd;font-size:22px}.task-modal{border:0;border-radius:18px;box-shadow:0 20px 70px rgba(20,35,60,.2)}.form-control{border-radius:9px}.form-label{font-weight:700;color:#475467;font-size:12px}.form-text{font-size:11px}
.task-table thead th:first-child,.task-table tbody td:first-child{padding-left:24px}.task-table thead th:last-child,.task-table tbody td:last-child{padding-right:24px}
@media(max-width:767px){.calendar-cell{min-height:78px;padding:4px}.calendar-event{font-size:0;height:7px;padding:0;border-radius:8px}.calendar-more{display:none}.calendar-legend{justify-content:flex-start;gap:10px;flex-wrap:wrap}.calendar-month{min-width:92px}.task-page .card-header{gap:12px}.task-page #taskSearch{width:100%}}
</style>
@endsection

@push('scripts')
<script>
const planningTasks = @json($taskPayload);
const taskModalElement = document.getElementById('taskModal');
const taskModal = bootstrap.Modal.getOrCreateInstance(taskModalElement);
const taskForm = document.getElementById('taskForm');
const storeTaskUrl = @json(route('perencanaan.tugas.store'));
const updateTaskUrl = @json(route('perencanaan.tugas.update', ['task' => '__TASK__']));
function openTaskForm(){
    taskForm.reset(); taskForm.action = storeTaskUrl; document.getElementById('taskMethod').value = 'POST';
    document.getElementById('taskModalTitle').textContent = 'Tambah tugas';
    const today = new Date();
    document.getElementById('formTanggalInformasi').value = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;
    taskModal.show();
}
function editTask(id){
    const task = planningTasks.find(item => Number(item.id) === Number(id)); if(!task) return;
    taskForm.action = updateTaskUrl.replace('__TASK__', task.id); document.getElementById('taskMethod').value = 'PUT';
    document.getElementById('taskModalTitle').textContent = 'Perbarui tugas';
    document.getElementById('formTanggalInformasi').value = task.tanggal_informasi;
    document.getElementById('formInformasi').value = task.informasi_tugas;
    document.getElementById('formTugasDari').value = task.tugas_dari;
    document.getElementById('formDateline').value = task.dateline_tugas;
    document.getElementById('formTanggalSelesai').value = task.tanggal_selesai || '';
    document.getElementById('formLink').value = task.link_tugas || '';
    document.getElementById('formKeterangan').value = task.keterangan || '';
    taskModal.show();
}
document.getElementById('taskSearch').addEventListener('input', function(){
    const query = this.value.toLowerCase().trim();
    document.querySelectorAll('#taskTable tbody tr[data-search]').forEach(row => row.hidden = !row.dataset.search.includes(query));
});
</script>
@endpush
