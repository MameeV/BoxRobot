<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('storeCategory', 'CategoriesController@storeCategory');
Route::get('getCategories', 'CategoriesController@index');
Route::post('updateCategory/{id}', 'CategoriesController@update');
Route::get('showCategory/{id}', 'CategoriesController@showCategory');
Route::post('destroyCategory/{id}', 'CategoriesController@destroyCategory');

Route::post('storeProduct', 'ProductsController@storeProduct');
Route::get('getProducts', 'ProductsController@index');
Route::post('updateProduct/{id}', 'ProductsController@updateProduct');
Route::get('showProduct/{id}', 'ProductsController@showProduct');
Route::post('destroyProduct/{id}', 'ProductsController@destroyProduct');

Route::post('storeOrder', 'OrdersController@storeOrder');
Route::get('getOrders', 'OrdersController@index');
Route::post('updateOrder/{id}', 'OrdersController@updateOrder');
Route::get('showOrder/{id}', 'OrdersController@showOrder');
Route::post('destroyOrder/{id}', 'OrdersController@destroyOrder');
Route::get('showUserOrders', "OrdersController@showUserOrders");

Route::post('storeComment', 'CommentsController@storeComment');
Route::get('getComments/{id}', 'CommentsController@index');
Route::post('deleteComment/{id}', 'CommentsController@deleteComment');

Route::get('getRole', 'RolesController@index');
Route::post('storeRole', 'RolesController@storeRole');
Route::post('updateRole/{id}', 'RolesController@updateRole');
Route::get('showRole/{id}', 'RolesController@showRole');
Route::post('destroyRole/{id}', 'RolesController@destroRole');

Route::post('signUp', 'UsersController@signUp');
Route::post('signIn', 'UsersController@signIn');
Route::get('getUser', 'UsersController@getUser');
//enter more routes here, leaving below as last route!
Route::any('{path?}', 'MainController@index')->where("path", ".+");
