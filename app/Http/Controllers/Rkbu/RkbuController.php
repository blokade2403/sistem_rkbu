<?php

namespace App\Http\Controllers\Rkbu;

use App\DataTables\Rkbu\RkbuDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RkbuController extends Controller
{
    public function index(RkbuDataTable $dataTable)
    {
        return $dataTable->render('rkbu.index', [
            'title' => 'Rencana Kebutuhan Barang Umum (RKBU)',
        ]);
    }
}
