<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::get('/', 'HomeController@index');
Route::get('product/{product}', 'ShowProductController@show');
