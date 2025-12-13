<?php

namespace App\Http\Controllers\RkbuHistory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RkbuHistory\RkbuHistory;

class RkbuHistoryBarjasController extends Controller
{
    public function index()
    {
        $tahunAnggaran = session('tahun_anggaran');

        $rkbu_history = RkbuHistory::where('id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->get()
            ->filter(function ($item) use ($tahunAnggaran) {
                $json = json_decode($item->data_sebelum, true);
                return $json['nama_tahun_anggaran'] == $tahunAnggaran;
            });


        return view('rkbu.history.index', compact('rkbu_history'));
    }
}
