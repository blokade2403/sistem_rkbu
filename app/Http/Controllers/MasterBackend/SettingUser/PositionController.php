<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\SettingUser\Position;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('parent', 'unit')->orderBy('nama_jabatan')->get();
        $units = Unit::all();
        return view('master_backend.setting_users.positions.index', compact('positions', 'units'), [
            'routePrefix' => 'positions',
            'title' => 'Halaman Jabatan',
            'title2' => 'Jabatan',
        ]);
    }

    public function create()
    {
        $parents = Position::orderBy('nama_jabatan')->get();
        $units = Unit::orderBy('nama_unit')->get();
        return view('master_backend.setting_users.positions.create', compact('parents', 'units'), [
            'routePrefix' => 'positions',
            'title' => 'Halaman Jabatan',
            'title2' => 'Jabatan',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'level_jabatan' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'parent_id' => 'nullable|exists:positions,id',
        ]);

        try {
            Position::create([
                'id' => Str::uuid(),
                'nama_jabatan' => $request->nama_jabatan,
                'level_jabatan' => $request->level_jabatan,
                'parent_id' => $request->parent_id,
                'unit_id' => $request->unit_id,
            ]);

            return redirect()
                ->route('positions.index')
                ->with('success', 'Jabatan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('positions.index')->with('error', 'Data gagal ditambahkan');
        }
    }

    public function edit($id)
    {
        $positions = Position::findOrFail($id);
        $parents = Position::where('id', '!=', $id)->get();
        $units = Unit::orderBy('nama_unit')->get();

        return view(
            'master_backend.setting_users.positions.edit',
            compact('positions', 'parents', 'units'),
            [
                'routePrefix' => 'positions',
                'title' => 'Halaman Jabatan',
                'title2' => 'Jabatan',
            ],
        );
    }

    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'level_jabatan' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:positions,id',
        ]);

        // Hindari parent = dirinya sendiri
        if ($request->parent_id == $id) {
            return back()->with('error', 'Parent tidak boleh dirinya sendiri');
        }

        $position->update([
            'nama_jabatan' => $request->nama_jabatan,
            'level_jabatan' => $request->level_jabatan,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil diupdate');
    }

    public function destroy($id)
    {
        try {
            $position = Position::findOrFail($id);
            $position->delete();

            return redirect()->route('positions.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('positions.index')->with('error', 'Data gagal dihapus');
        }
    }
}
