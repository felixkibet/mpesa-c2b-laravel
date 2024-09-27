<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MpesaController;

Route::get('/', [MpesaController::class, 'index']);
Route::get('/generate-token', [MpesaController::class, 'getAccessToken'])->name('generate.token');
Route::post('/register-urls', [MpesaController::class, 'registerUrls'])->name('register.urls');
Route::post('/simulate', [MpesaController::class, 'simulateTransaction'])->name('simulate.transaction');


Route::post('/validate', [MpesaController::class, 'validateTransaction'])->name('validate');
Route::post('/confirm', [MpesaController::class, 'confirmTransaction'])->name('confirm');

