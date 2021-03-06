<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('user', 'AuthController@getUser');
Route::get('logout', 'AuthController@logout');
Route::put('user/{user}', 'User\UserController@update');
Route::resource('cart', 'User\CartController')
    ->except('index');
Route::post('payment', 'User\PaymentController@payOrder')
    ->middleware(['check.address', 'product.count', 'deleted.products']);

