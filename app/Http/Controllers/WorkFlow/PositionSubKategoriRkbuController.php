<?php

namespace App\Http\Controllers\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\WorkFlow\PositionSubKategoriRkbu;
use Illuminate\Http\Request;

class PositionSubKategoriRkbuController extends Controller
{
    public function index()
    {
        $data = PositionSubKategoriRkbu::with(['position', 'subKategoriRkbu'])->get();

        $positions = Position::all();

        $subKategoriRkbus = SubKategoriRkbu::all();

        return view(
            'master_backend.workflow_setting.position_sub_kategori_rkbu.index',
            compact('data', 'positions', 'subKategoriRkbus'),
            ['title' => 'Position Sub Kategori RKBU - Workflow Setting'],
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'position_id' => 'required',
            'id_sub_kategori_rkbu' => 'required',
        ]);

        PositionSubKategoriRkbu::create([
            'position_id' => $request->position_id,
            'id_sub_kategori_rkbu' => $request->id_sub_kategori_rkbu,
        ]);

        return back()->with('success', 'Mapping berhasil ditambahkan');
    }

    public function destroy(PositionSubKategoriRkbu $positionSubKategoriRkbu)
    {
        $positionSubKategoriRkbu->delete();

        return back()->with('success', 'Mapping berhasil dihapus');
    }
}
