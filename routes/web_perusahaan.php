<?php

use App\Http\Controllers\Master\PerusahaanController;
use App\Http\Controllers\Setting\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'setting', 'middleware' => ['auth']], function () {

    // profile
    Route::get('profile', [ProfileController::class, 'perusahaan'])->name('perusahaan.profile');
    Route::put('perusahaan', [ProfileController::class, 'update_perusahaan'])->name('perusahaan.update.save');
    Route::put('profile/avatar', [PerusahaanController::class, 'update_avatar']);
    Route::put('profile/password', [PerusahaanController::class, 'update_password']);
});
