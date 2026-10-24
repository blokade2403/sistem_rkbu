<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\MasterBackend\SettingInput\AnggaranController;
use App\Http\Controllers\MasterBackend\SettingInput\JudulHeaderController;
use App\Http\Controllers\MasterBackend\SettingInput\KomponenController;
use App\Http\Controllers\MasterBackend\SettingInput\PpnController;
use App\Http\Controllers\MasterBackend\SettingInput\PaguAnggaranController;
use App\Http\Controllers\MasterBackend\SettingInput\TahunAnggaranController;
use App\Http\Controllers\MasterBackend\SettingInput\TargetSpsController;
use App\Http\Controllers\MasterBackend\SettingInput\UraianDuaController;
use App\Http\Controllers\MasterBackend\SettingInput\UraianSatuController;
use App\Http\Controllers\MasterBackend\SettingRkbu\AktivitasController;
use App\Http\Controllers\MasterBackend\SettingRkbu\JenisBelanjaController;
use App\Http\Controllers\MasterBackend\SettingRkbu\JenisKategoriRkbuController;
use App\Http\Controllers\MasterBackend\SettingRkbu\KategoriRekeningController;
use App\Http\Controllers\MasterBackend\SettingRkbu\KategoriRkbuController;
use App\Http\Controllers\MasterBackend\SettingRkbu\KegiatanController;
use App\Http\Controllers\MasterBackend\SettingRkbu\ObyekBelanjaController;
use App\Http\Controllers\MasterBackend\SettingRkbu\ProgramController;
use App\Http\Controllers\MasterBackend\SettingRkbu\RekeningBelanjaController;
use App\Http\Controllers\MasterBackend\SettingRkbu\SubKategoriRekeningController;
use App\Http\Controllers\MasterBackend\SettingRkbu\SubKategoriRkbuController;
use App\Http\Controllers\MasterBackend\SettingRkbu\SubKegiatanController;
use App\Http\Controllers\MasterBackend\SettingRkbu\SumberDanaController;
use App\Http\Controllers\MasterBackend\SettingUser\FaseController;
use App\Http\Controllers\MasterBackend\SettingUser\PositionAssignmentController;
use App\Http\Controllers\MasterBackend\SettingUser\PositionController;
use App\Http\Controllers\MasterBackend\SettingUser\RoleController;
use App\Http\Controllers\MasterBackend\SettingUser\RoleUserController;
use App\Http\Controllers\MasterBackend\SettingUser\UnitController;
use App\Http\Controllers\MasterBackend\UserProfil\UserController;
use App\Http\Controllers\MasterBackend\UserProfil\UserPptkController;
use App\Http\Controllers\MasterBackend\ValidatorRole\UserValidatorRoleController;
use App\Http\Controllers\MasterBackend\ValidatorRole\ValidatorRoleController;
use App\Http\Controllers\Rkbu\Approval\ApprovalRkbuController;
use App\Http\Controllers\Rkbu\RkbuBarjasController;
use App\Http\Controllers\Rkbu\RkbuController;
use App\Http\Controllers\Rkbu\RkbuModalAlkesController;
use App\Http\Controllers\Rkbu\RkbuModalKantorController;
use App\Http\Controllers\Rkbu\RkbuModuleController;
use App\Http\Controllers\Rkbu\RkbuPersediaanController;
use App\Http\Middleware\EnforceRkbuPhaseAccess;
use App\Http\Controllers\UsulanBelanja\UsulanBelanjaController;
use App\Http\Controllers\UsulanBelanja\SpjUsulanBelanjaController;
use App\Http\Controllers\UsulanBelanja\ValidasiUsulanBelanjaController;
use App\Http\Controllers\Pengadaan\PerusahaanController;
use App\Http\Controllers\WorkFlow\ApprovalController;
use App\Http\Controllers\WorkFlow\PositionSubKategoriRkbuController;
use App\Http\Controllers\WorkFlow\PptkKategoriController;
use App\Http\Controllers\Workflow\WorkflowApprovalController;
use App\Http\Controllers\WorkFlow\WorkflowController;
use App\Http\Controllers\WorkFlow\WorkflowStepController;
use App\Http\Controllers\WorkFlow\WorkflowStepPptkController;
use App\Http\Controllers\WorkFlow\WorkflowStepSubKategoriRkbuController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('/', function () {
        return redirect()->route('login.form');
    });
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('perusahaans', PerusahaanController::class);
    Route::prefix('setting-user')->group(function () {
        Route::resource('fases', FaseController::class);
        Route::resource('units', UnitController::class);
        Route::resource('positions', PositionController::class);
        Route::resource('position_assignments', PositionAssignmentController::class)
            ->only(['index', 'store', 'update', 'destroy']);
        Route::resource('roles', RoleController::class);
        Route::get('role-user', [RoleUserController::class, 'index'])->name('role_user.index');
        Route::post('role-user/assign', [RoleUserController::class, 'assign'])->name(
            'role_user.assign',
        );
        Route::delete('role-user/{user}/{role}', [RoleUserController::class, 'remove'])->name(
            'role_user.remove',
        );
    });

    Route::prefix('user-profil')->group(function () {
        Route::resource('users', UserController::class);
        Route::put('users/{id}/update-status', [UserController::class, 'updateStatus'])->name(
            'update.status',
        );
        // Route::resource('user-pptk', UserPptkController::class);
        Route::resource('user-validator-roles', UserValidatorRoleController::class);
        Route::resource('validator-roles', ValidatorRoleController::class);
    });

    Route::prefix('workflow')->group(function () {
        Route::resource(
            'workflow-step-sub-kategori-rkbu',
            WorkflowStepSubKategoriRkbuController::class,
        );
        Route::resource('workflows', WorkflowController::class);
        Route::resource('workflow_steps', WorkflowStepController::class);
        // Route::resource('workflow_step_pptks', WorkflowStepPptkController::class);
        Route::resource('position_sub_kategori_rkbu', PositionSubKategoriRkbuController::class);
        // Route::resource('pptk_kategori', PptkKategoriController::class);
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::get('/approvals/{approval}', [ApprovalController::class, 'show'])->name(
            'approvals.show',
        );
        Route::post('/approvals/{approval}/approve', [ApprovalController::class, 'approve'])->name(
            'approvals.approve',
        );
        Route::post('/approvals/{approval}/reject', [ApprovalController::class, 'reject'])->name(
            'approvals.reject',
        );
    });

    Route::prefix('workflow')
        ->name('workflowapproval.')
        ->group(function () {

            Route::get(
                '/approval',
                [WorkflowApprovalController::class, 'index']
            )->name('index');

            Route::get(
                '/approval/data',
                [WorkflowApprovalController::class, 'getData']
            )->name('data');
            Route::post(
                '/approval/bulk-action',
                [WorkflowApprovalController::class, 'bulkAction']
            )->name('bulk-action');

            Route::get(
                '/approval/print-all',
                [WorkflowApprovalController::class, 'printAll']
            )->name('print-all');

            Route::get(
                '/approval/{id}',
                [WorkflowApprovalController::class, 'show']
            )->name('show');
            Route::get(
                '/approval/{id}/print',
                [WorkflowApprovalController::class, 'print']
            )->name('print');
            Route::post(
                '/approval/{id}/update-rkbu',
                [WorkflowApprovalController::class, 'updateRkbu']
            )->name('update-rkbu');
            Route::post(
                '/approval/{id}/approve',
                [WorkflowApprovalController::class, 'approve']
            )->name('approve');
            Route::post(
                '/approval/{id}/revision',
                [WorkflowApprovalController::class, 'revision']
            )->name('revision');

            Route::post(
                '/approval/{id}/reject',
                [WorkflowApprovalController::class, 'reject']
            )->name('reject');
        });

    Route::prefix('setting-input')->group(function () {
        Route::resource('tahun_anggarans', TahunAnggaranController::class);
        Route::resource('komponens', KomponenController::class);
        Route::resource('uraian_satus', UraianSatuController::class);
        Route::resource('uraian_duas', UraianDuaController::class);
        Route::resource('ppns', PpnController::class);
        Route::resource('judul_headers', JudulHeaderController::class);
        Route::resource('anggarans', AnggaranController::class);
        Route::get('pagu-anggaran/{paguAnggaran}/history', [PaguAnggaranController::class, 'history'])->name('pagu_anggaran.history');
        Route::resource('pagu-anggaran', PaguAnggaranController::class)
            ->parameters(['pagu-anggaran' => 'paguAnggaran'])
            ->names('pagu_anggaran')
            ->except(['show', 'destroy']);
        Route::resource('target_sps', TargetSpsController::class);
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
        Route::resource('rekening_belanjas', RekeningBelanjaController::class);
        Route::resource('sub_kategori_rekenings', SubKategoriRekeningController::class);
        Route::post('sub_kategori_rkbus/import', [SubKategoriRkbuController::class, 'import'])
            ->name('sub_kategori_rkbus.import');
        Route::resource('sub_kategori_rkbus', SubKategoriRkbuController::class);
    });

    Route::prefix('usulan-belanja')->name('usulan-belanja.')
        ->group(function () {
            Route::get('/', [
                UsulanBelanjaController::class,
                'index',
            ])->name('index');

            Route::get('/data', [
                UsulanBelanjaController::class,
                'data',
            ])->name('data');

            Route::get('/export', [
                UsulanBelanjaController::class,
                'export',
            ])->name('export');

            Route::get('/rekap', [
                UsulanBelanjaController::class,
                'rekap',
            ])->name('rekap');

            Route::get('/rekap/{usulanBelanja}', [
                UsulanBelanjaController::class,
                'showRekap',
            ])->name('rekap.show');

            Route::get('/spj', [SpjUsulanBelanjaController::class, 'index'])
                ->name('spj.index');
            Route::get('/spj/penyedia', [SpjUsulanBelanjaController::class, 'byProvider'])
                ->name('spj.providers');
            Route::get('/spj/{spj}', [SpjUsulanBelanjaController::class, 'show'])
                ->name('spj.show');
            Route::put('/spj/{spj}/item/{detail}', [SpjUsulanBelanjaController::class, 'updateItem'])
                ->name('spj.items.update');
            Route::post('/spj/{spj}/item', [SpjUsulanBelanjaController::class, 'addItem'])
                ->name('spj.items.add');
            Route::delete('/spj/{spj}/item/{detail}', [SpjUsulanBelanjaController::class, 'deleteItem'])
                ->name('spj.items.delete');
            Route::get('/{usulanBelanja}/spj/create', [SpjUsulanBelanjaController::class, 'create'])
                ->name('spj.create');
            Route::post('/{usulanBelanja}/spj', [SpjUsulanBelanjaController::class, 'store'])
                ->name('spj.store');

            Route::get('/{usulanBelanja}/print', [
                UsulanBelanjaController::class,
                'print',
            ])->name('print');

            Route::get('/{usulanBelanja}/print-surat-perintah', [
                UsulanBelanjaController::class,
                'printSuratPerintah',
            ])->name('print-surat-perintah');

            Route::get('/create', [
                UsulanBelanjaController::class,
                'create',
            ])->name('create');

            Route::post('/', [
                UsulanBelanjaController::class,
                'store',
            ])->name('store');

            Route::post('/{usulanBelanja}/items', [
                UsulanBelanjaController::class,
                'addItem',
            ])->name('items.store');

            Route::put('/{usulanBelanja}/items', [
                UsulanBelanjaController::class,
                'updateItems',
            ])->name('items.update');

            Route::delete('/{usulanBelanja}/items/{item}', [
                UsulanBelanjaController::class,
                'deleteItem',
            ])->name('items.destroy');

            Route::delete('/{usulanBelanja}', [
                UsulanBelanjaController::class,
                'delete',
            ])->name('destroy');

            Route::get('/validasi', [
                ValidasiUsulanBelanjaController::class,
                'index',
            ])->name('validasi.index');

            Route::get('/{usulanBelanja}', [
                UsulanBelanjaController::class,
                'show',
            ])->name('show');

            Route::post('/{usulanBelanja}/ajukan', [
                UsulanBelanjaController::class,
                'ajukan',
            ])->name('ajukan');

            Route::post('/{usulanBelanja}/validasi', [
                ValidasiUsulanBelanjaController::class,
                'proses',
            ])->name('validasi.proses');
        });

    Route::prefix('rkbu')
        ->name('rkbu.')
        ->middleware(EnforceRkbuPhaseAccess::class)
        ->group(function () {

            Route::get('/', [RkbuController::class, 'index'])
                ->name('index');
            Route::get('/barjas/print', [RkbuBarjasController::class, 'print'])
                ->name('barjas.print');
            Route::resource('barjas', RkbuBarjasController::class);
            Route::post('/barjas/{id}/submit', [RkbuBarjasController::class, 'submit'])
                ->name('barjas.submit');
            Route::post('/barjas/bulk-submit', [RkbuBarjasController::class, 'bulkSubmit'])
                ->name('barjas.bulk-submit');

            Route::get('/persediaan/print', [RkbuPersediaanController::class, 'print'])
                ->name('persediaan.print');
            Route::resource('persediaan', RkbuPersediaanController::class);
            Route::post('/persediaan/{id}/submit', [RkbuPersediaanController::class, 'submit'])
                ->name('persediaan.submit');
            Route::post('/persediaan/bulk-submit', [RkbuPersediaanController::class, 'bulkSubmit'])
                ->name('persediaan.bulk-submit');

            Route::get('/modal_kantor/print', [RkbuModalKantorController::class, 'print'])
                ->name('modal_kantor.print');
            Route::resource('modal_kantor', RkbuModalKantorController::class);
            Route::post('/modal_kantor/{id}/submit', [RkbuModalKantorController::class, 'submit'])
                ->name('modal_kantor.submit');
            Route::post('/modal_kantor/bulk-submit', [RkbuModalKantorController::class, 'bulkSubmit'])
                ->name('modal_kantor.bulk-submit');

            Route::get('/modal_alkes/print', [RkbuModalAlkesController::class, 'print'])
                ->name('modal_alkes.print');
            Route::resource('modal_alkes', RkbuModalAlkesController::class);
            Route::post('/modal_alkes/{id}/submit', [RkbuModalAlkesController::class, 'submit'])
                ->name('modal_alkes.submit');
            Route::post('/modal_alkes/bulk-submit', [RkbuModalAlkesController::class, 'bulkSubmit'])
                ->name('modal_alkes.bulk-submit');

            Route::post('/pegawai/{id}/submit', [RkbuModuleController::class, 'submit'])
                ->defaults('rkbuModule', 'pegawai')
                ->name('pegawai.submit');
            Route::post('/pegawai/bulk-submit', [RkbuModuleController::class, 'bulkSubmit'])
                ->defaults('rkbuModule', 'pegawai')
                ->name('pegawai.bulk-submit');
            Route::get('/pegawai', [RkbuModuleController::class, 'index'])
                ->defaults('rkbuModule', 'pegawai')
                ->name('pegawai.index');
            Route::get('/pegawai/create', [RkbuModuleController::class, 'create'])
                ->defaults('rkbuModule', 'pegawai')
                ->name('pegawai.create');
            Route::post('/pegawai', [RkbuModuleController::class, 'store'])
                ->defaults('rkbuModule', 'pegawai')
                ->name('pegawai.store');
            Route::get('/pegawai/{id}/edit', [RkbuModuleController::class, 'edit'])
                ->defaults('rkbuModule', 'pegawai')
                ->whereUuid('id')
                ->name('pegawai.edit');
            Route::get('/pegawai/{id}', [RkbuModuleController::class, 'show'])
                ->defaults('rkbuModule', 'pegawai')
                ->whereUuid('id')
                ->name('pegawai.show');
            Route::put('/pegawai/{id}', [RkbuModuleController::class, 'update'])
                ->defaults('rkbuModule', 'pegawai')
                ->whereUuid('id')
                ->name('pegawai.update');
            Route::delete('/pegawai/{id}', [RkbuModuleController::class, 'destroy'])
                ->defaults('rkbuModule', 'pegawai')
                ->whereUuid('id')
                ->name('pegawai.destroy');

            // foreach (
            //     [
            //         'pegawai' => 'pegawai',
            //         'persediaan' => 'persediaan',
            //         'modal-kantor' => 'modal_kantor',
            //         'modal-alkes' => 'modal_alkes',
            //     ] as $path => $module
            // ) {
            //     Route::post($path . '/{id}/submit', [RkbuModuleController::class, 'submit'])
            //         ->defaults('rkbuModule', $module)
            //         ->name($path . '.submit');
            //     Route::post($path . '/bulk-submit', [RkbuModuleController::class, 'bulkSubmit'])
            //         ->defaults('rkbuModule', $module)
            //         ->name($path . '.bulk-submit');

            //     Route::get($path, [RkbuModuleController::class, 'index'])
            //         ->defaults('rkbuModule', $module)
            //         ->name($path . '.index');
            //     Route::get($path . '/create', [RkbuModuleController::class, 'create'])
            //         ->defaults('rkbuModule', $module)
            //         ->name($path . '.create');
            //     Route::post($path, [RkbuModuleController::class, 'store'])
            //         ->defaults('rkbuModule', $module)
            //         ->name($path . '.store');
            //     Route::get($path . '/{id}/edit', [RkbuModuleController::class, 'edit'])
            //         ->defaults('rkbuModule', $module)
            //         ->whereUuid('id')
            //         ->name($path . '.edit');
            //     Route::get($path . '/{id}', [RkbuModuleController::class, 'show'])
            //         ->defaults('rkbuModule', $module)
            //         ->whereUuid('id')
            //         ->name($path . '.show');
            //     Route::put($path . '/{id}', [RkbuModuleController::class, 'update'])
            //         ->defaults('rkbuModule', $module)
            //         ->whereUuid('id')
            //         ->name($path . '.update');
            //     Route::delete($path . '/{id}', [RkbuModuleController::class, 'destroy'])
            //         ->defaults('rkbuModule', $module)
            //         ->whereUuid('id')
            //         ->name($path . '.destroy');
            // }
        });

    Route::prefix('approvalrkbu')->group(function () {

        Route::post(
            '/{id}/approve',
            [ApprovalRkbuController::class, 'approve']
        )
            ->name('approval.approve');

        Route::post(
            '/{id}/revision',
            [ApprovalRkbuController::class, 'revision']
        )
            ->name('approval.revision');

        Route::post(
            '/{id}/reject',
            [ApprovalRkbuController::class, 'reject']
        )
            ->name('approval.reject');
    });
});
