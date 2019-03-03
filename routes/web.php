<?php
// \URL::forceScheme('https');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/', 'ShopController@index');
Route::get('/products/{category_filter?}', 'ShopController@products');
Route::get('/product_details/{id}', 'ShopController@product_details');
Route::get('/cart', 'ShopController@cart');
Route::post('/cart', 'ShopController@add_to_cart');
Route::post('/checkout', 'ShopController@order');
Route::get('/purchases', 'CustomerController@list_purchases');
Route::post('/signup', 'CustomerController@signup');
Route::post('/empty_cart', 'ShopController@empty_cart');
Route::get('/success', 'ShopController@approve_payment');

Route::get('/login','SessionController@login');
Route::post('/login','SessionController@authenticate');
Route::get('/admin/login','SessionController@admin_login');
Route::post('/admin/login','SessionController@admin_authenticate');
Route::get('/logout', 'SessionController@logout');

Route::post('/ajax/find_product', 'AjaxController@find_product');
Route::post('/ajax/find_cities', 'AjaxController@find_cities');
Route::post('/ajax/find_sale_lines', 'AjaxController@find_sale_lines');
Route::post('/ajax/find_purchase_lines', 'AjaxController@find_purchase_lines');

Route::get('/admin', 'UserController@index');
Route::get('/admin/change_password', 'UserController@edit_password');
Route::post('/admin/change_password', 'UserController@change_password');
Route::post('/admin/change_photo', 'UserController@change_photo');
Route::get('/admin/users_list', 'UserController@list_users');
Route::post('/admin/new_user', 'UserController@new_user');
Route::post('/admin/edit_user', 'UserController@edit_user');

Route::get('/admin/list_categories', 'CategoryController@list_categories');
Route::post('/admin/new_category', 'CategoryController@new_category');
Route::post('/admin/edit_category', 'CategoryController@edit_category');

Route::get('/admin/waists_list', 'WaistController@list_waists');
Route::post('/admin/new_waist', 'WaistController@new_waist');
Route::post('/admin/edit_waist', 'WaistController@edit_waist');

Route::get('/admin/products_list', 'ProductController@list_products');
Route::post('/admin/new_product', 'ProductController@new_product');
Route::post('/admin/edit_product', 'ProductController@edit_product');

Route::get('/admin/offers_list','OfferController@list_offers');
Route::post('/admin/new_offer','OfferController@new_offer');
Route::post('/admin/edit_offer','OfferController@edit_offer');

Route::get('/admin/sales_list','SaleController@list_sales');
Route::post('/admin/edit_sale','SaleController@edit_sale');



//Route::get('/create_test_user', 'Shop@create_test_user');