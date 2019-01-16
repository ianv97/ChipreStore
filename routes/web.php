<?php
// \URL::forceScheme('https');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'Shop@index');
Route::get('/products', 'Shop@products');
Route::get('/product_details', 'Shop@product_details');
Route::get('/cart', 'Shop@cart');
Route::get('/checkout', 'Shop@checkout');

Route::get('/admin', function () {
    return view('admin/index');
});

Route::get('/admin/simple', function () {
    return view('admin/pages/tables/simple');
})->name('admin.simple');

Route::get('/admin/data', function () {
    return view('admin/pages/tables/data');
})->name('admin.data');

