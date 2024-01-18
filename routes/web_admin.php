<?php

use App\Http\Controllers\Master\BeritaController;
use App\Http\Controllers\Master\DosenCircleController;
use App\Http\Controllers\Master\DosenController;
use App\Http\Controllers\Master\JenisMagangController;
use App\Http\Controllers\Master\JurusanController;
use App\Http\Controllers\Master\KategoriController;
use App\Http\Controllers\Master\KegiatanController;
use App\Http\Controllers\Master\MahasiswaController;
use App\Http\Controllers\Master\PendaftaranController;
use App\Http\Controllers\Master\PeriodeController;
use App\Http\Controllers\Master\PerusahaanController;
use App\Http\Controllers\Master\ProdiController;
use App\Http\Controllers\Master\TahapanProposalController;
use App\Http\Controllers\Master\TipeKegiatanController;
use App\Http\Controllers\Proposal\AdminHasilSeminarProposalController;
use App\Http\Controllers\Proposal\AdminPendaftaranSemproController;
use App\Http\Controllers\Proposal\AdminProposalMahasiswaBermasalahController;
use App\Http\Controllers\Proposal\AdminProposalMahasiswaController;
use App\Http\Controllers\Proposal\AdminUsulanTopikController;
use App\Http\Controllers\Report\LogActivityController;
use App\Http\Controllers\Setting\AccountController;
use App\Http\Controllers\Setting\GroupController;
use App\Http\Controllers\Setting\MenuController;
use App\Http\Controllers\Setting\ProfileController;
use App\Http\Controllers\Setting\UserController;
use App\Http\Controllers\Transaction\QuotaDosenController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'master', 'middleware' => ['auth']], function () {

    // Jurusan
    Route::resource('jurusan', JurusanController::class)->parameter('jurusan', 'id');
    Route::post('jurusan/list', [JurusanController::class, 'list']);
    Route::get('jurusan/{id}/delete', [JurusanController::class, 'confirm']);

    // Prodi
    Route::resource('prodi', ProdiController::class)->parameter('prodi', 'id');
    Route::post('prodi/list', [ProdiController::class, 'list']);
    Route::get('prodi/{id}/delete', [ProdiController::class, 'confirm']);

    // Tipe Kegiatan
    Route::resource('tipe_kegiatan', TipeKegiatanController::class)->parameter('tipe_kegiatan', 'id');
    Route::post('tipe_kegiatan/list', [TipeKegiatanController::class, 'list']);
    Route::get('tipe_kegiatan/{id}/delete', [TipeKegiatanController::class, 'confirm']);

    // Jenis Magang
    Route::resource('jenis_magang', JenisMagangController::class)->parameter('jenis_magang', 'id');
    Route::post('jenis_magang/list', [JenisMagangController::class, 'list']);
    Route::get('jenis_magang/{id}/delete', [JenisMagangController::class, 'confirm']);

    // Periode
    Route::resource('periode', PeriodeController::class)->parameter('periode', 'id');
    Route::post('periode/list', [PeriodeController::class, 'list']);
    Route::get('periode/{id}/delete', [PeriodeController::class, 'confirm']);
    Route::get('periode/{id}/confirm_active', [PeriodeController::class, 'confirm_active']);
    Route::put('periode/{id}/active', [PeriodeController::class, 'set_active']);

    // Perusahaan
    Route::resource('perusahaan', PerusahaanController::class)->parameter('perusahaan', 'id');
    Route::post('perusahaan/list', [PerusahaanController::class, 'list']);
    Route::get('perusahaan/{id}/delete', [PerusahaanController::class, 'confirm']);
    Route::get('perusahaan/{id}/confirm_approve', [PerusahaanController::class, 'confirm_approve']);
    Route::get('perusahaan/{id}/confirm_reject', [PerusahaanController::class, 'confirm_reject']);
    Route::put('perusahaan/{id}/approve', [PerusahaanController::class, 'approve']);
    Route::put('perusahaan/{id}/reject', [PerusahaanController::class, 'reject']);

    // Kegiatan
    Route::get('perusahaan/{id}/kegiatan', [KegiatanController::class, 'index']);
    Route::post('perusahaan/{id}/kegiatan/list', [KegiatanController::class, 'list']);
    Route::get('perusahaan/{id}/kegiatan/create', [KegiatanController::class, 'create']);
    Route::post('perusahaan/{id}/kegiatan/store', [KegiatanController::class, 'store']);
    Route::get('perusahaan/{id}/kegiatan/{kegiatan_id}/edit', [KegiatanController::class, 'edit']);
    Route::get('perusahaan/{id}/kegiatan/{kegiatan_id}/show', [KegiatanController::class, 'show']);
    Route::put('perusahaan/{id}/kegiatan/{kegiatan_id}/update', [KegiatanController::class, 'update']);
    Route::get('perusahaan/{id}/kegiatan/{kegiatan_id}/delete', [KegiatanController::class, 'confirm']);
    Route::delete('perusahaan/{id}/kegiatan/{kegiatan_id}/destroy', [KegiatanController::class, 'destroy']);
    Route::get('perusahaan/{id}/kegiatan/{kegiatan_id}/confirm_approve', [KegiatanController::class, 'confirm_approve']);
    Route::get('perusahaan/{id}/kegiatan/{kegiatan_id}/confirm_reject', [KegiatanController::class, 'confirm_reject']);
    Route::put('perusahaan/{id}/kegiatan/{kegiatan_id}/approve', [KegiatanController::class, 'approve']);
    Route::put('perusahaan/{id}/kegiatan/{kegiatan_id}/reject', [KegiatanController::class, 'reject']);

    // Mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class)->parameter('mahasiswa', 'id');
    Route::post('mahasiswa/list', [MahasiswaController::class, 'list']);
    Route::get('mahasiswa/{id}/delete', [MahasiswaController::class, 'confirm']);


    //pendaftaran
    Route::resource('pendaftaran', PendaftaranController::class)->parameter('pendaftaran', 'id');
    Route::post('pendaftaran/list', [PendaftaranController::class, 'list']);
    Route::get('pendaftaran/{id}/delete', [PendaftaranController::class, 'confirm']);
});

Route::group(['prefix' => 'setting', 'middleware' => ['auth']], function () {
    //group
    Route::resource('group', GroupController::class)->parameter('group', 'id');
    Route::post('group/list', [GroupController::class, 'list']);
    Route::get('group/{id}/delete', [GroupController::class, 'confirm']);
    Route::put('group/{id}/menu', [GroupController::class, 'menu_save']);

    //menu
    Route::resource('menu', MenuController::class)->parameter('menu', 'id');
    Route::post('menu/list', [MenuController::class, 'list']);
    Route::get('menu/{id}/delete', [MenuController::class, 'confirm']);

    //user
    Route::resource('user', UserController::class)->parameter('user', 'id');
    Route::post('user/list', [UserController::class, 'list']);
    Route::get('user/{id}/delete', [UserController::class, 'confirm']);

});
