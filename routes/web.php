<?php
// \URL::forceScheme('https');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/', 'Shop@index');
Route::get('/products', 'Shop@products');
Route::get('/product_details/{id}', 'Shop@product_details');
Route::get('/cart', 'Shop@cart');
Route::post('/cart', 'Shop@add_to_cart');
Route::get('/checkout', 'Shop@checkout');
Route::post('/checkout', 'Shop@order');
Route::get('/purchases', 'CustomerController@list_purchases');
Route::post('/signup', 'CustomerController@signup');

Route::get('/login','Session@login');
Route::post('/login','Session@authenticate');
Route::get('/admin/login','Session@admin_login');
Route::post('/admin/login','Session@admin_authenticate');
Route::get('/logout', 'Session@logout');

Route::post('/ajax/find_product', 'Ajax@find_product');
Route::post('/ajax/find_cities', 'Ajax@find_cities');
Route::post('/ajax/find_sale_lines', 'Ajax@find_sale_lines');
Route::post('/ajax/find_purchase_lines', 'Ajax@find_purchase_lines');

Route::get('/admin', 'User@index');
Route::get('/admin/change_password', 'User@edit_password');
Route::post('/admin/change_password', 'User@change_password');
Route::post('/admin/change_photo', 'User@change_photo');
Route::get('/admin/users_list', 'User@list_users');
Route::post('/admin/new_user', 'User@new_user');
Route::post('/admin/edit_user', 'User@edit_user');

Route::get('/admin/list_categories', 'Category@list_categories');
Route::post('/admin/new_category', 'Category@new_category');
Route::post('/admin/edit_category', 'Category@edit_category');

Route::get('/admin/waists_list', 'Waist@list_waists');
Route::post('/admin/new_waist', 'Waist@new_waist');
Route::post('/admin/edit_waist', 'Waist@edit_waist');

Route::get('/admin/products_list', 'Product@list_products');
Route::post('/admin/new_product', 'Product@new_product');
Route::post('/admin/edit_product', 'Product@edit_product');

Route::get('/admin/offers_list','OfferController@list_offers')->name('admin.offers.index');
Route::post('/admin/new_offer','OfferController@new_offer')->name('admin.offers.create');
Route::post('/admin/edit_offer','OfferController@edit_offer');

Route::get('/admin/sales_list','SaleController@list_sales');
Route::post('/admin/edit_sale','SaleController@edit_sale');
