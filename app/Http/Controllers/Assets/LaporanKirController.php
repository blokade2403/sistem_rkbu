<?php

namespace App\Http\Controllers\Assets;

use App\Pdf\MYPDF;
use App\Models\Asset\Asset;
use Illuminate\Http\Request;
use App\Models\Asset\Penempatan;
use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingUser\PejabatPengadaan;

class LaporanKirController extends Controller
{
    public function index()
    {
        $assetsGrouped = Asset::whereNotNull('id_penempatan')
            ->selectRaw('id_penempatan, COUNT(*) as total_assets')
            ->groupBy('id_penempatan')
            ->with('penempatan')
            ->get();

        $judulHeaders = JudulHeader::first();

        $totalAsset         = Asset::count();

        $totalBaik          = Asset::where('kondisi_asset', 'Baik')->count();
        $totalRusakRingan   = Asset::where('kondisi_asset', 'Rusak Ringan')->count();
        $totalRusakBerat    = Asset::where('kondisi_asset', 'Rusak Berat')->count();
        $totalRusak         = $totalRusakRingan + $totalRusakBerat;

        return view('asset.laporan_kir.index', compact(
            'assetsGrouped',
            'judulHeaders',
            'totalAsset',
            'totalBaik',
            'totalRusakRingan',
            'totalRusakBerat',
            'totalRusak',
        ));
    }

    public function print($id_penempatan)
    {

        // Ambil data penempatan dan aset berdasarkan id_penempatan
        $penempatan = Penempatan::where('id_penempatan', $id_penempatan)->firstOrFail();
        $assets = Asset::where('id_penempatan', $id_penempatan)->get();
        $judulHeaders = JudulHeader::first();

        $first_direktur = PejabatPengadaan::first();

        $nama_direktur      = '...............';
        $nip_direktur       = '...............';
        $nama_pb            = '...............';
        $nip_pb             = '...............';

        if ($first_direktur) {
            // Ambil nama valid perencana, kabag, dan direktur
            $nama_direktur      = $first_direktur->nama_direktur ?? '...............';
            $nip_direktur       = $first_direktur->nip_direktur ?? '...............';
            $nama_pb            = $first_direktur->nama_pengurus_barang ?? '...............';
            $nip_pb             = $first_direktur->nip_pengurus_barang ?? '...............';
        }

        $pdf = new MYPDF();
        // Set informasi dokumen
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nama Penulis');
        $pdf->SetTitle('Laporan KIR');
        $pdf->SetSubject('Laporan KIR');
        $pdf->SetKeywords('TCPDF, PDF, KIR, example');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->SetFont('helvetica', '', 7); // Set font utama

        $pdf->SetCompression(true);
        // Mengatur margin halaman dan auto page break
        $pdf->SetMargins(15, 10, 15);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(true, 25); // Auto page break dengan margin bawah
        $pdf->setCellPaddings(1, 1, 1, 1); // Padding cell

        $judulHeaders = JudulHeader::first();

        // Cek apakah gambar1 ada, jika tidak gunakan gambar alias
        $gambar1 = $judulHeaders->gambar1 ? asset('storage/uploads/' . basename($judulHeaders->gambar1)) : asset('storage/uploads/foto.png');

        // Cek apakah gambar2 ada, jika tidak gunakan gambar alias
        $gambar2 = $judulHeaders->gambar2 ? asset('storage/uploads/' . basename($judulHeaders->gambar2)) : asset('storage/uploads/foto.png');
        // Buat konten HTML untuk PDF (border tabel diatur ke 0.5)
        $pdf->AddPage('L');
        $html = '<table lines="none" bgcolor="white">
                    <tr bgcolor="#ffffff">
                            <th  width="10%" align="left"><h4></h4>
                            </th> 
                            <th  width="80%" align="center">
                                    <h3 align="center" style="font-size:11px; font-weight: Bold; line-height:1;">KARTU INVENTARIS RUANGAN (KIR)</h3>
                                    <h3 style="font-weight: Bold; font-size:11px; line-height:0;" align="center">BERUPA PERALATAN DAN MESIN</h3>
                            </th>
                            <th width="10%" align="right"><h4></h4>
                                    <h6 align="center"><img src="" width="75" height="45"  ></h6>
                            </th> 
                    </tr>
                </table>
                <table lines="none" bgcolor="white">
                    <tr bgcolor="#ffffff">
                            <th  width="10%" align="left"><h4></h4>
                            </th> 
                    </tr>
                </table>';

        // Tambahkan header ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Cek dan tambahkan halaman baru jika diperlukan sebelum menulis konten tabel
        $requiredSpace = 50; // Tentukan tinggi yang diperlukan untuk elemen (misalnya tabel)
        $this->checkPageBreak($pdf, $requiredSpace);

        $htmlBody = view('asset.laporan_kir.print', compact('penempatan', 'judulHeaders', 'assets'))->render();

        // Tuliskan konten body ke PDF
        $pdf->writeHTML($htmlBody, true, false, true, false, '');

        checkPageBreak($pdf, 50); // 50 untuk memastikan ruang yang cukup untuk tanda tangan
        $htmlSignatureTable = '
            <table rules="none">
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center"></th>
                    <th width="45%" align="center"></th>
                    <th width="30%" align="center">Jakarta, ' . date('j F Y') . '</th>  
                </tr>
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center">Pengurus Barang Pengguna atau Pengurus Barang Pembantu</th>
                    <th width="45%" align="center"></th>
                   <th width="30%" align="center">Direktur ' . ucwords(strtolower($judulHeaders->nama_rs)) . '</th>
                </tr>
                <p></p>
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center">' . $nama_pb . '</th>
                    <th width="45%" align="center"></th>
                    <th width="30%" align="center">' . $nama_direktur . '</th> 
                </tr>
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center">' . $nip_pb . '</th>
                    <th width="45%" align="center"></th>
                    <th width="30%" align="center">' . $nip_direktur . '</th> 
                </tr>
            </table>';

        $pdf->writeHTML($htmlSignatureTable, true, false, true, false, '');

        // Tampilkan PDF sebagai preview di browser
        $pdf->Output('rkbu_data_preview.pdf', 'I');
    }

    function CheckPageBreak($pdf, $height)
    {
        // Get the current position
        $currentY = $pdf->GetY();

        // Get the height of the page
        $pageHeight = $pdf->getPageHeight();

        // Check if the current Y position + the height of the next row will exceed the page height minus bottom margin
        if ($currentY + $height > $pageHeight - $pdf->getBreakMargin()) {
            // Add a new page if space is insufficient
            $pdf->AddPage();
        }
    }
}
