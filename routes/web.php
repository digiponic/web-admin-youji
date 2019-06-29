<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function () {

    Route::group(['prefix' => 'area'], function () {
        Route::get('/provinsi', 'ApiAreaController@getProvinsi');
        Route::get('/kota', 'ApiAreaController@getKota');
        Route::get('/kecamatan', 'ApiAreaController@getKecamatan');
        Route::get('/kelurahan', 'ApiAreaController@getKelurahan');
    });
    
    Route::group(['prefix' => 'pelanggan'], function () {
        Route::get('/alamat', 'ApiPelangganController@getAlamat');
    });

    Route::group(['prefix' => 'produk'], function () {
        Route::get('/', 'ApiProdukController@getData');
    });

});