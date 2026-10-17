<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PositionAssignmentController extends Controller
{
    public function index()
    {
        $assignments = PositionAssignment::query()
            ->with(['position', 'user'])
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('created_at')
            ->get();
        $positions = Position::query()
            ->orderBy('nama_jabatan')
            ->get();
        $users = User::query()
            ->where('status_user', 'aktif')
            ->orderBy('nama')
            ->get();

        return view('master_backend.setting_users.position_assignments.index', [
            'assignments' => $assignments,
            'positions' => $positions,
            'users' => $users,
            'title' => 'Penugasan Pejabat',
            'title2' => 'Penugasan Pejabat',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        DB::transaction(function () use ($validated) {
            $this->deactivateCurrentAssignment($validated);
            PositionAssignment::create($validated);
        });

        return redirect()
            ->route('position_assignments.index')
            ->with('success', 'Penugasan pejabat berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $assignment = PositionAssignment::findOrFail($id);
        $validated = $this->validatedData($request);

        DB::transaction(function () use ($assignment, $validated) {
            $this->deactivateCurrentAssignment($validated, $assignment->id);
            $assignment->update($validated);
        });

        return redirect()
            ->route('position_assignments.index')
            ->with('success', 'Penugasan pejabat berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $assignment = PositionAssignment::findOrFail($id);
        $assignment->update(['is_active' => false]);

        return redirect()
            ->route('position_assignments.index')
            ->with('success', 'Penugasan pejabat berhasil dinonaktifkan.');
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'position_id' => ['required', 'uuid', 'exists:positions,id'],
            'user_id' => ['required', 'uuid', Rule::exists('users', 'id')],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($validated['is_active'] && ! User::query()
            ->whereKey($validated['user_id'])
            ->where('status_user', 'aktif')
            ->exists()) {
            throw ValidationException::withMessages([
                'user_id' => 'Pejabat yang ditugaskan harus memiliki status user aktif.',
            ]);
        }

        return $validated;
    }

    private function deactivateCurrentAssignment(array $data, ?string $exceptId = null): void
    {
        if (! $data['is_active'] || $data['tanggal_mulai'] > today()->toDateString()) {
            return;
        }

        $query = PositionAssignment::query()
            ->where('position_id', $data['position_id'])
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', today())
            ->where(function ($query) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', today());
            });

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        $query->update(['is_active' => false]);
    }
}
