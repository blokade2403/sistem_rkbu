
<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingBelanja\UsulanBarang;

function faseAktif()
{
    $tahun = TahunAnggaran::find(session("tahun_anggaran"));

    return $tahun?->fase?->nama_fase;
}

if (!function_exists("breadcrumb_name")) {
    function breadcrumb_name($segment)
    {
        $map = [
            "setting-user" => "Setting User",
            "jabatans" => "Jabatan",
            "fungsionals" => "Fungsional",
            "units" => "Unit",
            "level_users" => "Level User",
            "fases" => "Fase",
            "ppk_keuangans" => "PPK Keuangan",
            "pptks" => "PPTK",
            "pejabat_pengadaans" => "Pejabat Pengadaan",
            "admin_pendukungs" => "Admin Pendukung",
            "pejabats" => "Pejabat",
            "users" => "User",
        ];

        // Kalau ada di mapping, pakai
        if (array_key_exists($segment, $map)) {
            return $map[$segment];
        }

        // Kalau tidak ada, ubah jadi Capitalize normal
        return ucfirst(str_replace(["-", "_"], " ", $segment));
    }
}

if (!function_exists("terbilang")) {
    /**
     * Fungsi untuk mengubah angka menjadi huruf (terbilang)
     *
     * @param int $jumlah
     * @return string
     */
    function terbilang($jumlah)
    {
        $angka = [
            0 => "",
            1 => "Satu",
            2 => "Dua",
            3 => "Tiga",
            4 => "Empat",
            5 => "Lima",
            6 => "Enam",
            7 => "Tujuh",
            8 => "Delapan",
            9 => "Sembilan",
            10 => "Sepuluh",
            11 => "Sebelas",
            12 => "Dua Belas",
            13 => "Tiga Belas",
            14 => "Empat Belas",
            15 => "Lima Belas",
            16 => "Enam Belas",
            17 => "Tujuh Belas",
            18 => "Delapan Belas",
            19 => "Sembilan Belas",
            20 => "Dua Puluh",
        ];

        if ($jumlah < 21) {
            return $angka[$jumlah];
        } elseif ($jumlah < 100) {
            return $angka[intval($jumlah / 10)] .
                " Puluh " .
                terbilang($jumlah % 10);
        } elseif ($jumlah < 200) {
            return "Seratus " . terbilang($jumlah - 100);
        } elseif ($jumlah < 1000) {
            return terbilang(intval($jumlah / 100)) .
                " Ratus " .
                terbilang($jumlah % 100);
        } elseif ($jumlah < 2000) {
            return "Seribu " . terbilang($jumlah - 1000);
        } elseif ($jumlah < 1000000) {
            return terbilang(intval($jumlah / 1000)) .
                " Ribu " .
                terbilang($jumlah % 1000);
        } elseif ($jumlah < 1000000000) {
            return terbilang(intval($jumlah / 1000000)) .
                " Juta " .
                terbilang($jumlah % 1000000);
        } elseif ($jumlah < 1000000000000) {
            return terbilang(intval($jumlah / 1000000000)) .
                " Miliar " .
                terbilang($jumlah % 1000000000);
        } else {
            return "Angka terlalu besar";
        }
    }
}

if (!function_exists("formatTanggal")) {
    function formatTanggal($tanggal)
    {
        if (!$tanggal || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            return "Tanggal tidak valid";
        }

        $bulan = [
            1 => "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        $tanggalArray = explode("-", $tanggal); // Pisahkan tahun, bulan, tanggal
        $tahun = $tanggalArray[0];
        $bulanAngka = (int) $tanggalArray[1];
        $hari = $tanggalArray[2];

        return $hari . " " . $bulan[$bulanAngka] . " " . $tahun;
    }
}

if (!function_exists("terbilangUang")) {
    function terbilangUang($angka)
    {
        $angka = abs($angka);
        $satuan = [
            "",
            "Satu",
            "Dua",
            "Tiga",
            "Empat",
            "Lima",
            "Enam",
            "Tujuh",
            "Delapan",
            "Sembilan",
            "Sepuluh",
            "Sebelas",
        ];
        $terbilang = "";

        if ($angka < 12) {
            $terbilang = $satuan[$angka];
        } elseif ($angka < 20) {
            $terbilang = $satuan[$angka - 10] . " Belas";
        } elseif ($angka < 100) {
            $terbilang =
                $satuan[floor($angka / 10)] .
                " Puluh " .
                terbilangUang($angka % 10);
        } elseif ($angka < 200) {
            $terbilang = "Seratus " . terbilangUang($angka - 100);
        } elseif ($angka < 1000) {
            $terbilang =
                $satuan[floor($angka / 100)] .
                " Ratus " .
                terbilangUang($angka % 100);
        } elseif ($angka < 2000) {
            $terbilang = "Seribu " . terbilangUang($angka - 1000);
        } elseif ($angka < 1000000) {
            $terbilang =
                terbilangUang(floor($angka / 1000)) .
                " Ribu " .
                terbilangUang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $terbilang =
                terbilangUang(floor($angka / 1000000)) .
                " Juta " .
                terbilangUang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $terbilang =
                terbilangUang(floor($angka / 1000000000)) .
                " Milyar " .
                terbilangUang(fmod($angka, 1000000000));
        } elseif ($angka < 1000000000000000) {
            $terbilang =
                terbilangUang(floor($angka / 1000000000000)) .
                " Triliun " .
                terbilangUang(fmod($angka, 1000000000000));
        }

        return trim($terbilang);
    }

    function formatTerbilangUang($angka)
    {
        return terbilangUang($angka) . " Rupiah";
    }

    function formatTanggalIndonesia($tanggal)
    {
        // Array nama hari dan bulan
        $namaHari = [
            "Minggu",
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu",
        ];
        $namaBulan = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        // Fungsi untuk mengubah angka menjadi teks
        function angkaKeTeks($angka)
        {
            $teks = [
                0 => "Nol",
                1 => "Satu",
                2 => "Dua",
                3 => "Tiga",
                4 => "Empat",
                5 => "Lima",
                6 => "Enam",
                7 => "Tujuh",
                8 => "Delapan",
                9 => "Sembilan",
            ];

            if ($angka < 10) {
                return $teks[$angka];
            } elseif ($angka < 20) {
                return $teks[$angka - 10] . " Belas";
            } elseif ($angka < 100) {
                return angkaKeTeks((int) ($angka / 10)) .
                    " Puluh" .
                    ($angka % 10 != 0 ? " " . $teks[$angka % 10] : "");
            }

            return $angka; // Untuk angka di atas 99
        }

        // Ubah tanggal menjadi timestamp
        $timestamp = strtotime($tanggal);

        // Ambil informasi hari, tanggal, bulan, dan tahun
        $hari = $namaHari[date("w", $timestamp)];
        $tanggal = (int) date("d", $timestamp);
        $bulan = $namaBulan[(int) date("m", $timestamp) - 1];
        $tahun = (int) date("Y", $timestamp);

        // Konversi tanggal ke teks
        $tanggalTeks = angkaKeTeks($tanggal);

        // Konversi tahun ke teks (pastikan urutannya benar)
        $tahunTeks = "Dua Ribu"; // Bagian pertama adalah "Dua Ribu"
        $tahunBelakang = (int) substr($tahun, 2); // Ambil dua digit terakhir
        if ($tahunBelakang > 0) {
            $tahunTeks .= " " . angkaKeTeks($tahunBelakang); // Tambahkan dua digit terakhir jika tidak nol
        }

        // Format final
        return "$hari tanggal $tanggalTeks bulan $bulan tahun $tahunTeks";
    }

    if (!function_exists("getUserNotifications")) {
        function getUserNotifications()
        {
            $idPptk = session("id_pptk"); // gunakan session id_pptk
            $idPejabat = session("id_pejabat");
            $tahunAnggaran = Session::get("tahun_anggaran");
            $namaLevelUser = session("nama_level_user");

            $pptk = [
                "9ce173c5-b49f-41e1-b17c-f55e25544a58",
                "9d5b9ca9-8699-4456-8267-153db7acb631",
                "9d5b9cbe-e3f4-4cfc-bb3a-3e5b4fca35db",
            ];

            $Pengadaan = ["Admin PPK", "PPBJ", "PPK"];

            $notifications = collect();

            if (!empty($idPptk)) {
                $notifications = UsulanBarang::where(
                    "status_permintaan_barang",
                    "Perlu Validasi PPTK",
                )
                    ->where("usulan_barangs.tahun_anggaran", $tahunAnggaran)
                    ->where("usulan_barangs.status_usulan_barang", "Selesai")
                    ->join(
                        "users",
                        "usulan_barangs.id_user",
                        "=",
                        "users.id_user",
                    )
                    ->join(
                        "sub_kategori_rkbus",
                        "usulan_barangs.id_sub_kategori_rkbu",
                        "=",
                        "sub_kategori_rkbus.id_sub_kategori_rkbu",
                    )
                    ->where("sub_kategori_rkbus.id_pptk", $idPptk)
                    ->select("usulan_barangs.*")
                    ->distinct()
                    ->get();
            } elseif ($namaLevelUser === "Validasi RKA") {
                $notifications = UsulanBarang::where(
                    "status_permintaan_barang",
                    "Disetujui PPTK",
                )
                    ->where("users.id_pejabat", $idPejabat)
                    ->where("usulan_barangs.tahun_anggaran", $tahunAnggaran)
                    ->where("usulan_barangs.status_usulan_barang", "Selesai")
                    ->join(
                        "users",
                        "users.id_user",
                        "=",
                        "usulan_barangs.id_user",
                    )
                    ->join("ksps", "users.id_ksp", "=", "ksps.id_ksp")
                    ->join(
                        "pejabats",
                        "users.id_pejabat",
                        "=",
                        "pejabats.id_pejabat",
                    )
                    ->select("usulan_barangs.*")
                    ->distinct()
                    ->get();
            } elseif ($namaLevelUser === "Direktur") {
                $notifications = UsulanBarang::where(
                    "status_permintaan_barang",
                    "Disetujui Kabag",
                )
                    ->where("usulan_barangs.tahun_anggaran", $tahunAnggaran)
                    ->where("usulan_barangs.status_usulan_barang", "Selesai")
                    ->join(
                        "users",
                        "usulan_barangs.id_user",
                        "=",
                        "users.id_user",
                    )
                    ->select("usulan_barangs.*")
                    ->distinct()
                    ->get();
            } elseif (in_array($namaLevelUser, $Pengadaan)) {
                $notifications = UsulanBarang::where(
                    "status_permintaan_barang",
                    "Disetujui Direktur",
                )
                    ->whereNull("usulan_barangs.status_validasi_pengadaan") // hanya yang belum diproses pengadaan
                    ->where("usulan_barangs.tahun_anggaran", $tahunAnggaran)
                    ->where("usulan_barangs.status_usulan_barang", "Selesai")
                    ->join(
                        "users",
                        "usulan_barangs.id_user",
                        "=",
                        "users.id_user",
                    )
                    ->select("usulan_barangs.*")
                    ->distinct()
                    ->get();
            }

            return $notifications;
        }
    }
}

if (!function_exists("getRealisasiPerUsulanDetail")) {
    function getRealisasiPerUsulanDetail($tahunAnggaran)
    {
        return DB::table("usulan_barang_details as ubd")
            ->select(
                "ubd.id_usulan_barang_detail",
                DB::raw("ubd.total_anggaran_usulan_barang as total_anggaran"),

                // ✅ REALISASI (SUDAH DIBAYAR)
                DB::raw("
                    LEAST(
                        ubd.total_anggaran_usulan_barang,
                        SUM(
                            CASE
                                WHEN ms.status_pembayaran = 'Sudah di Bayar'
                                THEN ms.pembayaran
                                ELSE 0
                            END
                        )
                    ) as realisasi
                "),

                // ✅ PROSES (SERAH TERIMA SELESAI, BELUM DIBAYAR)
                DB::raw("
                    LEAST(
                        ubd.total_anggaran_usulan_barang
                        -
                        LEAST(
                            ubd.total_anggaran_usulan_barang,
                            SUM(
                                CASE
                                    WHEN ms.status_pembayaran = 'Sudah di Bayar'
                                    THEN ms.pembayaran
                                    ELSE 0
                                END
                            )
                        ),
                        SUM(
                            CASE
                                WHEN ms.status_serah_terima_bendahara = 'Selesai'
                                 AND (ms.status_pembayaran IS NULL
                                      OR ms.status_pembayaran != 'Sudah di Bayar')
                                THEN ms.bruto
                                ELSE 0
                            END
                        )
                    ) as proses
                "),
            )
            ->join(
                "spj_details as sd",
                "sd.id_usulan_barang_detail",
                "=",
                "ubd.id_usulan_barang_detail",
            )
            ->join("spjs as s", "s.id_spj", "=", "sd.id_spj")
            ->join("master_spjs as ms", "ms.id_spj", "=", "s.id_spj")
            ->where("ms.tahun_anggaran", $tahunAnggaran)
            ->groupBy(
                "ubd.id_usulan_barang_detail",
                "ubd.total_anggaran_usulan_barang",
            )
            ->get()
            ->keyBy("id_usulan_barang_detail");
    }
}

// if (! function_exists('getRealisasiPerUsulanDetail')) {
//     function getRealisasiPerUsulanDetail($tahunAnggaran)
//     {
//         return DB::table('usulan_barang_details as ubd')
//             ->select(
//                 'ubd.id_usulan_barang_detail',
//                 DB::raw('ubd.total_anggaran_usulan_barang as total_anggaran'),

//                 // REALISASI DIBATASI OLEH ANGGARAN
//                 DB::raw("
//                     LEAST(
//                         ubd.total_anggaran_usulan_barang,
//                         SUM(
//                             CASE
//                                 WHEN ms.status_pembayaran = 'Sudah di Bayar'
//                                 THEN ms.pembayaran
//                                 ELSE 0
//                             END
//                         )
//                     ) as realisasi
//                 "),

//                 // PROSES DIBATASI OLEH SISA ANGGARAN
//                 DB::raw("
//                     LEAST(
//                         ubd.total_anggaran_usulan_barang
//                         -
//                         LEAST(
//                             ubd.total_anggaran_usulan_barang,
//                             SUM(
//                                 CASE
//                                     WHEN ms.status_pembayaran = 'Sudah di Bayar'
//                                     THEN ms.pembayaran
//                                     ELSE 0
//                                 END
//                             )
//                         ),
//                         SUM(
//                             CASE
//                                 WHEN ms.status_pembayaran IS NULL
//                                   OR ms.status_pembayaran != 'Sudah di Bayar'
//                                 THEN ms.bruto
//                                 ELSE 0
//                             END
//                         )
//                     ) as proses
//                 ")
//             )
//             ->join('spj_details as sd', 'sd.id_usulan_barang_detail', '=', 'ubd.id_usulan_barang_detail')
//             ->join('spjs as s', 's.id_spj', '=', 'sd.id_spj')
//             ->join('master_spjs as ms', 'ms.id_spj', '=', 's.id_spj')
//             ->where('ms.tahun_anggaran', $tahunAnggaran)
//             ->groupBy(
//                 'ubd.id_usulan_barang_detail',
//                 'ubd.total_anggaran_usulan_barang'
//             )
//             ->get()
//             ->keyBy('id_usulan_barang_detail');
//     }
// }

