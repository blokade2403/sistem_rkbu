<?php

namespace App\Http\Controllers\UsulanBelanja;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsulanBelanja\UsulanDetailExport;

class ExportUsulanBarangDetail extends Controller
{
    public function downloadReportExport($id_usulan_barang)
    {
        // Validasi parameter
        if (!$id_usulan_barang) {
            return back()->with('error', 'ID Usulan Barang tidak ditemukan.');
        }

        // Kirim parameter ke kelas export
        return Excel::download(new UsulanDetailExport($id_usulan_barang), 'rkbu_report_usulan_barang.xlsx');
    }
}
