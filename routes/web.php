<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {

    Route::get('/', 'Dashboard@index')->name('home.index');

    Route::group(['prefix' => 'login', 'middleware' => ['guest'], 'as' => 'login.'], function () {
        Route::get('/login-akun', 'Auth@show')->name('login-akun');
        Route::post('/login-proses', 'Auth@login_proses')->name('login-proses');
    });

    Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'], function () {
        Route::get('/dashboard-admin', 'Dashboard@dashboard')->name('dashboard-admin');
        Route::get('/chart', 'Dashboard@areaChart')->name('chart');

        Route::get('/data-anggota', 'Anggota@index')->name('data-anggota');
        Route::get('/get-data-anggota', 'Anggota@get')->name('get-data-anggota');
        Route::post('/update-data-anggota/{params}', 'Anggota@update')->name('update-data-anggota');
        Route::delete('/delete-data-anggota/{params}', 'Anggota@delete')->name('delete-data-anggota');

        Route::prefix('manajemen-buku')->group(function () {
            Route::get('/kategori', 'KategoriBukuController@index')->name('kategori');
            Route::get('/get-kategori', 'KategoriBukuController@get')->name('get-kategori');
            Route::get('/show-kategori/{params}', 'KategoriBukuController@show')->name('show-kategori');
            Route::post('/add-kategori', 'KategoriBukuController@store')->name('add-kategori');
            Route::post('/update-kategori/{params}', 'KategoriBukuController@update')->name('update-kategori');
            Route::delete('/delete-kategori/{params}', 'KategoriBukuController@delete')->name('delete-kategori');

            Route::get('/buku', 'BukuController@index')->name('buku');
            Route::get('/add-view-buku', 'BukuController@add')->name('add-view-buku');
            Route::get('/edit-view-buku/{params}', 'BukuController@edit')->name('edit-view-buku');
            Route::get('/get-buku', 'BukuController@get')->name('get-buku');
            Route::get('/show-buku/{params}', 'BukuController@show')->name('show-buku');
            Route::post('/add-buku', 'BukuController@store')->name('add-buku');
            Route::post('/update-buku/{params}', 'BukuController@update')->name('update-buku');
            Route::delete('/delete-buku/{params}', 'BukuController@delete')->name('delete-buku');
        });

        Route::get('/peminjaman', 'PeminjamanController@index')->name('peminjaman');
        Route::get('/get-peminjaman', 'PeminjamanController@get')->name('get-peminjaman');
        Route::get('/show-peminjaman/{params}', 'PeminjamanController@show')->name('show-peminjaman');
        Route::post('/add-peminjaman', 'PeminjamanController@store')->name('add-peminjaman');
        Route::post('/update-peminjaman/{params}', 'PeminjamanController@update')->name('update-peminjaman');
        Route::delete('/delete-peminjaman/{params}', 'PeminjamanController@delete')->name('delete-peminjaman');

        Route::get('/pengembalian', 'Pengembalian@index')->name('pengembalian');

        Route::get('/histori', 'History@index')->name('histori');
        Route::get('/get-histori', 'History@get')->name('get-histori');

        Route::get('/denda', 'Denda@index')->name('denda');
        Route::get('/get-denda', 'Denda@get')->name('get-denda');
    });

    Route::get('/logout', 'Auth@logout')->name('logout');
});
