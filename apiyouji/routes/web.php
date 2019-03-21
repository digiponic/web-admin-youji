<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    // return $router->app->version();
    return 'REST API Digiponic Mobile Apps for Galaksi Organik v0.1';
});
// harus di daftarkan

// routing product
$router->group(['prefix' => 'product'], function () use ($router) {
    $router->get('/', 'ProductController@all');
    $router->get('/show/{id}', 'ProductController@show');
    $router->post('/store', 'ProductController@store');
    $router->post('/batch', 'ProductController@batch');
    $router->put('/update/{id}', 'ProductController@update');
    $router->delete('/delete/{id}', 'ProductController@delete');
    $router->get('/stockcard/{id}', 'ProductController@stockcard');
   
});

$router->group(['prefix' => 'tipe'], function () use ($router) {
    $router->get('/', 'TipeController@data');
    $router->get('/detil/{keterangan}', 'TipeController@detil');
   
});

$router->group(['prefix' => 'produk'], function () use ($router) {
    $router->get('/', 'ProdukController@data');
    $router->post('/filter', 'ProdukController@filter');
});

$router->group(['prefix' => 'pelanggan'], function () use ($router) {
    $router->post('/', 'PelangganController@simpan');
    $router->post('/perbarui', 'PelangganController@perbarui');
});

$router->group(['prefix' => 'area'], function () use ($router) {
    $router->get('/', 'AreaController@data');
});

$router->group(['prefix' => 'transaksi'], function () use ($router) {
    $router->post('/', 'TransaksiController@simpan');
    $router->post('/data', 'TransaksiController@data');
    $router->post('/data/detil', 'TransaksiController@detil');
});

//routing customeraddress
$router->group(['prefix'=>'customeraddress'], function()use ($router){
    $router->get('/', 'customeraddressController@all');
    $router->get('//show/{id}', 'customeraddressController@show');
    $router->post('/store', 'customeraddressController@store');
    $router->post('/batch', 'customeraddressController@batch');
    $router->put('/update/{id}', 'customeraddressController@update');
    $router->delete('/delete/{id}', 'customeraddressController@delete');
});
//routing customer
$router->group(['prefix'=> 'customer'], function()use ($router){
    $router->get('/', 'CustomerController@all');
    $router->get('/show/{id}', 'CustomerController@show');
    $router->post('/store', 'CustomerController@store');
    $router->post('/batch', 'CustomerController@batch');
    $router->put('/update/{id}', 'CustomerController@update');
    $router->delete('/delete/{id}', 'CustomerController@delete');
});
//routing Generals
$router->group(['prefix'=>'generals'],function()use($router){
    $router->get('/', 'generalsController@all');
    $router->get('/show/{id}', 'generalsController@show');
    $router->post('/store', 'generalsController@store');
    $router->put('/update/{id}', 'generalsController@update');
    $router->delete('/delete/{id}', 'generalsController@delete');
});
//routing Pesan
$router->group(['prefix'=>'pesan'],function()use($router){
    $router->get('/', 'PesanController@all');
    $router->get('/show/{id}', 'PesanController@show');
    $router->post('/store', 'PesanController@store');
    $router->put('/update/{id}', 'PesanController@update');
    $router->delete('/delete/{id}', 'PesanController@delete');
});
//routing ProductImages
$router->group(['prefix'=>'productimages'], function()use($router){
    $router->get('/', 'ProductImagesController@all');
    $router->get('/show/{id}', 'ProductImagesController@show');
    $router->post('/store', 'ProductImagesController@store');
    $router->put('/update/{id}', 'ProductImagesController@update');
    $router->delete('/delete/{id}', 'ProductImagesController@delete');
});
//routing Promotions
$router->group(['prefix'=>'promotion'], function()use($router){
    $router->get('/', 'PromotionController@all');
    $router->get('/promotion/show/{id}', 'PromotionController@show');
    $router->post('/promotion/store', 'PromotionController@store');
    $router->post('/promotion/batch', 'PromotionController@batch');
    $router->put('/promotion/update/{id}', 'PromotionController@update');
    $router->delete('/promotion/delete/{id}', 'PromotionController@delete');
});
//routing PromotionDetail
$router->group(['prefix'=>'promotiondetail'], function()use($router){
    $router->get('/', 'PromotionDetailController@all');
    $router->get('/show/{id}', 'PromotionDetailController@show');
    $router->post('/store', 'PromotionDetailController@store');
    $router->post('/batch', 'PromotionDetailController@batch');
    $router->put('/update/{id}', 'PromotionDetailController@update');
    $router->delete('/delete/{id}', 'PromotionDetailController@delete');
});
//routing SalesOrder
$router->group(['prefix'=>'salesorders'], function()use($router){
    $router->get('/', 'SalesOrdersController@all');
    $router->get('/show/{id}', 'SalesOrdersController@show');
    $router->post('/store', 'SalesOrdersController@store');
    $router->post('/batch', 'SalesOrdersController@batch');
    $router->put('/update/{id}', 'SalesOrdersController@update');
    $router->delete('/delete/{id}', 'SalesOrdersController@delete');
});
//routing SalesOrderDetail
$router->group(['prefix'=>'salesorderdetail'], function()use($router){
    $router->get('/', 'SalesOrderDetailController@all');
    $router->get('/show/{id}', 'SalesOrderDetailController@show');
    $router->post('/store', 'SalesOrderDetailController@store');
    $router->post('/batch', 'SalesOrderDetailController@batch');
    $router->put('/update/{id}', 'SalesOrderDetailController@update');
    $router->delete('/delete/{id}', 'SalesOrderDetailController@delete');
});
//routing stockCards
$router->group(['prefix'=>'stockcards'], function()use($router){
    $router->get('/', 'StockCardsController@all');
    $router->get('/show/{id}', 'StockCardsController@show');
    $router->post('/store', 'StockCardsController@store');
    $router->post('/batch', 'StockCardsController@batch');
    $router->put('/update/{id}', 'StockCardsController@update');
    $router->delete('/delete/{id}', 'StockCardsController@delete');
});
//routing Users
$router->group(['prefix'=>'users'], function()use($router){
    $router->get('/', 'UsersController@all');
    $router->get('/show/{id}', 'UsersController@show');
    $router->post('//store', 'UsersController@store');
    $router->put('/update/{id}', 'UsersController@update');
    $router->delete('/delete/{id}', 'UsersController@delete');
});
//routing cities
$router->group(['prefix'=>'cities'], function()use($router){
    $router->get('/', 'CitiesController@all');
    $router->get('/show/{id}', 'CitiesController@show');
    $router->post('/store', 'CitiesController@store');
    $router->put('/update/{id}', 'CitiesController@update');
    $router->delete('/delete/{id}', 'CitiesController@delete');
});
//routing districs
$router->group(['prefix'=>'districs'], function()use($router){
    $router->get('/', 'DistricsController@all');
    $router->get('/show/{id}', 'DistricsControlle@show');
    $router->post('/store', 'DistricsController@store');
    $router->put('/update/{id}', 'DistricsControlle@update');
    $router->delete('/delete/{id}', 'DistricsControlle@delete');
});
//routing states
$router->group(['prefix'=>'states'], function()use($router){
$router->get('/', 'StatesController@all');
$router->get('/show/{id}', 'StatesController@show');
$router->post('/store', 'StatesController@store');
$router->put('/update/{id}', 'StatesController@update');
$router->delete('/delete/{id}', 'StatesController@delete');
});
//routing transaksi
$router->get('transactions/', 'TransactionsController@all');