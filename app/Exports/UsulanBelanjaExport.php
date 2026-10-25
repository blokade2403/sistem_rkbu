<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsulanBelanjaExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private Request $request) {}

    public function query(): Builder
    {
        $user = $this->request->user();
        $isAdministrator = $user->roles()
            ->where('nama_role', 'Administrator')
            ->exists();

        $query = DB::table('usulan_belanja_items as item_usulan')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'item_usulan.usulan_belanja_id')
            ->leftJoin('rkbus as rkbu', 'rkbu.id', '=', 'item_usulan.rkbu_id')
            ->leftJoin('sub_kategori_rkbus as subkategori', 'subkategori.id_sub_kategori_rkbu', '=', 'usulan.sub_kategori_rkbu_id')
            ->leftJoin('units as unit', 'unit.id', '=', 'usulan.unit_id')
            ->leftJoin('tahun_anggarans as tahun', 'tahun.id', '=', 'usulan.tahun_anggaran_id')
            ->leftJoin('rkbu_detail_barjass as barjas', function ($join) {
                $join->on('barjas.id', '=', 'item_usulan.detail_id')->where('item_usulan.detail_type', '=', 'barjas');
            })
            ->leftJoin('rkbu_detail_persediaans as persediaan', function ($join) {
                $join->on('persediaan.id', '=', 'item_usulan.detail_id')->where('item_usulan.detail_type', '=', 'persediaan');
            })
            ->leftJoin('rkbu_detail_modals as modal', function ($join) {
                $join->on('modal.id', '=', 'item_usulan.detail_id')->where('item_usulan.detail_type', '=', 'modal');
            })
            ->leftJoin('rkbu_detail_modal_alkes as alkes', function ($join) {
                $join->on('alkes.id', '=', 'item_usulan.detail_id')->where('item_usulan.detail_type', '=', 'modal_alkes');
            })
            ->leftJoin('rkbu_detail_pegawais as pegawai', function ($join) {
                $join->on('pegawai.id', '=', 'item_usulan.detail_id')->where('item_usulan.detail_type', '=', 'pegawai');
            });

        if (! $isAdministrator) {
            $query->where('usulan.user_id', $user->id);
        }

        $query->where('usulan.tahun_anggaran_id', session('tahun_anggaran_id'));

        if ($this->request->filled('jenis_kategori_rkbu_id')) {
            $query->where('usulan.jenis_kategori_rkbu_id', $this->request->input('jenis_kategori_rkbu_id'));
        }

        if ($this->request->filled('sub_kategori_rkbu_id')) {
            $query->where('usulan.sub_kategori_rkbu_id', $this->request->input('sub_kategori_rkbu_id'));
        }

        $allowedStatuses = ['draft', 'proses', 'revision', 'approved', 'rejected'];
        if (in_array($this->request->input('status'), $allowedStatuses, true)) {
            $query->where('usulan.status_usulan', $this->request->input('status'));
        }

        $search = trim((string) $this->request->input('search', ''));
        if ($search !== '') {
            $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $search) . '%';
            $query->where(function (Builder $searchQuery) use ($like) {
                $searchQuery->where('usulan.no_usulan_belanja', 'like', $like)
                    ->orWhere('rkbu.nomor_rkbu', 'like', $like)
                    ->orWhere('usulan.nama_pengusul', 'like', $like)
                    ->orWhere('subkategori.nama_sub_kategori_rkbu', 'like', $like)
                    ->orWhere('barjas.nama_barang', 'like', $like)
                    ->orWhere('persediaan.nama_barang', 'like', $like)
                    ->orWhere('modal.nama_barang', 'like', $like)
                    ->orWhere('alkes.nama_barang', 'like', $like)
                    ->orWhere('pegawai.nama_pegawai', 'like', $like)
                    ->orWhere('pegawai.jabatan', 'like', $like);
            });
        }

        return $query->select([
            'usulan.no_usulan_belanja',
            'usulan.created_at as tanggal_usulan',
            'usulan.nama_pengusul',
            'unit.nama_unit',
            'tahun.nama_tahun_anggaran',
            'subkategori.kode_sub_kategori_rkbu',
            'subkategori.nama_sub_kategori_rkbu',
            'rkbu.nomor_rkbu',
            'item_usulan.detail_type',
            'item_usulan.harga_satuan',
            'item_usulan.volume',
            'item_usulan.ppn',
            'usulan.status_usulan',
        ])
            ->selectRaw("COALESCE(barjas.nama_barang, persediaan.nama_barang, modal.nama_barang, alkes.nama_barang, pegawai.nama_pegawai, pegawai.jabatan, '-') as nama_item")
            ->selectRaw("COALESCE(NULLIF(item_usulan.spesifikasi, ''), barjas.spesifikasi, persediaan.spesifikasi, modal.spesifikasi, alkes.spesifikasi, CONCAT_WS(' - ', pegawai.jabatan, pegawai.pendidikan), '-') as spesifikasi")
            ->selectRaw('COALESCE(barjas.volume_1, modal.volume_1, alkes.volume_1, 0) as volume_1')
            ->selectRaw("COALESCE(barjas.satuan_1, persediaan.satuan_1, modal.satuan_1, alkes.satuan_1, CASE WHEN item_usulan.detail_type = 'pegawai' THEN 'Bulan' END, '-') as satuan_1")
            ->selectRaw('COALESCE(barjas.volume_2, modal.volume_2, alkes.volume_2, 0) as volume_2')
            ->selectRaw("COALESCE(barjas.satuan_2, modal.satuan_2, alkes.satuan_2, '-') as satuan_2")
            ->selectRaw('COALESCE(barjas.penempatan, persediaan.penempatan, modal.penempatan, alkes.penempatan, \'-\') as penempatan')
            ->selectRaw('COALESCE(barjas.status_komponen, persediaan.status_komponen, modal.status_komponen, alkes.status_komponen, pegawai.status_komponen, \'-\') as status_komponen')
            ->orderBy('usulan.created_at')
            ->orderBy('usulan.no_usulan_belanja')
            ->orderBy('nama_item');
    }

    public function headings(): array
    {
        return [
            'Nomor Usulan',
            'Tanggal Usulan',
            'Nama Pengusul',
            'Unit',
            'Tahun Anggaran',
            'Kode Subkategori RKBU',
            'Nama Subkategori RKBU',
            'Nomor RKBU',
            'Jenis Item',
            'Nama Item',
            'Spesifikasi',
            'Volume 1',
            'Satuan 1',
            'Volume 2',
            'Satuan 2',
            'Volume Usulan',
            'Harga Satuan',
            'PPN (%)',
            'Total Anggaran',
            'Nilai PPN',
            'Total + PPN',
            'Penempatan',
            'Status Komponen',
            'Status Usulan',
        ];
    }

    public function map($row): array
    {
        $total = (float) $row->harga_satuan * (float) $row->volume;
        $tax = $total * (float) $row->ppn / 100;

        return [
            $row->no_usulan_belanja,
            $row->tanggal_usulan,
            $row->nama_pengusul,
            $row->nama_unit,
            $row->nama_tahun_anggaran,
            $row->kode_sub_kategori_rkbu,
            $row->nama_sub_kategori_rkbu,
            $row->nomor_rkbu,
            ucfirst(str_replace('_', ' ', $row->detail_type)),
            $row->nama_item,
            $row->spesifikasi,
            $row->volume_1,
            $row->satuan_1,
            $row->volume_2,
            $row->satuan_2,
            (float) $row->volume,
            (float) $row->harga_satuan,
            (float) $row->ppn,
            $total,
            $tax,
            $total + $tax,
            $row->penempatan,
            $row->status_komponen,
            ucfirst((string) $row->status_usulan),
        ];
    }
}
