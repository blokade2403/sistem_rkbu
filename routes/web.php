<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\MasterSpj\MasterSpjController;
use App\Http\Controllers\Rkbu\RkbuBarangJasaController;
use App\Http\Controllers\Rkbu\RkbuModalAlkesController;
use App\Http\Controllers\Rkbu\RkbuPegawaiPnsController;
use App\Http\Controllers\Rkbu\RkbuPersediaanController;
use App\Http\Controllers\Rkbu\RkbuModalKantorController;
use App\Http\Controllers\Rkbu\RkbuPegawaiNonPnsController;
use App\Http\Controllers\Rkbu\Barjas\PdfControllerRkbuBarjas;
use App\Http\Controllers\SpjBelanja\UsulanSpjModalController;
use App\Http\Controllers\SpjBelanja\UsulanSpjBarjasController;
use App\Http\Controllers\MasterBackend\UserProfil\KspController;
use App\Http\Controllers\UsulanBelanja\ExportUsulanBarangDetail;
use App\Http\Controllers\MasterBackend\UserProfil\UserController;
use App\Http\Controllers\UsulanBelanja\CekUsulanBarangController;
use App\Http\Controllers\MasterBackend\SettingUser\FaseController;
use App\Http\Controllers\MasterBackend\SettingUser\PptkController;
use App\Http\Controllers\MasterBackend\SettingUser\UnitController;
use App\Http\Controllers\SpjBelanja\UsulanSpjPersediaanController;
use App\Http\Controllers\MasterBackend\UserProfil\PejabatController;
use App\Http\Controllers\Rkbu\Barjas\PdfControllerValidasiBarjasKsp;
use App\Http\Controllers\UsulanBelanja\UsulanBelanjaModalController;
use App\Http\Controllers\MasterBackend\SettingRkbu\ProgramController;
use App\Http\Controllers\MasterBackend\SettingUser\JabatanController;
use App\Http\Controllers\Rkbu\ModalAlkes\PdfControllerRkbuModalAlkes;
use App\Http\Controllers\Rkbu\Persediaan\PdfControllerRkbuPersediaan;
use App\Http\Controllers\UsulanBelanja\UsulanBelanjaBarjasController;
use App\Http\Controllers\MasterBackend\SettingRkbu\KegiatanController;
use App\Http\Controllers\Rkbu\Administrator\RkbuBarjasAdminController;
use App\Http\Controllers\MasterBackend\SettingInput\AnggaranController;
use App\Http\Controllers\MasterBackend\SettingInput\KomponenController;
use App\Http\Controllers\MasterBackend\SettingRkbu\AktivitasController;
use App\Http\Controllers\MasterBackend\SettingUser\LevelUserController;
use App\Http\Controllers\Rkbu\ModalKantor\PdfControllerRkbuModalKantor;
use App\Http\Controllers\UsulanBelanja\CekUsulanBelanjaModalController;
use App\Http\Controllers\MasterBackend\SettingInput\UraianDuaController;
use App\Http\Controllers\MasterBackend\SettingRkbu\SumberDanaController;
use App\Http\Controllers\MasterBackend\SettingUser\FungsionalController;
use App\Http\Controllers\MasterBackend\SettingInput\UraianSatuController;
use App\Http\Controllers\MasterBackend\SettingRkbu\SubKegiatanController;
use App\Http\Controllers\MasterBackend\SettingUser\PpkKeuanganController;
use App\Http\Controllers\Rkbu\Administrator\PdfControllerRkbuBarjasAdmin;
use App\Http\Controllers\Rkbu\Pegawai\PdfControllerValidasiPegawaiPnsKsp;
use App\Http\Controllers\Rkbu\ValidasiKsp\ValidasiRkbuModalKspController;
use App\Http\Controllers\Rkbu\ValidasiRka\PdfControllerValidasiBarjasRka;
use App\Http\Controllers\UsulanBelanja\PdfControllerCekUsulanBarangModal;
use App\Http\Controllers\UsulanBelanja\UsulanBelanjaPersediaanController;
use App\Http\Controllers\MasterBackend\SettingInput\JudulHeaderController;
use App\Http\Controllers\MasterBackend\SettingRkbu\JenisBelanjaController;
use App\Http\Controllers\MasterBackend\SettingRkbu\KategoriRkbuController;
use App\Http\Controllers\MasterBackend\SettingRkbu\ObyekBelanjaController;
use App\Http\Controllers\Rkbu\Administrator\RkbuModalAlkesAdminController;
use App\Http\Controllers\Rkbu\Administrator\RkbuPegawaiPnsAdminController;
use App\Http\Controllers\Rkbu\Administrator\RkbuPersediaanAdminController;
use App\Http\Controllers\Rkbu\ValidasiKsp\ValidasiRkbuBarjasKspController;
use App\Http\Controllers\Rkbu\ValidasiRka\ValidasiRkbuBarjasRkaController;
use App\Http\Controllers\UsulanBelanja\PdfControllerCekUsulanBarangBarjas;
use App\Http\Controllers\Rkbu\Administrator\RkbuModalKantorAdminController;
use App\Http\Controllers\MasterBackend\SettingInput\TahunAnggaranController;
use App\Http\Controllers\MasterBackend\SettingUser\AdminPendukungController;
use App\Http\Controllers\Rkbu\Pegawai\PdfControllerValidasiPegawaiNonPnsKsp;
use App\Http\Controllers\Rkbu\Persediaan\PdfControllerValidasiPersediaanKsp;
use App\Http\Controllers\UsulanBelanja\CekUsulanBelanjaPersediaanController;
use App\Http\Controllers\MasterBackend\SettingInput\StatusValidasiController;
use App\Http\Controllers\MasterBackend\SettingRkbu\SubKategoriRkbuController;
use App\Http\Controllers\Rkbu\Administrator\PdfControllerRkbuModalAlkesAdmin;
use App\Http\Controllers\Rkbu\Administrator\PdfControllerRkbuPegawaiPnsAdmin;
use App\Http\Controllers\Rkbu\Administrator\PdfControllerRkbuPersediaanAdmin;
use App\Http\Controllers\Rkbu\ValidasiRka\PdfControllerValidasiModalAlkesRka;
use App\Http\Controllers\Rkbu\ValidasiRka\PdfControllerValidasiPegawaiPnsRka;
use App\Http\Controllers\Rkbu\ValidasiRka\PdfControllerValidasiPersediaanRka;
use App\Http\Controllers\MasterBackend\SettingRkbu\KategoriRekeningController;
use App\Http\Controllers\MasterBackend\SettingUser\PejabatPengadaanController;
use App\Http\Controllers\Rkbu\Administrator\PdfControllerRkbuModalKantorAdmin;
use App\Http\Controllers\Rkbu\ModalKantor\PdfControllerValidasiModalKantorKsp;
use App\Http\Controllers\Rkbu\ValidasiKsp\ValidasiRkbuModalAlkesKspController;
use App\Http\Controllers\Rkbu\ValidasiKsp\ValidasiRkbuPegawaiPnsKspController;
use App\Http\Controllers\Rkbu\ValidasiKsp\ValidasiRkbuPersediaanKspController;
use App\Http\Controllers\Rkbu\ValidasiRka\PdfControllerValidasiModalKantorRka;
use App\Http\Controllers\Rkbu\ValidasiRka\ValidasiRkbuModalAlkesRkaController;
use App\Http\Controllers\Rkbu\ValidasiRka\ValidasiRkbuPegawaiPnsRkaController;
use App\Http\Controllers\Rkbu\ValidasiRka\ValidasiRkbuPersediaanRkaController;
use App\Http\Controllers\UsulanBelanja\PdfControllerCekUsulanBarangPersediaan;
use App\Http\Controllers\MasterBackend\SettingRkbu\JenisKategoriRkbuController;
use App\Http\Controllers\Rkbu\ValidasiRka\ValidasiRkbuModalKantorRkaController;
use App\Http\Controllers\MasterBackend\SettingInput\StatusValidasiRkaController;
use App\Http\Controllers\Rkbu\ValidasiRka\PdfControllerValidasiPegawaiNonPnsRka;
use App\Http\Controllers\Rkbu\ValidasiKsp\ValidasiRkbuPegawaiNonPnsKspController;
use App\Http\Controllers\Rkbu\ValidasiRka\ValidasiRkbuPegawaiNonPnsRkaController;
use App\Http\Controllers\ValidasiUsulanBelanja\Rka\ValidasiUsulanModalRkaController;
use App\Http\Controllers\ValidasiUsulanBelanja\Rka\ValidasiUsulanBarangRkaController;
use App\Http\Controllers\ValidasiUsulanBelanja\Pptk\ValidasiUsulanModalPptkController;
use App\Http\Controllers\ValidasiUsulanBelanja\Pptk\ValidasiUsulanBarangPptkController;
use App\Http\Controllers\ValidasiUsulanBelanja\Rka\ValidasiUsulanPersediaanRkaController;
use App\Http\Controllers\ValidasiUsulanBelanja\Pptk\ValidasiUsulanPersediaanPptkController;
use App\Http\Controllers\ValidasiUsulanBelanja\Direktur\ValidasiUsulanModalDirekturController;
use App\Http\Controllers\ValidasiUsulanBelanja\Direktur\ValidasiUsulanBarangDirekturController;
use App\Http\Controllers\ValidasiUsulanBelanja\Direktur\ValidasiUsulanPersediaanDirekturController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome2');


Route::controller(AuthController::class)->group(function () {
    Route::get('/', function () {
        return redirect()->route('login.form');
    });
    Route::get('/login', 'showLoginForm')->name('login.form');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashbord', [DashboardController::class, 'index'])->name('dashbord');

    // Master Data Routes
    Route::prefix('setting-user')->group(function () {
        Route::resource('jabatans', JabatanController::class);
        Route::delete('/jabatans/{id}', [JabatanController::class, 'destroy'])->name('jabatans.destroy');
        Route::resource('fungsionals', FungsionalController::class);
        Route::resource('units', UnitController::class);
        Route::resource('level_users', LevelUserController::class);
        Route::resource('fases', FaseController::class);
        Route::resource('ppk_keuangans', PpkKeuanganController::class);
        Route::resource('pptks', PptkController::class);
        Route::resource('pejabat_pengadaans', PejabatPengadaanController::class);
        Route::resource('admin_pendukungs', AdminPendukungController::class);
    });

    Route::prefix('user-profil')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/users/{id}/edit_profil', [UserController::class, 'edit_profil'])->name('users.edit_profil');
        Route::put('/users/{user}', [UserController::class, 'update_profil'])->name('users.update_profil');
        Route::resource('ksps', KspController::class);
        Route::resource('pejabats', PejabatController::class);
    });

    Route::prefix('setting-input')->group(function () {
        Route::resource('tahun_anggarans', TahunAnggaranController::class);
        Route::resource('komponens', KomponenController::class);
        Route::resource('uraian_satus', UraianSatuController::class);
        Route::resource('uraian_duas', UraianDuaController::class);
        Route::resource('status_validasis', StatusValidasiController::class);
        Route::resource('status_validasi_rkas', StatusValidasiRkaController::class);
        Route::resource('judul_headers', JudulHeaderController::class);
        Route::resource('anggarans', AnggaranController::class);
    });

    Route::prefix('setting-rkbu')->group(function () {
        Route::resource('aktivitas', AktivitasController::class);
        Route::resource('jenis_belanjas', JenisBelanjaController::class);
        Route::resource('programs', ProgramController::class);
        Route::resource('kegiatans', KegiatanController::class);
        Route::resource('sumber_danas', SumberDanaController::class);
        Route::resource('sub_kegiatans', SubKegiatanController::class);
        Route::resource('kategori_rekenings', KategoriRekeningController::class);
        Route::resource('jenis_kategori_rkbus', JenisKategoriRkbuController::class);
        Route::resource('obyek_belanjas', ObyekBelanjaController::class);
        Route::resource('kategori_rkbus', KategoriRkbuController::class);
        Route::resource('sub_kategori_rkbus', SubKategoriRkbuController::class);
    });

    Route::prefix('rkbu-barjas')->group(function () {
        Route::resource('rkbu_barang_jasas', RkbuBarangJasaController::class);
        Route::post('rkbu_barang_jasas/get_data_by_subkategori', [RkbuBarangJasaController::class, 'getDataBySubKategori'])->name('rkbu_barang_jasas.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerRkbuBarjas::class, 'downloadPDF'])->name('rkbu_barang_jasas.downloadPDF');
    });

    Route::prefix('rkbu-modal')->group(function () {
        Route::resource('rkbu_modal_kantors', RkbuModalKantorController::class);
        Route::post('rkbu_modal_kantors/get_data_by_subkategori', [RkbuModalKantorController::class, 'getDataBySubKategori'])->name('rkbu_modal_kantors.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerRkbuModalKantor::class, 'downloadPDF'])->name('rkbu_modal_kantors.downloadPDF');
    });

    Route::prefix('rkbu-modal-alkes')->group(function () {
        Route::resource('rkbu_modal_alkess', RkbuModalAlkesController::class);
        Route::post('rkbu_modal_alkess/get_data_by_subkategori', [RkbuModalAlkesController::class, 'getDataBySubKategori'])->name('rkbu_modal_alkess.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerRkbuModalAlkes::class, 'downloadPDF'])->name('rkbu_modal_alkess.downloadPDF');
    });

    Route::prefix('rkbu-persediaan')->group(function () {
        Route::resource('rkbu_persediaans', RkbuPersediaanController::class);
        Route::post('rkbu_persediaans/get_data_by_subkategori', [RkbuPersediaanController::class, 'getDataBySubKategori'])->name('rkbu_persediaans.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerRkbuPersediaan::class, 'downloadPDF'])->name('rkbu_persediaans.downloadPDF');
    });

    Route::prefix('rkbu-pegawai-pns')->group(function () {
        Route::resource('rkbu_pegawai_pnss', RkbuPegawaiPnsController::class);
        Route::post('rkbu_pegawai_pnss/get_data_by_subkategori', [RkbuPegawaiPnsController::class, 'getDataBySubKategori'])->name('rkbu_pegawai_pnss.get_data_by_subkategori');
        Route::get('/downloadPDF', [RkbuPegawaiPnsController::class, 'downloadPDF'])->name('rkbu_pegawai_pnss.downloadPDF');
    });

    Route::prefix('rkbu-pegawai-non-pns')->group(function () {
        Route::resource('rkbu_pegawai_non_pnss', RkbuPegawaiNonPnsController::class);
        Route::post('rkbu_pegawai_non_pnss/get_data_by_subkategori', [RkbuPegawaiNonPnsController::class, 'getDataBySubKategori'])->name('rkbu_pegawai_non_pnss.get_data_by_subkategori');
        Route::get('/downloadPDF', [RkbuPegawaiNonPnsController::class, 'downloadPDF'])->name('rkbu_pegawai_non_pnss.downloadPDF');
    });

    Route::prefix('validasi-ksp-barjas')->group(function () {
        Route::resource('validasi_ksp_rkbu_barang_jasas', ValidasiRkbuBarjasKspController::class);
        Route::post('validasi_ksp_rkbu_barang_jasas/get_data_by_subkategori', [RkbuPegawaiNonPnsController::class, 'getDataBySubKategori'])->name('validasi_ksp_rkbu_barang_jasas.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiBarjasKsp::class, 'downloadPDF'])->name('validasi_ksp_rkbu_barang_jasas.downloadPDF');
    });

    Route::prefix('validasi-ksp-modal-kantor')->group(function () {
        Route::resource('validasi_ksp_rkbu_modal_kantors', ValidasiRkbuModalKspController::class);
        Route::post('validasi_ksp_rkbu_modal_kantors/get_data_by_subkategori', [ValidasiRkbuModalKspController::class, 'getDataBySubKategori'])->name('validasi_ksp_rkbu_modal_kantors.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiModalKantorKsp::class, 'downloadPDF'])->name('validasi_ksp_rkbu_modal_kantors.downloadPDF');
    });

    Route::prefix('validasi-ksp-modal-alkes')->group(function () {
        Route::resource('validasi_ksp_rkbu_modal_alkess', ValidasiRkbuModalAlkesKspController::class);
        Route::post('validasi_ksp_rkbu_modal_alkess/get_data_by_subkategori', [ValidasiRkbuModalAlkesKspController::class, 'getDataBySubKategori'])->name('validasi_ksp_rkbu_modal_alkess.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiModalAlkesRka::class, 'downloadPDF'])->name('validasi_ksp_rkbu_modal_alkess.downloadPDF');
    });

    Route::prefix('validasi-ksp-persediaan')->group(function () {
        Route::resource('validasi_ksp_rkbu_persediaans', ValidasiRkbuPersediaanKspController::class);
        Route::post('validasi_ksp_rkbu_persediaans/get_data_by_subkategori', [ValidasiRkbuPersediaanKspController::class, 'getDataBySubKategori'])->name('validasi_ksp_rkbu_persediaans.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiPersediaanKsp::class, 'downloadPDF'])->name('validasi_ksp_rkbu_persediaans.downloadPDF');
    });

    Route::prefix('validasi-ksp-pegawai_pns')->group(function () {
        Route::resource('validasi_ksp_rkbu_pnss', ValidasiRkbuPegawaiPnsKspController::class);
        Route::post('validasi_ksp_rkbu_pnss/get_data_by_subkategori', [ValidasiRkbuPegawaiPnsKspController::class, 'getDataBySubKategori'])->name('validasi_ksp_rkbu_pnss.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiPegawaiPnsKsp::class, 'downloadPDF'])->name('validasi_ksp_rkbu_pnss.downloadPDF');
    });

    Route::prefix('validasi-ksp-pegawai-non-pns')->group(function () {
        Route::resource('validasi_ksp_rkbu_non_pnss', ValidasiRkbuPegawaiNonPnsKspController::class);
        Route::post('validasi_ksp_rkbu_non_pnss/get_data_by_subkategori', [ValidasiRkbuPegawaiNonPnsKspController::class, 'getDataBySubKategori'])->name('validasi_ksp_rkbu_non_pnss.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiPegawaiNonPnsKsp::class, 'downloadPDF'])->name('validasi_ksp_rkbu_non_pnss.downloadPDF');
    });

    Route::prefix('validasi-rka-barjas')->group(function () {
        Route::resource('validasi_rka_rkbu_barjas', ValidasiRkbuBarjasRkaController::class);
        Route::post('validasi_rka_rkbu_barjas/get_data_by_subkategori', [ValidasiRkbuBarjasRkaController::class, 'getDataBySubKategori'])->name('validasi_rka_rkbu_barjas.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiBarjasRka::class, 'downloadPDF'])->name('validasi_rka_rkbu_barjas.downloadPDF');
    });

    Route::prefix('validasi-rka-modal-kantor')->group(function () {
        Route::resource('validasi_rka_modal_kantor', ValidasiRkbuModalKantorRkaController::class);
        Route::post('validasi_rka_modal_kantor/get_data_by_subkategori', [ValidasiRkbuModalKantorRkaController::class, 'getDataBySubKategori'])->name('validasi_rka_modal_kantor.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiModalKantorRka::class, 'downloadPDF'])->name('validasi_rka_modal_kantor.downloadPDF');
    });

    Route::prefix('validasi-rka-modal-alkes')->group(function () {
        Route::resource('validasi_rka_modal_alkess', ValidasiRkbuModalAlkesRkaController::class);
        Route::post('validasi_rka_modal_alkess/get_data_by_subkategori', [ValidasiRkbuModalAlkesRkaController::class, 'getDataBySubKategori'])->name('validasi_rka_modal_alkess.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiModalAlkesRka::class, 'downloadPDF'])->name('validasi_rka_modal_alkess.downloadPDF');
    });

    Route::prefix('validasi-rka-pegawai-pns')->group(function () {
        Route::resource('validasi_rka_pegawai_pnss', ValidasiRkbuPegawaiPnsRkaController::class);
        Route::post('validasi_rka_pegawai_pnss/get_data_by_subkategori', [ValidasiRkbuPegawaiPnsRkaController::class, 'getDataBySubKategori'])->name('validasi_rka_pegawai_pnss.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiPegawaiPnsRka::class, 'downloadPDF'])->name('validasi_rka_pegawai_pnss.downloadPDF');
    });

    Route::prefix('validasi-rka-non-pns')->group(function () {
        Route::resource('validasi_rka_pegawai_non_pnss', ValidasiRkbuPegawaiNonPnsRkaController::class);
        Route::post('validasi_rka_pegawai_non_pnss/get_data_by_subkategori', [ValidasiRkbuPegawaiNonPnsRkaController::class, 'getDataBySubKategori'])->name('validasi_rka_pegawai_non_pnss.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiPegawaiNonPnsRka::class, 'downloadPDF'])->name('validasi_rka_pegawai_non_pnss.downloadPDF');
    });

    Route::prefix('validasi-rka-persediaan')->group(function () {
        Route::resource('validasi_rka_rkbu_persediaans', ValidasiRkbuPersediaanRkaController::class);
        Route::post('validasi_rka_rkbu_persediaans/get_data_by_subkategori', [ValidasiRkbuPersediaanRkaController::class, 'getDataBySubKategori'])->name('validasi_rka_rkbu_persediaans.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerValidasiPersediaanRka::class, 'downloadPDF'])->name('validasi_rka_rkbu_persediaans.downloadPDF');
    });

    Route::prefix('admin-rkbu-pegawai-pns')->group(function () {
        Route::resource('rkbu_pegawai_pns_admins', RkbuPegawaiPnsAdminController::class);
        Route::post('rkbu_pegawai_pns_admins/get_data_by_subkategori', [RkbuPegawaiPnsAdminController::class, 'getDataBySubKategori'])->name('rkbu_pegawai_pns_admins.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerRkbuPegawaiPnsAdmin::class, 'downloadPDF'])->name('rkbu_pegawai_pns_admins.downloadPDF');
    });

    Route::prefix('admin-rkbu-barjas')->group(function () {
        Route::resource('rkbu_barjas_admins', RkbuBarjasAdminController::class);
        Route::post('rkbu_barjas_admins/get_data_by_subkategori', [RkbuBarjasAdminController::class, 'getDataBySubKategori'])->name('rkbu_barjas_admins.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerRkbuBarjasAdmin::class, 'downloadPDF'])->name('rkbu_barjas_admins.downloadPDF');
    });

    Route::prefix('admin-rkbu-modal-kantor')->group(function () {
        Route::resource('rkbu_modal_kantor_admins', RkbuModalKantorAdminController::class);
        Route::post('rkbu_modal_kantor_admins/get_data_by_subkategori', [RkbuModalKantorAdminController::class, 'getDataBySubKategori'])->name('rkbu_modal_kantor_admins.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerRkbuModalKantorAdmin::class, 'downloadPDF'])->name('rkbu_modal_kantor_admins.downloadPDF');
    });

    Route::prefix('admin-rkbu-modal-alkes')->group(function () {
        Route::resource('rkbu_modal_alkes_admins', RkbuModalAlkesAdminController::class);
        Route::post('rkbu_modal_alkes_admins/get_data_by_subkategori', [RkbuModalAlkesAdminController::class, 'getDataBySubKategori'])->name('rkbu_modal_alkes_admins.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerRkbuModalAlkesAdmin::class, 'downloadPDF'])->name('rkbu_modal_alkes_admins.downloadPDF');
    });

    Route::prefix('admin-rkbu-persediaan')->group(function () {
        Route::resource('rkbu_persediaan_admins', RkbuPersediaanAdminController::class);
        Route::post('rkbu_persediaan_admins/get_data_by_subkategori', [RkbuPersediaanAdminController::class, 'getDataBySubKategori'])->name('rkbu_persediaan_admins.get_data_by_subkategori');
        Route::get('/downloadPDF', [PdfControllerRkbuPersediaanAdmin::class, 'downloadPDF'])->name('rkbu_persediaan_admins.downloadPDF');
    });

    Route::prefix('usulan-belanja-barjas')->group(function () {
        Route::resource('usulan_barang_barjass', UsulanBelanjaBarjasController::class);
        Route::get('/usulan-barang_barjass/{id_usulan_barang}/show', [UsulanBelanjaBarjasController::class, 'show'])->name('usulan_barang_barjass.show');
        Route::post('/usulan_barang_barjass/update-status/{id}', [UsulanBelanjaBarjasController::class, 'updateStatus'])->name('usulan_barang_barjass.updateStatus');
        Route::delete('/usulan_barang_barjass/{usulan_barang}', [UsulanBelanjaBarjasController::class, 'destroy'])->name('usulan_barang_barjass.destroy');
        Route::delete('/usulan_barang_barjass.delete/{id_usulan_barang}', [UsulanBelanjaBarjasController::class, 'doDelete'])->name('usulan_barang_barjass.delete');
        Route::delete('/usulan_barang_barjass.delete_detail/{id_usulan_barang_detail}', [UsulanBelanjaBarjasController::class, 'doDeleteDetailKeranjang'])->name('usulan_barang_barjass.delete_detail');
        Route::post('usulan_barang_barjass/get_data_by_subkategori', [UsulanBelanjaBarjasController::class, 'getDataBySubKategori'])->name('usulan_barang_barjass.get_data_by_subkategori');
        Route::get('/get-rkbu-data', [UsulanBelanjaBarjasController::class, 'getRkbuData']);
        Route::post('/add-to-cart/{id_usulan_barang}', [UsulanBelanjaBarjasController::class, 'addToCart'])->name('add-to-cart');
        Route::post('/add-to-cart-multiple-barjas', [UsulanBelanjaBarjasController::class, 'addToCartMultiple'])->name('add-to-cart-multiple-barjas');
        Route::get('/usulan_barang_barjass/keranjang/{id}', [UsulanBelanjaBarjasController::class, 'keranjang'])->name('usulan_barang_barjass.keranjang');
        Route::get('/usulan_barang_details/download-report-export/{id_usulan_barang}', [ExportUsulanBarangDetail::class, 'downloadReportExport'])
            ->name('usulan_barang_details.downloadReportExport');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangBarjas::class, 'downloadPDFCekBarjas'])->name('usulan_barang_barjass.downloadPDF');
    });

    Route::prefix('usulan-belanja-persediaan')->group(function () {
        Route::resource('usulan_barang_persediaans', UsulanBelanjaPersediaanController::class);
        Route::get('/usulan-barang_persediaans/{id_usulan_barang}/show', [UsulanBelanjaPersediaanController::class, 'show'])->name('usulan_barang_persediaans.show');
        Route::post('/usulan_barang_persediaans/update-status/{id}', [UsulanBelanjaPersediaanController::class, 'updateStatus'])->name('usulan_barang_persediaans.updateStatus');
        Route::delete('/usulan_barang_persediaans/{usulan_barang}', [UsulanBelanjaPersediaanController::class, 'destroy'])->name('usulan_barang_persediaans.destroy');
        Route::delete('/usulan_barang_persediaans.delete/{id_usulan_barang}', [UsulanBelanjaPersediaanController::class, 'doDelete'])->name('usulan_barang_persediaans.delete');
        Route::delete('/usulan_barang_persediaans.delete_detail/{id_usulan_barang_detail}', [UsulanBelanjaPersediaanController::class, 'doDeleteDetailKeranjang'])->name('usulan_barang_persediaans.delete_detail');
        Route::post('usulan_barang_persediaans/get_data_by_subkategori', [UsulanBelanjaPersediaanController::class, 'getDataBySubKategori'])->name('usulan_barang_persediaans.get_data_by_subkategori');
        Route::get('/get-rkbu-data', [UsulanBelanjaPersediaanController::class, 'getRkbuData']);
        Route::post('/add-to-cart/{id_usulan_barang}', [UsulanBelanjaPersediaanController::class, 'addToCart'])->name('add-to-cart');
        Route::post('/add-to-cart-multiple-persediaan', [UsulanBelanjaPersediaanController::class, 'addToCartMultiple'])->name('add-to-cart-multiple-persediaan');
        Route::get('/usulan_barang_persediaans/keranjang/{id}', [UsulanBelanjaPersediaanController::class, 'keranjang'])->name('usulan_barang_persediaans.keranjang');
        Route::get('/usulan_barang_details/download-report-export/{id_usulan_barang}', [ExportUsulanBarangDetail::class, 'downloadReportExport'])
            ->name('usulan_barang_details.downloadReportExport');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangPersediaan::class, 'downloadPDFCekBarjas'])->name('usulan_barang_persediaans.downloadPDF');
    });

    Route::prefix('usulan-belanja-modal')->group(function () {
        Route::resource('usulan_barang_modals', UsulanBelanjaModalController::class);
        Route::get('/usulan-barang_modals/{id_usulan_barang}/show', [UsulanBelanjaModalController::class, 'show'])->name('usulan_barang_modals.show');
        Route::post('/usulan_barang_modals/update-status/{id}', [UsulanBelanjaModalController::class, 'updateStatus'])->name('usulan_barang_modals.updateStatus');
        Route::delete('/usulan_barang_modals/{usulan_barang}', [UsulanBelanjaModalController::class, 'destroy'])->name('usulan_barang_modals.destroy');
        Route::delete('/usulan_barang_modals.delete/{id_usulan_barang}', [UsulanBelanjaModalController::class, 'doDelete'])->name('usulan_barang_modals.delete');
        Route::delete('/usulan_barang_modals.delete_detail/{id_usulan_barang_detail}', [UsulanBelanjaModalController::class, 'doDeleteDetailKeranjang'])->name('usulan_barang_modals.delete_detail');
        Route::post('usulan_barang_modals/get_data_by_subkategori', [UsulanBelanjaModalController::class, 'getDataBySubKategori'])->name('usulan_barang_modals.get_data_by_subkategori');
        Route::get('/get-rkbu-data', [UsulanBelanjaModalController::class, 'getRkbuData']);
        Route::post('/add-to-cart/{id_usulan_barang}', [UsulanBelanjaModalController::class, 'addToCart'])->name('add-to-cart');
        Route::post('/add-to-cart-multiple-modal', [UsulanBelanjaModalController::class, 'addToCartMultiple'])->name('add-to-cart-multiple-modal');
        Route::get('/usulan_barang_modals/keranjang/{id}', [UsulanBelanjaModalController::class, 'keranjang'])->name('usulan_barang_modals.keranjang');
        Route::get('/usulan_barang_details/download-report-export/{id_usulan_barang}', [ExportUsulanBarangDetail::class, 'downloadReportExport'])
            ->name('usulan_barang_details.downloadReportExport');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangModal::class, 'downloadPDFCekBarjas'])->name('usulan_barang_modals.downloadPDF');
    });

    Route::prefix('cek-usulan-belanja-barjas')->group(function () {
        Route::resource('cek_usulan_barang_barjass', CekUsulanBarangController::class);
        Route::get('cek_usulan_barang_barjass/{usulanBarang}', [CekUsulanBarangController::class, 'show'])->name('cek_usulan_barang_barjass.show');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangBarjas::class, 'downloadPDFCekBarjas'])->name('cek_usulan_barang_barjass.downloadPDF');
    });

    Route::prefix('cek-usulan-belanja-modal')->group(function () {
        Route::resource('cek_usulan_barang_modals', CekUsulanBelanjaModalController::class);
        Route::get('cek_usulan_barang_modals/{usulanBarang}', [CekUsulanBelanjaModalController::class, 'show'])->name('cek_usulan_barang_modals.show');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangModal::class, 'downloadPDFCekBarjas'])->name('cek_usulan_barang_modals.downloadPDF');
    });

    Route::prefix('cek-usulan-belanja-persediaan')->group(function () {
        Route::resource('cek_usulan_barang_persediaans', CekUsulanBelanjaPersediaanController::class);
        Route::get('cek_usulan_barang_persediaans/{usulanBarang}', [CekUsulanBelanjaPersediaanController::class, 'show'])->name('cek_usulan_barang_persediaans.show');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangPersediaan::class, 'downloadPDFCekBarjas'])->name('cek_usulan_barang_persediaans.downloadPDF');
    });

    Route::prefix('validasi-usulan-belanja-pptk')->group(function () {
        Route::resource('validasi_usulan_barang_pptks', ValidasiUsulanBarangPptkController::class);
        Route::get('/validasi-usulan-barang-pptks/{id_sub_kategori_rkbu?}', [ValidasiUsulanBarangPptkController::class, 'index'])->name('validasi_usulan_barang_pptks.index');
        Route::get('/validasi_usulan_barang_pptks/keranjang/{id}', [ValidasiUsulanBarangPptkController::class, 'keranjang'])->name('validasi_usulan_barang_pptks.keranjang');
        Route::put('/validasi-usulan-barang-pptks/{no_usulan_barang}', [ValidasiUsulanBarangPptkController::class, 'updateValidasi'])->name('validasi_usulan_barang_pptks.updateValidasi');
        Route::get('/validasi-usulan-barang-pptks/show/{no_usulan_barang}', [ValidasiUsulanBarangPptkController::class, 'show'])->name('validasi_usulan_barang_pptks.show');
        Route::delete('/validasi-usulan-barang-pptks/{id_usulan_barang}', [ValidasiUsulanBarangPptkController::class, 'doDelete'])
            ->name('validasi_usulan_barang_pptks.delete');
        Route::delete('/validasi_usulan_barang_pptks.delete_detail/{id_usulan_barang_detail}', [ValidasiUsulanBarangPptkController::class, 'doDeleteDetailKeranjang'])->name('validasi_usulan_barang_pptks.delete_detail');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangBarjas::class, 'downloadPDFCekBarjas'])->name('validasi_usulan_barang_pptks.downloadPDF');
    });

    Route::prefix('validasi-usulan-modal-pptk')->group(function () {
        Route::resource('validasi_usulan_modal_pptks', ValidasiUsulanModalPptkController::class);
        Route::get('/validasi-usulan-modal-pptks/{id_sub_kategori_rkbu?}', [ValidasiUsulanModalPptkController::class, 'index'])->name('validasi_usulan_modal_pptks.index');
        Route::get('/validasi_usulan_modal_pptks/keranjang/{id}', [ValidasiUsulanModalPptkController::class, 'keranjang'])->name('validasi_usulan_modal_pptks.keranjang');
        Route::put('/validasi-usulan-modal-pptks/{no_usulan_barang}', [ValidasiUsulanModalPptkController::class, 'updateValidasi'])->name('validasi_usulan_modal_pptks.updateValidasi');
        Route::get('/validasi-usulan-modal-pptks/show/{no_usulan_barang}', [ValidasiUsulanModalPptkController::class, 'show'])->name('validasi_usulan_modal_pptks.show');
        Route::delete('/validasi-usulan-modal-pptks/{id_usulan_barang}', [ValidasiUsulanModalPptkController::class, 'doDelete'])
            ->name('validasi_usulan_modal_pptks.delete');
        Route::delete('/validasi_usulan_modal_pptks.delete_detail/{id_usulan_barang_detail}', [ValidasiUsulanModalPptkController::class, 'doDeleteDetailKeranjang'])->name('validasi_usulan_modal_pptks.delete_detail');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangModal::class, 'downloadPDFCekBarjas'])->name('validasi_usulan_modal_pptks.downloadPDF');
    });

    Route::prefix('validasi-usulan-persediaan-pptk')->group(function () {
        Route::resource('validasi_usulan_persediaan_pptks', ValidasiUsulanPersediaanPptkController::class);
        Route::get('/validasi-usulan-persediaan-pptks/{id_sub_kategori_rkbu?}', [ValidasiUsulanPersediaanPptkController::class, 'index'])->name('validasi_usulan_persediaan_pptks.index');
        Route::get('/validasi_usulan_persediaan_pptks/keranjang/{id}', [ValidasiUsulanPersediaanPptkController::class, 'keranjang'])->name('validasi_usulan_persediaan_pptks.keranjang');
        Route::put('/validasi-usulan-persediaan-pptks/{no_usulan_barang}', [ValidasiUsulanPersediaanPptkController::class, 'updateValidasi'])->name('validasi_usulan_persediaan_pptks.updateValidasi');
        Route::get('/validasi-usulan-persediaan-pptks/show/{no_usulan_barang}', [ValidasiUsulanPersediaanPptkController::class, 'show'])->name('validasi_usulan_persediaan_pptks.show');
        Route::delete('/validasi-usulan-persediaan-pptks/{id_usulan_barang}', [ValidasiUsulanPersediaanPptkController::class, 'doDelete'])
            ->name('validasi_usulan_persediaan_pptks.delete');
        Route::delete('/validasi_usulan_persediaan_pptks.delete_detail/{id_usulan_barang_detail}', [ValidasiUsulanPersediaanPptkController::class, 'doDeleteDetailKeranjang'])->name('validasi_usulan_persediaan_pptks.delete_detail');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangModal::class, 'downloadPDFCekBarjas'])->name('validasi_usulan_persediaan_pptks.downloadPDF');
    });

    Route::prefix('validasi-usulan-belanja-rka')->group(function () {
        Route::resource('validasi_usulan_barang_rkas', ValidasiUsulanBarangRkaController::class);
        Route::get('/validasi-usulan-barang-rkas/{id_sub_kategori_rkbu?}', [ValidasiUsulanBarangRkaController::class, 'index'])->name('validasi_usulan_barang_rkas.index');
        Route::get('/validasi_usulan_barang_rkas/keranjang/{id}', [ValidasiUsulanBarangRkaController::class, 'keranjang'])->name('validasi_usulan_barang_rkas.keranjang');
        Route::put('/validasi-usulan-barang-rkas/{no_usulan_barang}', [ValidasiUsulanBarangRkaController::class, 'updateValidasi'])->name('validasi_usulan_barang_rkas.updateValidasi');
        Route::get('/validasi-usulan-barang-rkas/show/{no_usulan_barang}', [ValidasiUsulanBarangRkaController::class, 'show'])->name('validasi_usulan_barang_rkas.show');
        Route::delete('/validasi-usulan-barang-rkas/{id_usulan_barang}', [ValidasiUsulanBarangRkaController::class, 'doDelete'])
            ->name('validasi_usulan_barang_rkas.delete');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangBarjas::class, 'downloadPDFCekBarjas'])->name('validasi_usulan_barang_rkas.downloadPDF');
    });

    Route::prefix('validasi-usulan-modal-rka')->group(function () {
        Route::resource('validasi_usulan_modal_rkas', ValidasiUsulanModalRkaController::class);
        Route::get('/validasi-usulan-modal-rkas/{id_sub_kategori_rkbu?}', [ValidasiUsulanModalRkaController::class, 'index'])->name('validasi_usulan_modal_rkas.index');
        Route::get('/validasi_usulan_modal_rkas/keranjang/{id}', [ValidasiUsulanModalRkaController::class, 'keranjang'])->name('validasi_usulan_modal_rkas.keranjang');
        Route::put('/validasi-usulan-modal-rkas/{no_usulan_barang}', [ValidasiUsulanModalRkaController::class, 'updateValidasi'])->name('validasi_usulan_modal_rkas.updateValidasi');
        Route::get('/validasi-usulan-modal-rkas/show/{no_usulan_barang}', [ValidasiUsulanModalRkaController::class, 'show'])->name('validasi_usulan_modal_rkas.show');
        Route::delete('/validasi-usulan-modal-rkas/{id_usulan_barang}', [ValidasiUsulanModalRkaController::class, 'doDelete'])
            ->name('validasi_usulan_modal_rkas.delete');
        Route::get('/downloadPDF/{id_usulan_modal}', [PdfControllerCekUsulanBarangModal::class, 'downloadPDFCekBarjas'])->name('validasi_usulan_modal_rkas.downloadPDF');
    });

    Route::prefix('validasi-usulan-persediaan-rka')->group(function () {
        Route::resource('validasi_usulan_persediaan_rkas', ValidasiUsulanPersediaanRkaController::class);
        Route::get('/validasi-usulan-persediaan-rkas/{id_sub_kategori_rkbu?}', [ValidasiUsulanPersediaanRkaController::class, 'index'])->name('validasi_usulan_persediaan_rkas.index');
        Route::get('/validasi_usulan_persediaan_rkas/keranjang/{id}', [ValidasiUsulanPersediaanRkaController::class, 'keranjang'])->name('validasi_usulan_persediaan_rkas.keranjang');
        Route::put('/validasi-usulan-persediaan-rkas/{no_usulan_barang}', [ValidasiUsulanPersediaanRkaController::class, 'updateValidasi'])->name('validasi_usulan_persediaan_rkas.updateValidasi');
        Route::get('/validasi-usulan-persediaan-rkas/show/{no_usulan_barang}', [ValidasiUsulanPersediaanRkaController::class, 'show'])->name('validasi_usulan_persediaan_rkas.show');
        Route::delete('/validasi-usulan-persediaan-rkas/{id_usulan_barang}', [ValidasiUsulanPersediaanRkaController::class, 'doDelete'])
            ->name('validasi_usulan_persediaan_rkas.delete');
        Route::get('/downloadPDF/{id_usulan_persediaan}', [PdfControllerCekUsulanBarangModal::class, 'downloadPDFCekBarjas'])->name('validasi_usulan_persediaan_rkas.downloadPDF');
    });

    Route::prefix('validasi-usulan-belanja-direktur')->group(function () {
        Route::resource('validasi_usulan_barang_direkturs', ValidasiUsulanBarangDirekturController::class);
        Route::get('/validasi-usulan-barang-direkturs/{id_sub_kategori_rkbu?}', [ValidasiUsulanBarangDirekturController::class, 'index'])->name('validasi_usulan_barang_direkturs.index');
        Route::get('/validasi_usulan_barang_direkturs/keranjang/{id}', [ValidasiUsulanBarangDirekturController::class, 'keranjang'])->name('validasi_usulan_barang_direkturs.keranjang');
        Route::put('/validasi-usulan-barang-direkturs/{no_usulan_barang}', [ValidasiUsulanBarangDirekturController::class, 'updateValidasi'])->name('validasi_usulan_barang_direkturs.updateValidasi');
        Route::get('/validasi-usulan-barang-direkturs/show/{no_usulan_barang}', [ValidasiUsulanBarangDirekturController::class, 'show'])->name('validasi_usulan_barang_direkturs.show');
        Route::delete('/validasi-usulan-barang-direkturs/{id_usulan_barang}', [ValidasiUsulanBarangDirekturController::class, 'doDelete'])
            ->name('validasi_usulan_barang_direkturs.delete');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangBarjas::class, 'downloadPDFCekBarjas'])->name('validasi_usulan_barang_direkturs.downloadPDF');
    });

    Route::prefix('validasi-usulan-modal-direktur')->group(function () {
        Route::resource('validasi_usulan_modal_direkturs', ValidasiUsulanModalDirekturController::class);
        Route::get('/validasi-usulan-modal-direkturs/{id_sub_kategori_rkbu?}', [ValidasiUsulanModalDirekturController::class, 'index'])->name('validasi_usulan_modal_direkturs.index');
        Route::get('/validasi_usulan_modal_direkturs/keranjang/{id}', [ValidasiUsulanModalDirekturController::class, 'keranjang'])->name('validasi_usulan_modal_direkturs.keranjang');
        Route::put('/validasi-usulan-modal-direkturs/{no_usulan_barang}', [ValidasiUsulanModalDirekturController::class, 'updateValidasi'])->name('validasi_usulan_modal_direkturs.updateValidasi');
        Route::get('/validasi-usulan-modal-direkturs/show/{no_usulan_barang}', [ValidasiUsulanModalDirekturController::class, 'show'])->name('validasi_usulan_modal_direkturs.show');
        Route::delete('/validasi-usulan-modal-direkturs/{id_usulan_barang}', [ValidasiUsulanModalDirekturController::class, 'doDelete'])
            ->name('validasi_usulan_modal_direkturs.delete');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangBarjas::class, 'downloadPDFCekBarjas'])->name('validasi_usulan_modal_direkturs.downloadPDF');
    });

    Route::prefix('validasi-usulan-persediaan-direktur')->group(function () {
        Route::resource('validasi_usulan_persediaan_direkturs', ValidasiUsulanPersediaanDirekturController::class)->parameters([
            'validasi_usulan_persediaan_direkturs' => 'id'
        ]);
        Route::get('/validasi-usulan-persediaan-direkturs/{id_sub_kategori_rkbu?}', [ValidasiUsulanPersediaanDirekturController::class, 'index'])->name('validasi_usulan_persediaan_direkturs.index');
        Route::get('/validasi_usulan_persediaan_direkturs/keranjang/{id}', [ValidasiUsulanPersediaanDirekturController::class, 'keranjang'])->name('validasi_usulan_persediaan_direkturs.keranjang');
        Route::put('/validasi-usulan-persediaan-direkturs/{no_usulan_barang}', [ValidasiUsulanPersediaanDirekturController::class, 'updateValidasi'])->name('validasi_usulan_persediaan_direkturs.updateValidasi');
        Route::get('/validasi-usulan-persediaan-direkturs/show/{no_usulan_barang}', [ValidasiUsulanPersediaanDirekturController::class, 'show'])->name('validasi_usulan_persediaan_direkturs.show');
        Route::delete('/validasi-usulan-persediaan-direkturs/{id_usulan_barang}', [ValidasiUsulanPersediaanDirekturController::class, 'doDelete'])
            ->name('validasi_usulan_persediaan_direkturs.delete');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangBarjas::class, 'downloadPDFCekBarjas'])->name('validasi_usulan_persediaan_direkturs.downloadPDF');
    });

    Route::prefix('usulan-spj-barjas')->group(function () {
        Route::resource('usulan_spj_barjass', UsulanSpjBarjasController::class)->parameters(['usulan_spj_barjass' => 'id_spj']);
        Route::post('/usulan-spj/add', [UsulanSpjBarjasController::class, 'add'])->name('usulan_spj_barjass.add');
        Route::post('/usulan-spj/store', [UsulanSpjBarjasController::class, 'store'])->name('usulan_spj_barjass.store');
        Route::put('/usulan-spj-barjass/{id_spj}/update-status', [UsulanSpjBarjasController::class, 'updateStatus'])
            ->name('usulan_spj_barjass.updateStatus');
        Route::get('/usulan_spj_barjass/show/{id_spj}', [UsulanSpjBarjasController::class, 'show'])->name('usulan_spj_barjas.show');
        Route::delete('/usulan_spj_barjass/{id_spj}', [UsulanSpjBarjasController::class, 'destroy'])->name('usulan_spj_barjass.destroy');
        Route::delete('/usulan_spj_detail_barjass/{id_spj_detail}', [UsulanSpjBarjasController::class, 'delete'])->name('usulan_spj_barjass.delete');
    });

    Route::prefix('usulan-spj-modal')->group(function () {
        Route::resource('usulan_spj_modals', UsulanSpjModalController::class)->parameters(['usulan_spj_modals' => 'id_spj']);
        Route::post('/usulan-spj/add', [UsulanSpjModalController::class, 'add'])->name('usulan_spj_modals.add');
        Route::post('/usulan-spj/store', [UsulanSpjModalController::class, 'store'])->name('usulan_spj_modals.store');
        Route::put('/usulan-spj-barjass/{id_spj}/update-status', [UsulanSpjModalController::class, 'updateStatus'])
            ->name('usulan_spj_modals.updateStatus');
        Route::get('/usulan_spj_modals/show/{id_spj}', [UsulanSpjModalController::class, 'show'])->name('usulan_spj_modals.show');
        Route::delete('/usulan_spj_modals/{id_spj}', [UsulanSpjModalController::class, 'destroy'])->name('usulan_spj_modals.destroy');
        Route::delete('/usulan_spj_detail_barjass/{id_spj_detail}', [UsulanSpjModalController::class, 'delete'])->name('usulan_spj_modals.delete');
    });

    Route::prefix('usulan-spj-persediaan')->group(function () {
        Route::resource('usulan_spj_persediaans', UsulanSpjPersediaanController::class)->parameters(['usulan_spj_persediaans' => 'id_spj']);
        Route::post('/usulan-spj/add', [UsulanSpjPersediaanController::class, 'add'])->name('usulan_spj_persediaans.add');
        Route::post('/usulan-spj/store', [UsulanSpjPersediaanController::class, 'store'])->name('usulan_spj_persediaans.store');
        Route::put('/usulan-spj-persediaans/{id_spj}/update-status', [UsulanSpjPersediaanController::class, 'updateStatus'])
            ->name('usulan_spj_persediaans.updateStatus');
        Route::get('/usulan_spj_persediaans/show/{id_spj}', [UsulanSpjPersediaanController::class, 'show'])->name('usulan_spj_persediaans.show');
        Route::delete('/usulan_spj_persediaans/{id_spj}', [UsulanSpjPersediaanController::class, 'destroy'])->name('usulan_spj_persediaans.destroy');
        Route::delete('/usulan_spj_persediaans/{id_spj_detail}', [UsulanSpjPersediaanController::class, 'delete'])->name('usulan_spj_persediaans.delete');
    });

    Route::prefix('master-spj')->group(function () {
        Route::resource('master_spjs', MasterSpjController::class);
        // Route::get('{id}/show', [MasterSpjController::class, 'show'])->name('master_spjs.show');
        Route::get('/master_spjs/editkomponen/{id}', [MasterSpjController::class, 'editkomponen'])->name('master_spjs.editkomponen');
        Route::put('/master_spjs/{id_spj_detail}', [MasterSpjController::class, 'update'])->name('master_spjs.update');
        Route::put('/master-spj/{id}/update-surat-pesanan', [MasterSpjController::class, 'updateSuratPesanan'])
            ->name('master_spjs.updateSuratPesanan');
        Route::put('/master-spjs/{id}/update-barang-datang', [MasterSpjController::class, 'updateBarangDatang'])
            ->name('master_spjs.updateBarangDatang');
        Route::put('/master-spj/{id}/update-tukar-faktur', [MasterSpjController::class, 'tukarfaktur'])
            ->name('master_spjs.tukarfaktur');
        Route::get('/downloadPDF/{id_usulan_barang}', [PdfControllerCekUsulanBarangBarjas::class, 'downloadPDFCekBarjas'])->name('master_spjs.downloadPDF');
    });
});
