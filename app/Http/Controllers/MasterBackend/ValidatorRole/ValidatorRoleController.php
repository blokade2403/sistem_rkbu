<?php

namespace App\Http\Controllers\MasterBackend\ValidatorRole;

use App\Http\Controllers\Controller;
use App\Models\ValidatorRole;
use Illuminate\Http\Request;

class ValidatorRoleController extends Controller
{
    public function index()
    {
        $data = ValidatorRole::orderBy('nama_validator')->get();

        return view('master_backend.user_profil.validator_role.index', compact('data'), [
            'title' => 'Validator Role',
        ]);
    }

    public function create()
    {
        return view('master_backend.user_profil.validator_role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_validator' => 'required|unique:validator_roles,kode_validator',
            'nama_validator' => 'required',
        ]);

        // dd($request->all());

        ValidatorRole::create([
            'kode_validator' => strtoupper($request->kode_validator),
            'nama_validator' => $request->nama_validator,
            'is_active' => $request->is_active ?? 1,
        ]);

        return redirect()
            ->route('validator-roles.index')
            ->with('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
        $data = ValidatorRole::findOrFail($id);

        return view('master_backend.user_profil.validator_role.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = ValidatorRole::findOrFail($id);

        $request->validate([
            'kode_validator' => 'required|unique:validator_roles,kode_validator,' . $id,
            'nama_validator' => 'required',
        ]);

        $data->update([
            'kode_validator' => strtoupper($request->kode_validator),
            'nama_validator' => $request->nama_validator,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('validator-roles.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        ValidatorRole::findOrFail($id)->delete();

        return redirect()->route('validator-roles.index')->with('success', 'Data berhasil dihapus');
    }
}
