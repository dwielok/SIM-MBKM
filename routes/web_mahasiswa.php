<?php

use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\Profile\MahasiswaProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'mahasiswa', 'middleware' => ['auth']], function () {



    Route::get('profile', [MahasiswaProfileController::class, 'index']);
    Route::put('profile', [MahasiswaProfileController::class, 'update']);
});

Route::resource('perusahaan', PerusahaanController::class)->parameter('perusahaan', 'id');
Route::post('perusahaan/list', [PerusahaanController::class, 'list']);
