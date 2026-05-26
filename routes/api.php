<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\GuruController;

Route::apiResource('siswa', SiswaController::class);
Route::apiResource('guru', GuruController::class);
