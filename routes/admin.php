<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AuthController,
    ProductController as AdminProductController,
};

Route::middleware('guest')
    ->controller(AuthController::class)
    ->group(function () {
        Route::group(['prefix' => '/admin', 'as' => 'admin.'], function () {
            Route::view('login', 'login')->name('login.view');
            Route::post('login', 'login')->name('login');
        });
    });

Route::middleware('auth')
    ->controller(AdminProductController::class)
    ->group(function () {
        Route::group(['prefix' => '/admin/products', 'as' => 'admin.product.'], function () {
            Route::get('/', 'index')->name('list');
            Route::view('add', 'admin.product.create')->name('add.view');
            Route::post('add', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('delete/{id}', 'delete')->name('delete');
        });
    });

Route::get('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
