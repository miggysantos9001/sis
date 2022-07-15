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

Route::get('/new-item/delete-item/{id}','NewItemController@delete_item')->name('new-item.delete-item');
Route::get('/new-item/receive-entry/{id}','NewItemController@receive_entry')->name('new-item.receive');
Route::get('/new-item/print-entry/{id}','NewItemController@print_entry')->name('new-item.print');

Route::post('/new-item/update-item/{id}','NewItemController@update_item');
Route::post('/new-item/receive-entry/{id}','NewItemController@post_receive_entry');

Route::resource('/new-items','NewItemController');
Route::get('/new-items/delete/{id}','NewItemController@delete')->name('new-items.delete');

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

Route::resource('/customers','CustomerController');

Route::get('/loadproducts','DistributionController@loadproducts');
Route::get('/distribution/remove-to-cart/{id}','DistributionController@removetoCart')->name('distribution.remove-to-cart');
Route::get('/distribution/delete-entry/{id}','DistributionController@delete_entry')->name('distribution.delete-item');
Route::get('/distribution/delete/{id}','DistributionController@delete')->name('distribution.delete');
Route::get('/distribution/set-paid/{id}','DistributionController@set_paid')->name('distribution.set-paid');
Route::get('/distribution/print/{id}','DistributionController@print')->name('distribution.print');
Route::get('/distribution/create-customer-order','DistributionController@create_customer_order')->name('distribution.create-order');

Route::post('/distribution/add-to-cart/','DistributionController@addtoCart')->name('distribution.cart');
Route::post('/distribution/create-customer-order','DistributionController@store_customer_order');
Route::post('/distribution/update-entry/{id}','DistributionController@update_entry');
Route::post('/distribution/set-paid/{id}','DistributionController@post_set_paid');
Route::post('/distribution/add-to-discount/{id}','DistributionController@addtoDiscount');

Route::resource('/distributions','DistributionController');

Route::resource('/unit-measures','UnitMeasureController');


