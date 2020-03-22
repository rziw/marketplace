<?php
    Route::resource('user', 'UserController')
        ->except('index');

    Route::get('users', 'UserController@index');

    Route::resource('shop', 'ShopController')
        ->except('index');

    Route::get('shops', 'ShopController@index');

    Route::resource('shop/{shop}/product', 'Product\ProductController')
        ->except('index');

    Route::get('shop/{shop}/products', 'Product\ProductController@index');

    Route::get('products/name/{name}/search', 'Product\ProductSearchController');

    Route::post('shop/{shop}/product/{product}/duplicate', 'Product\ProductDuplicateController');
