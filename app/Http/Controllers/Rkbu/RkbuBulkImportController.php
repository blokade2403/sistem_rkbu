<?php

namespace App\Http\Controllers\Rkbu;

use App\Http\Controllers\Controller;
use App\Imports\RkbuPersediaanImport;
use App\Models\MasterBackend\SettingRkbu\KategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\Rkbu\Rkbu;
use App\Models\Rkbu\RkbuDetailBarjas;
use App\Models\Rkbu\RkbuDetailModal;
use App\Models\Rkbu\RkbuDetailModalAlkes;
use App\Models\Rkbu\RkbuDetailPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class RkbuBulkImportController extends Controller
{
    private const FIELDS = [
        'barjas' => ['kode_sub_kategori_rkbu','nama_barang','spesifikasi','volume_1','satuan_1','volume_2','satuan_2','harga_satuan','ppn','rating','link_ekatalog','penempatan'],
        'modal_kantor' => ['kode_sub_kategori_rkbu','nama_barang','spesifikasi','volume_1','satuan_1','volume_2','satuan_2','harga_satuan','ppn','rating','link_ekatalog','penempatan','standar_kebutuhan','eksisting','kondisi_baik','kondisi_rusak_berat'],
        'modal_alkes' => ['kode_sub_kategori_rkbu','nama_barang','spesifikasi','volume_1','satuan_1','volume_2','satuan_2','harga_satuan','ppn','rating','link_ekatalog','penempatan','standar_kebutuhan','eksisting','kondisi_baik','kondisi_rusak_berat'],
        'pegawai' => ['kode_sub_kategori_rkbu','nama_pegawai','nip','bulan','tempat_lahir','tanggal_lahir','pendidikan','jabatan','status_kawin','nomor_kontrak','tmt_pegawai','bulan_tmt','tahun_tmt','gaji_pokok','remunerasi','koefisien_remunerasi','koefisien_gaji','bpjs_kesehatan','bpjs_tk','bpjs_jht'],
    ];

    private function authorizeImport(): void
    {
        $user = auth()->user();
        $allowed = $user && ($user->roles->contains(fn ($role) => in_array(strtolower(trim($role->nama_role)), ['administrator', 'super admin'], true))
            || ($user->status_edit === 'aktif' && $user->roles->contains('nama_role', 'Operator')));
        abort_unless($allowed, 403);
    }

    public function template(string $module)
    {
        $this->authorizeImport();
        abort_unless(isset(self::FIELDS[$module]), 404);
        return response()->streamDownload(function () use ($module) {
            $out = fopen('php://output', 'w');
            fputcsv($out, self::FIELDS[$module]);
            fclose($out);
        }, "template-rkbu-{$module}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function import(Request $request, string $module)
    {
        $this->authorizeImport();
        abort_unless(isset(self::FIELDS[$module]), 404);
        $request->validate(['file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240']);
        $rows = Excel::toCollection(new RkbuPersediaanImport(), $request->file('file'))->first();
        if (!$rows || $rows->count() < 2) return back()->withErrors(['file' => 'File tidak memiliki baris data.']);
        $headers = collect($rows->first())->map(fn ($value) => trim((string) $value))->all();
        if (array_diff(self::FIELDS[$module], $headers)) return back()->withErrors(['file' => 'Kolom template tidak sesuai. Unduh template untuk jenis RKBU ini.']);
        $map = array_flip($headers);
        $subcategoryQuery = SubKategoriRkbu::query()->where('status', 'aktif')->with('kategori.jenis_kategori_rkbu');
        $typeLabel = match ($module) { 'barjas' => 'Barang Jasa', 'modal_kantor' => 'Modal Kantor', 'modal_alkes' => 'Modal Alkes', default => 'Pegawai' };
        $subcategoryQuery->whereHas('kategori.jenis_kategori_rkbu', fn ($query) => $query->where('nama_jenis_kategori_rkbu', 'like', "%{$typeLabel}%"));
        $subcategories = $subcategoryQuery->get()->keyBy('kode_sub_kategori_rkbu');
        $data = [];
        $errors = [];
        foreach ($rows->skip(1) as $i => $row) {
            $r = [];
            foreach ($map as $key => $col) $r[$key] = $row[$col] ?? null;
            if (!collect($r)->filter(fn ($v) => $v !== null && trim((string) $v) !== '')->isNotEmpty()) continue;
            $line = $i + 2;
            $rowErrorCount = count($errors);
            $sub = $subcategories->get(trim((string) ($r['kode_sub_kategori_rkbu'] ?? '')));
            if (!$sub) { $errors[] = "Baris {$line}: kode subkategori tidak ditemukan."; continue; }
            $nameField = $module === 'pegawai' ? 'nama_pegawai' : 'nama_barang';
            $requiredNumbers = $module === 'pegawai' ? ['bulan','koefisien_remunerasi','koefisien_gaji'] : ['volume_1','harga_satuan'];
            if (trim((string) ($r[$nameField] ?? '')) === '') $errors[] = "Baris {$line}: {$nameField} wajib diisi.";
            foreach ($requiredNumbers as $field) if (!is_numeric($r[$field] ?? null) || (float) $r[$field] < ($field === 'bulan' || $field === 'volume_1' ? 1 : 0)) $errors[] = "Baris {$line}: {$field} harus bernilai valid.";
            foreach (['volume_2','gaji_pokok','remunerasi','bpjs_kesehatan','bpjs_tk','bpjs_jht','eksisting','standar_kebutuhan'] as $field) if (isset($r[$field]) && $r[$field] !== '' && (!is_numeric($r[$field]) || (float) $r[$field] < 0)) $errors[] = "Baris {$line}: {$field} harus >= 0.";
            if (count($errors) === $rowErrorCount) $data[] = [$r, $sub];
        }
        if ($errors) return back()->withErrors(['file' => implode(' ', array_slice($errors, 0, 8)) . (count($errors) > 8 ? ' Kesalahan lainnya: ' . (count($errors) - 8) : '')]);
        if (!$data) return back()->withErrors(['file' => 'Tidak ada baris yang dapat diimpor.']);

        DB::transaction(function () use ($data, $module) {
            foreach ($data as [$r, $sub]) {
                $kategori = KategoriRkbu::findOrFail($sub->id_kategori_rkbu);
                $jenisBelanja = JenisBelanja::where('nama_jenis_belanja', 'LIKE', '%Barang%')->firstOrFail();
                $rkbu = Rkbu::create(['nomor_rkbu' => $this->number(), 'tahun_anggaran_id' => Session::get('tahun_anggaran_id'), 'unit_id' => Session::get('unit_id'), 'sumber_dana_id' => SumberDana::query()->firstOrFail()->id_sumber_dana, 'jenis_belanja_id' => $jenisBelanja->id_jenis_belanja, 'jenis_kategori_rkbu_id' => $kategori->id_jenis_kategori_rkbu, 'sub_kategori_rkbu_id' => $sub->id_sub_kategori_rkbu, 'created_by' => auth()->id(), 'status' => 'draft']);
                if ($module === 'pegawai') {
                    $bulan = (float) $r['bulan']; $gaji = (float) ($r['gaji_pokok'] ?: 0); $rem = (float) ($r['remunerasi'] ?: 0); $kg = (float) $r['koefisien_gaji']; $kr = (float) $r['koefisien_remunerasi'];
                    $bpjs = $gaji * $bulan * $kg * ((float) ($r['bpjs_kesehatan'] ?: 0) + (float) ($r['bpjs_tk'] ?: 0) + (float) ($r['bpjs_jht'] ?: 0)) / 100;
                    $totalGaji = $gaji * $bulan * $kg + $bpjs; $totalRem = $rem * $bulan * $kr; $total = $totalGaji + $totalRem;
                    $values = array_intersect_key($r, array_flip(self::FIELDS['pegawai'])); unset($values['kode_sub_kategori_rkbu']);
                    $values = array_merge($values, ['rkbu_id' => $rkbu->id, 'jumlah_volume' => $bulan, 'total_gaji_pokok' => $totalGaji, 'total_remunerasi' => $totalRem, 'total_anggaran' => $total, 'sisa_vol_rkbu' => $bulan, 'sisa_anggaran_rkbu' => $total, 'status_komponen' => 'draft']);
                    RkbuDetailPegawai::create($values);
                } else {
                    $model = match ($module) { 'barjas' => RkbuDetailBarjas::class, 'modal_kantor' => RkbuDetailModal::class, default => RkbuDetailModalAlkes::class };
                    $v1 = (float) $r['volume_1']; $v2 = (float) (($r['volume_2'] ?? '') !== '' ? $r['volume_2'] : 1); $qty = $v1 * $v2; $price = (float) str_replace(',', '', (string) $r['harga_satuan']); $ppn = (float) ($r['ppn'] ?: 0); $total = $qty * $price * (1 + $ppn / 100);
                    $values = array_intersect_key($r, array_flip(self::FIELDS[$module])); unset($values['kode_sub_kategori_rkbu']);
                    $values = array_merge($values, ['rkbu_id' => $rkbu->id, 'volume_1' => $v1, 'volume_2' => $v2, 'jumlah_volume' => $qty, 'harga_satuan' => $price, 'ppn' => $ppn, 'total_anggaran' => $total, 'sisa_vol_rkbu' => $qty, 'sisa_anggaran_rkbu' => $total, 'status_komponen' => 'draft']);
                    $model::create($values);
                }
            }
        });
        return back()->with('success', count($data) . ' data RKBU berhasil diimpor sebagai draft.');
    }

    private function number(): string
    {
        $year = now()->year; $sequence = Rkbu::whereYear('created_at', $year)->count() + 1;
        do { $number = sprintf('RKBU-%s-%04d', $year, $sequence++); } while (Rkbu::where('nomor_rkbu', $number)->exists());
        return $number;
    }
}
