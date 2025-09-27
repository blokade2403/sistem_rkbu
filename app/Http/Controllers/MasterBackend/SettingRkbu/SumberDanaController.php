<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\SumberDana;

class SumberDanaController extends Controller
{

    public function index()
    {
        $sumber_danas = SumberDana::all();
        return view('master_backend.setting_rkbu.sumber_dana.index', compact('sumber_danas'), ['title' => 'Halaman Sumber Dana']);
    }

    public function create()
    {
        return view('master_backend.setting_rkbu.sumber_dana.create', ['title' => 'Halaman Sumber Dana']);
    }

    public function store(Request $request, SumberDana $SumberDana)
    {
        $request->validate([
            'nama_sumber_dana'  => 'required',
        ]);

        $SumberDana = new SumberDana([
            'nama_sumber_dana'          => $request->nama_sumber_dana,

        ]);

        // dd($SumberDana);

        $SumberDana->save();

        return redirect()->route('sumber_danas.index')
            ->with('success', 'Sumber Dana updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SumberDana $SumberDana)
    {
        return view('backend.master_setting.sumber_danas.show', compact('SumberDana'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SumberDana $SumberDana)
    {
        return view('master_backend.setting_rkbu.sumber_dana.edit', compact('SumberDana'), ['title' => 'Halaman Sumber Dana']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SumberDana $SumberDana)
    {
        $request->validate([
            'nama_sumber_dana'  => 'required',

        ]);

        // dd($request);

        $SumberDana->update($request->all());

        return redirect()->route('sumber_danas.index')
            ->with('success', 'Sumber Dana updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SumberDana $SumberDana)
    {
        $SumberDana->delete();

        return redirect()->route('sumber_danas.index')
            ->with('success', 'Sumber Dana deleted successfully.');
    }
}
