<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')
    ->group(function () {
        Route::get('register', [RegisterController::class, 'create'])->name('register');
        Route::post('register', [RegisterController::class, 'store']);
        Route::get('login', [LoginController::class, 'create'])->name('login');
        Route::post('login', [LoginController::class, 'store']);
    });

Route::middleware('auth')->group(function () {
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

//             Route::get('/create', 'create')->name('create');
//             Route::post('/store', 'store')->name('store');
//             Route::get('/{id}/edit', 'edit')->name('edit')->where('id', '[0-9]+');
//             Route::put('/{id}/update', 'update')->name('update')->where('id', '[0-9]+');
//             Route::delete('/{id}/delete', 'delete')->name('delete')->where('id', '[0-9]+');

//        Old version...
//        Route::get('cars/create', 'CarController@create')->name('car.create');
//        Route::post('cars/store', 'CarController@store')->name('car.store');
//        Route::get('cars/{id}/edit', 'CarController@edit')->name('car.edit')->where('id', '[0-9]+');
//        Route::put('cars/{id}/update', 'CarController@update')->name('car.update')->where('id', '[0-9]+');
//        Route::delete('cars/{id}/delete', 'CarController@delete')->name('car.delete')->where('id', '[0-9]+');
    });