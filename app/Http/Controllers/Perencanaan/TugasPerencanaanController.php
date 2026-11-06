<?php

namespace App\Http\Controllers\Perencanaan;

use App\Http\Controllers\Controller;
use App\Models\TugasPerencanaan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TugasPerencanaanController extends Controller
{
    public function index()
    {
        $tasks = TugasPerencanaan::orderBy('dateline_tugas')->get();
        $now = now();
        $urgentTasks = $tasks->filter(fn ($task) => !$task->tanggal_selesai && $task->dateline_tugas->lte($now->copy()->addDay()));
        $month = request()->query('month', now()->format('Y-m'));
        try {
            $calendarMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable $e) {
            $calendarMonth = now()->startOfMonth();
        }

        return view('perencanaan.tugas.index', compact('tasks', 'urgentTasks', 'calendarMonth'));
    }

    public function report(string $month)
    {
        $data = $this->reportData($month);

        return view('perencanaan.tugas.report', $data + ['pdfMode' => false]);
    }

    public function downloadReport(string $month)
    {
        $data = $this->reportData($month);

        return Pdf::loadView('perencanaan.tugas.report', $data + ['pdfMode' => true])
            ->setPaper('a4', 'portrait')
            ->download('laporan-tugas-perencanaan-' . $data['user']->username . '-' . $data['period']->format('Y-m') . '.pdf');
    }

    private function reportData(string $month): array
    {
        abort_unless(preg_match('/^\\d{4}-(0[1-9]|1[0-2])$/', $month), 404);

        $period = Carbon::createFromFormat('!Y-m', $month)->locale('id');
        // Gunakan jabatan profil: activeAssignment bisa menunjuk penugasan lintas fungsi
        // dan tidak selalu merupakan hierarki role Perencana.
        $user = request()->user()->load('position.parent');
        $position = $user->position;
        $supervisorPosition = $position?->parent;
        $supervisor = null;
        while ($supervisorPosition && ! $supervisor) {
            $assignment = $supervisorPosition->assignments()
                ->with('user')
                ->where('is_active', true)
                ->whereDate('tanggal_mulai', '<=', today())
                ->where(fn ($query) => $query->whereNull('tanggal_selesai')->orWhereDate('tanggal_selesai', '>=', today()))
                ->first();
            $supervisor = $assignment?->user
                ?? $supervisorPosition->users()->where('status_user', 'aktif')->first();
            if ($supervisor) {
                break;
            }
            $supervisorPosition = $supervisorPosition->parent;
        }

        $tasks = TugasPerencanaan::query()
            ->where('created_by', $user->id)
            ->whereBetween('tanggal_informasi', [$period->copy()->startOfMonth()->toDateString(), $period->copy()->endOfMonth()->toDateString()])
            ->orderBy('tanggal_informasi')->orderBy('dateline_tugas')->get();

        return compact('tasks', 'period', 'user', 'position', 'supervisor', 'supervisorPosition');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['created_by'] = $request->user()->id;
        TugasPerencanaan::create($data);

        return redirect()->route('perencanaan.tugas.index')->with('success', 'Tugas perencanaan berhasil ditambahkan.');
    }

    public function update(Request $request, TugasPerencanaan $task)
    {
        $task->update($this->validated($request));
        return redirect()->route('perencanaan.tugas.index')->with('success', 'Tugas perencanaan berhasil diperbarui.');
    }

    public function destroy(TugasPerencanaan $task)
    {
        $task->delete();
        return redirect()->route('perencanaan.tugas.index')->with('success', 'Tugas perencanaan dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'tanggal_informasi' => ['required', 'date'],
            'informasi_tugas' => ['required', 'string', 'max:5000'],
            'tugas_dari' => ['required', 'string', 'max:150'],
            'dateline_tugas' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_informasi'],
            'link_tugas' => ['nullable', 'url', 'max:2048'],
            'keterangan' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
