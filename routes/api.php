<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Denda;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\History;
use App\Http\Controllers\KategoriBukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UbahPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('cors')->group(function () {
    Route::post('/api-register', [Auth::class, 'register']);
    Route::post('/api-login', [Auth::class, 'do_login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/api-kategori', [KategoriBukuController::class, 'get']);

        Route::get('/api-buku', [BukuController::class, 'get']);
        Route::post('/api-search-buku', [BukuController::class, 'search']);
        Route::get('/api-buku-by-Uuidcategory/{params}', [BukuController::class, 'getByUuid']);

        Route::get('/api-ebook', [EbookController::class, 'get']);
        Route::get('/api-search-ebook', [EbookController::class, 'search']);

        Route::get('/api-get-peminjaman', [PeminjamanController::class, 'get']);
        Route::post('/api-add-peminjaman', [PeminjamanController::class, 'store']);

        Route::get('/api-get-histori', [History::class, 'get']);

        Route::get('/api-get-denda', [Denda::class, 'get']);

        Route::post('/do-ubahpassword/{params}', [UbahPassword::class, 'update']);

        Route::get('/api-logout', [Auth::class, 'revoke']);
    });
});
