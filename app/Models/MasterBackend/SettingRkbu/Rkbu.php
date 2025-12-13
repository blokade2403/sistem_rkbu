<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\Ksp;
use App\Models\MasterBackend\UserProfil\User;
use App\Imports\SettingRkbu\RkbuPersediaanImport;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingInput\StatusValidasi;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;
use App\Models\MasterBackend\SettingBelanja\UsulanBarangDetail;

class Rkbu extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey       = 'id_rkbu';
    public $incrementing        = false;        // kalau UUID
    protected $fillable         =
    [
        'id_sub_kategori_rekening',
        'id_sub_kategori_rkbu',
        'id_sub_kegiatan',
        'id_status_validasi',
        'id_status_validasi_rka',
        'id_kode_rekening_belanja',
        'id_user',
        'nama_barang',
        'vol_1',
        'satuan_1',
        'vol_2',
        'satuan_2',
        'spek',
        'jumlah_vol',
        'harga_satuan',
        'ppn',
        'total_anggaran',
        'rating',
        'nama_tahun_anggaran',
        'link_ekatalog',
        'upload_file_1',
        'upload_file_2',
        'upload_file_3',
        'upload_file_4',
        'keterangan_status',
        'penempatan',
        'stok',
        'rata_rata_pemakaian',
        'kebutuhan_per_bulan',
        'buffer',
        'pengadaan_sebelumnya',
        'proyeksi_sisa_stok',
        'kebutuhan_plus_buffer',
        'kebutuhan_tahun_x1',
        'rencana_pengadaan_tahun_x1',
        'nama_pegawai',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan',
        'jabatan',
        'status_kawin',
        'nomor_kontrak',
        'tmt_pegawai',
        'bulan_tmt',
        'gaji_pokok',
        'remunerasi',
        'koefisien_remunerasi',
        'koefisien_gaji',
        'bpjs_kesehatan',
        'bpjs_tk',
        'bpjs_jht',
        'total_gaji_pokok',
        'total_remunerasi',
        'sisa_vol_rkbu',
        'sisa_anggaran_rkbu',
        'status_komponen',
        'standar_kebutuhan',
        'eksisting',
        'kondisi_baik',
        'kondisi_rusak_berat',
    ];

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        Excel::import(new RkbuPersediaanImport, $request->file('file'));

        return back()->with('success', 'Data berhasil diimport!');
    }

    public function rekening_belanjas()
    {
        return $this->belongsTo(RekeningBelanja::class, 'id_kode_rekening_belanja', 'id_kode_rekening_belanja');
    }

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'id_aktivitas');
    }

    public function sub_kegiatans()
    {
        return $this->belongsTo(SubKegiatan::class, 'id_sub_kegiatan');
    }

    public function subKegiatan()
    {
        return $this->belongsTo(SubKegiatan::class, 'id_sub_kegiatan', 'id_sub_kegiatan');
    }

    public function kegiatans()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    public function status_validasi_rka()
    {
        return $this->belongsTo(StatusValidasiRka::class, 'id_status_validasi_rka');
    }

    public function status_validasi()
    {
        return $this->belongsTo(StatusValidasi::class, 'id_status_validasi');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'id_program');
    }

    public function sumber_dana()
    {
        return $this->belongsTo(SumberDana::class, 'id_sumber_dana');
    }

    public function subKategoriRkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rkbu');
    }

    // Relasi tidak langsung ke KategoriRkbu melalui SubKategoriRkbu
    public function kategoriRkbu()
    {
        return $this->hasOneThrough(
            KategoriRkbu::class,
            SubKategoriRkbu::class,
            'id_sub_kategori_rkbu', // Foreign key di SubKategoriRkbu
            'id_kategori_rkbu', // Foreign key di KategoriRkbu
            'id_sub_kategori_rkbu', // Local key di Rkbu
            'id_kategori_rkbu' // Local key di SubKategoriRkbu
        );
    }

    public function obyek_belanjas()
    {
        return $this->belongsTo(ObyekBelanja::class, 'id_obyek_belanja', 'id_obyek_belanja');
    }

    public function jenis_kategori_rkbus()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'id_jenis_kategori_rkbu', 'id_jenis_kategori_rkbu');
    }

    public function kategori_rkbus()
    {
        return $this->belongsTo(KategoriRkbu::class, 'id_kategori_rkbu', 'id_kategori_rkbu');
    }

    public function kategori_rekenings()
    {
        return $this->belongsTo(KategoriRekening::class, 'id_kategori_rekening', 'id_kategori_rekening');
    }

    public function sub_kategori_rekenings()
    {
        return $this->belongsTo(SubKategoriRekening::class, 'id_sub_kategori_rekening', 'id_sub_kategori_rekening');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function Ksp()
    {
        return $this->belongsTo(Ksp::class, 'id_ksp');
    }

    public function rekening_belanja()
    {
        return $this->belongsTo(RekeningBelanja::class, 'id_kode_rekening_belanja', 'id_kode_rekening_belanja');
    }

    public function usulanBarangDetails()
    {
        return $this->hasMany(UsulanBarangDetail::class, 'id_rkbu', 'id_rkbu');
    }

    public function usulan_barang_details()
    {
        return $this->hasMany(
            UsulanBarangDetail::class,
            'id_rkbu',
            'id_rkbu'
        );
    }


    public function getMasterSpjsAttribute()
    {
        return $this->usulanBarangDetails
            ->flatMap(function ($detail) {
                return $detail->spjDetails->map(function ($spjDetail) {
                    return optional($spjDetail->spj)->masterSpj;
                });
            })
            ->filter() // Buang null
            ->unique('id_master_spj') // Hindari duplikat
            ->values(); // Reset index
    }
}
