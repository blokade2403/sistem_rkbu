<?php

namespace App\Pdf;

use TCPDF;

class MYPDF extends TCPDF
{

    //Page header
    public function Header()
    {
        // Logo
        // $image_file = K_PATH_IMAGES . 'logo_example.jpg';
        // $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'T', 0, '', 0, false, 'T', 'M');
    }

    public function PageBreak($requiredSpace)
    {
        $maxHeight = $this->getPageHeight() - PDF_MARGIN_BOTTOM - $this->getY(); // Calculate available space
        if ($maxHeight < $requiredSpace) { // If not enough space for the required content
            $this->AddPage(); // Add a new page
        }
    }
}



$judul = '';
$resolusi = array(250, 355);
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf = new Pdf('L', 'inch', $resolusi, true, 'UTF-8', true);
$pdf->SetTitle('RKBU Barang dan Jasa ' . $judul . '');
$pdf->SetMargins(15, 15, 15);
$pdf->SetTopMargin(15);
$pdf->setFooterMargin(15);
$pdf->SetAutoPageBreak(True, 55);
$pdf->SetAuthor('Author');
$pdf->SetDisplayMode('real', 'default');

$pdf->SetHeaderData('', '', 'PDF_HEADER_TITLE', '');

// Atur margin bagian bawah
$bottomMargin = 12;
$pdf->SetMargins(15, 15, $bottomMargin);

// Atur margin footer
$pdf->setPrintFooter(true);
$pdf->setFooterMargin(10);

// Atur font untuk footer
$pdf->setFooterFont(array('', '', '7'));

$pdf->SetAuthor('Author');
$pdf->SetDisplayMode('real', 'default');

$pdf->SetFont('', '', 7);
$resolusi = array(250, 355);
$pdf->AddPage('P', $resolusi);
