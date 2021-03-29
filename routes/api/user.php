<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('user', 'AuthController@getUser');
Route::get('logout', 'AuthController@logout');
Route::put('user/{user}', 'User\UserController@update');
Route::resource('cart', 'User\CartController')
    ->except('index');

Route::group(['prefix' => 'payment', 'namespace' => 'User'], function () {

    Route::post('/payment', 'PaymentController@pay')
        ->middleware(['check.address', 'product.count', 'deleted.products']);
    Route::post('/success', 'PaymentController@trackSuccessfulPay');
    Route::post('/failed', 'PaymentController@trackFailedPay');

});




