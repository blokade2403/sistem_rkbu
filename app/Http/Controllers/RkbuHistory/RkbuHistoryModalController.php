<?php

namespace App\Http\Controllers\RkbuHistory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RkbuHistory\RkbuHistory;

class RkbuHistoryModalController extends Controller
{
    public function index()
    {
        // Menggunakan array untuk menyimpan ID
        $modal = [
            '9cf70e1d-18e7-40fe-bdd3-b7dabf61877d',
            '9cf70e31-9b9e-4dea-8b39-5459f23f3f51',
            '9cf70e44-a25e-462e-8bce-6fd930a91c0b'
        ];

        $tahunAnggaran = session('tahun_anggaran');

        $rkbu_history = RkbuHistory::whereIn('id_jenis_kategori_rkbu', $modal)
            ->get()
            ->filter(function ($item) use ($tahunAnggaran) {
                $json = json_decode($item->data_sebelum, true);
                return $json['nama_tahun_anggaran'] == $tahunAnggaran;
            });


        // Mengirimkan data ke view
        return view('rkbu.history.index', compact('rkbu_history'));
    }
}
