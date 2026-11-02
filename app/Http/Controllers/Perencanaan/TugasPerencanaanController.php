<?php

namespace App\Http\Controllers\Perencanaan;

use App\Http\Controllers\Controller;
use App\Models\TugasPerencanaan;
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
