<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/','LoginController@index');
Route::post('/login','LoginController@store')->name('pasok');
Route::get('/logout','LoginController@logout')->name('gawas');

Route::get('/dashboard','DashboardController@index')->name('dash');
Route::get('/dashboad/cancel-item/{id}','DashboardController@cancel_item');

Route::post('/dashboad/store-item','DashboardController@store_items');
Route::post('/dashboad/save-transaction/{id}','DashboardController@save_transaction');

Route::get('/change-password/{id}','DashboardController@changepassword');
Route::post('/change-password/{id}','DashboardController@post_changepassword');


Route::resource('/branches','BranchController');
Route::resource('/cashiers','CashierController');
Route::resource('/categories','CategoryController');

Route::get('inventory/view-list','InventoryController@view_inventory');
Route::post('inventory/view-list','InventoryController@post_inventory');

Route::get('/product/view-entry/{id}','ProductController@view_product');
Route::get('/product/delete-entry/{id}','ProductController@delete_product_image');
Route::post('/product/new-price/{id}','ProductController@new_price');
Route::resource('/products','ProductController');

Route::resource('/new-items','NewItemController');
Route::resource('/settings','SettingController');
Route::resource('/suppliers','SupplierController');

Route::get('/sales/daily-sales','SalesController@view_daily_sales');
Route::get('/sales/product-status','SalesController@view_product_status');
Route::get('/sales/per-date','SalesController@view_sales_per_date');
Route::get('/sales/daily-product-sales','SalesController@view_daily_product_sales');
Route::get('/sales/daily-category-product-sales','SalesController@view_daily_category_product_sales');
Route::get('/sales/daily-income','SalesController@view_daily_income');

Route::post('/sales/daily-sales','SalesController@post_daily_sales');
Route::post('/sales/product-status','SalesController@post_product_status');
Route::post('/sales/per-date','SalesController@post_sales_per_date');
Route::post('/sales/daily-product-sales','SalesController@post_daily_product_sales');
Route::post('/sales/daily-category-product-sales','SalesController@post_daily_category_product_sales');
Route::post('/sales/daily-income','SalesController@post_daily_income');

Route::get('/setup/delete/{id}','SetupController@delete');
Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'SetupController@switchLang']);
Route::resource('/setups','SetupController');


