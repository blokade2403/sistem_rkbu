<?php

namespace App\Support\Rkbu;

use App\Models\Rkbu\Rkbu;
use App\Models\Rkbu\RkbuDetailPersediaan;

class PersediaanListPresenter
{
    public static function renderInfo(RkbuDetailPersediaan $detail, ?Rkbu $rkbu): string
    {
        $yearLabel = $rkbu?->tahunAnggaran?->nama_tahun_anggaran ?? session('tahun_anggaran', '');
        $year = (int) preg_replace('/[^0-9]/', '', (string) $yearLabel);
        $year = $year ?: (int) date('Y');

        $metrics = [
            'Stok s/d Desember ' . ($year - 2) => $detail->stok,
            'Rata-rata Pemakaian ' . ($year - 1) => $detail->rata_rata_pemakaian,
            'Kebutuhan per Bulan' => $detail->kebutuhan_per_bulan,
            'Pengadaan ' . ($year - 1) => $detail->pengadaan_sebelumnya,
            'Sisa Volume RKBU' => $detail->sisa_vol_rkbu,
            'Sisa Anggaran RKBU' => $detail->sisa_anggaran_rkbu,
            'Proyeksi Sisa Stok ' . $year => $detail->proyeksi_sisa_stok,
            'Kebutuhan Tahun ' . $year => $detail->kebutuhan_tahun_x1,
            'Buffer' => $detail->buffer,
            'Kebutuhan + Buffer' => $detail->kebutuhan_plus_buffer,
        ];

        $metricRows = collect($metrics)->map(function ($value, $label) {
            $displayValue = $value === null
                ? '-'
                : ($label === 'Sisa Anggaran RKBU'
                    ? 'Rp. ' . number_format((float) $value, 0, ',', '.')
                    : $value);

            return '<div class="d-flex justify-content-between gap-3 py-1">' .
                '<span class="text-gray-600">' . e($label) . '</span>' .
                '<span class="fw-semibold text-gray-800 text-end">' . e($displayValue) . '</span>' .
                '</div>';
        })->implode('');

        $planned = '<div class="d-flex justify-content-between gap-3 border-top mt-1 pt-2">' .
            '<span class="text-danger fw-semibold">Rencana Pengadaan ' . e($year) . '</span>' .
            '<span class="text-danger fw-bold text-end">' . e($detail->rencana_pengadaan_tahun_x1 ?? '-') . '</span>' .
            '</div>';

        $creator = $rkbu?->creator?->nama ?? '-';
        $unit = $rkbu?->unit?->nama_unit ?? '-';
        $rekening = $rkbu?->rekeningBelanja;
        $rekeningLabel = trim(($rekening?->kode_rekening_belanja ?? '') . ' ' . ($rekening?->nama_rekening_belanja ?? ''));

        return '<div class="border rounded-3 bg-light p-3 persediaan-info-card">' .

            ($rekeningLabel !== ''
                ? '<div class="text-gray-800 fs-7 fw-semibold border-top pt-2 mb-2">' . e($rekeningLabel) . '</div>'
                : '') .
            '<div class="fs-8 text-uppercase fw-bold text-muted mb-1">Stok &amp; kebutuhan</div>' .
            $metricRows . $planned .
            '</div>';
    }
}
