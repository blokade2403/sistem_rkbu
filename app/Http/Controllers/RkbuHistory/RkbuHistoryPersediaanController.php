<?php

namespace App\Http\Controllers\RkbuHistory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RkbuHistory\RkbuHistory;

class RkbuHistoryPersediaanController extends Controller
{
    public function index()
    {
        $rkbu_history = RkbuHistory::where('id_jenis_kategori_rkbu', '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c')->get();
        return view('rkbu.history.index', compact('rkbu_history'));
    }
}
