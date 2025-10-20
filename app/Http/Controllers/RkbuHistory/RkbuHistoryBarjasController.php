<?php

namespace App\Http\Controllers\RkbuHistory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RkbuHistory\RkbuHistory;

class RkbuHistoryBarjasController extends Controller
{
    public function index()
    {
        $rkbu_history = RkbuHistory::where('id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')->get();
        return view('rkbu.history.index', compact('rkbu_history'));
    }
}
