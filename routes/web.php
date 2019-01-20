<?php
// \URL::forceScheme('https');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/login','Session@login');
Route::post('/login','Session@authenticate');
Route::get('/admin_login','Session@admin_login');
Route::post('/admin_login','Session@admin_authenticate');
Route::get('/logout', 'Session@logout');

Route::get('/', 'Shop@index');
Route::get('/products', 'Shop@products');
Route::get('/product_details', 'Shop@product_details');
Route::get('/cart', 'Shop@cart');
Route::get('/checkout', 'Shop@checkout');

Route::get('/admin', 'User@index');
Route::get('/admin/simple', 'User@simple');
Route::get('/admin/data', 'User@data');
Route::get('/admin/change_password', 'User@edit_password');
Route::post('/admin/change_password', 'User@change_password');
Route::post('/admin/change_photo', 'User@change_photo');
Route::get('/admin/list_users', 'User@list_users');
Route::post('/admin/new_user', 'User@new_user');
Route::post('/admin/edit_user', 'User@edit_user');