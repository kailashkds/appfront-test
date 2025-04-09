<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController,
};

include_once 'admin.php';

Route::controller(ProductController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');

        Route::group(['prefix' => '/products', 'as' => 'products.'], function () {
            Route::get('{product_id}', 'show')->name('show');
        });
    });
