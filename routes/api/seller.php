<?php

Route::resource('shop', 'ShopController')
    ->middleware('shop.owner');

Route::resource('shop/{shop}/product', 'ProductController')
    ->except('index')
    ->middleware('shop.owner');

Route::get('shop/{shop}/products', 'ProductController@index')
    ->middleware('shop.owner');

Route::get('shop/{shop}/orders', 'OrderController@index')
    ->middleware('shop.owner');

Route::put('shop/{shop}/order/{order}', 'OrderController@updateStatus')
->middleware('shop.owner');
