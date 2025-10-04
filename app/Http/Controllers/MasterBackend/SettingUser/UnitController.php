<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingUser\Unit;

class UnitController extends Controller
{
    public function index()
    {
        $units  = Unit::all();
        return view('master_backend.setting_users.unit.index', [
            'units'         => $units,
            'title'         => 'Halaman Unit',
            'title2'        => 'Unit',
            'routePrefix'   => 'units'
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_users.unit.create', [
            'title'         => 'Halaman Unit',
            'title2'        => 'Unit',
            'routePrefix'   => 'units'
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_unit' => 'required'],
            ['nama_unit' => 'Nama Unit belum terisi']
        );

        if ($validator->fails()) {
            $errors = $validator->error();

            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $unit = new Unit(['nama_unit'     => $request->nama_unit]);

        $unit->save();
        return redirect()->route('units.index')->with('success', 'KSP berhasil ditambahkan.');
    }

    public function show(Unit $unit)
    {
        //
    }

    public function edit(Unit $unit)
    {
        return view('master_backend.setting_users.unit.edit', [
            'units'         => $unit,
            'title'         => 'Halaman Unit',
            'title2'        => 'Unit',
            'routePrefix'   => 'units'
        ]);
    }

    public function update(Request $request, Unit $unit)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_unit' => 'required'],
            ['nama_unit' => 'Nama Unit belum terisi']
        );

        if ($validator->fails()) {
            $errors = $validator->error();

            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $unit->update($request->all());
        return redirect()->route('units.index')->with('success', 'Data Unit berhasil Di Update.');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Data Berhasil di Hapus');
    }
}
