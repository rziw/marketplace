<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::group(['middleware' => 'auth.role:admin,customer,seller'], function(){
    Route::get('user', 'AuthController@getUser');
    Route::get('logout', 'AuthController@logout');
    Route::put('user/{user}', 'User\UserController@update');

});

Route::group(['prefix' => 'admin', 'middleware' => 'auth.role:admin'], function(){
    Route::resource('users', 'Admin\UserController');
    Route::resource('shops', 'Admin\ShopController');
});

Route::group(['prefix' => 'seller', 'middleware' => 'auth.role:seller'], function(){
    Route::resource('shop', 'Seller\ShopController')
        ->middleware('shop.owner');

    Route::resource('shop/{shop}/product', 'Seller\ProductController')
        ->except('index')
        ->middleware('shop.owner');

    Route::get('shop/{shop}/products', 'Seller\ProductController@index')
        ->middleware('shop.owner');
});
