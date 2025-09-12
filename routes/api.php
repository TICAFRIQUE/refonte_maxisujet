<?php

use App\Http\Controllers\backend\CategorieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// route api pour mes categories

    Route::controller(CategorieController::class)->group(function () {
       route::get('/categories', 'index')->name('categories.index');
       route::post('/categories/store', 'store')->name('categories.store');
       route::get('/categories/{id}', 'show')->name('categories.show');
       route::post('/categories/update/{id}', 'update')->name('categories.update');
       route::delete('/categories/delete/{id}', 'destroy')->name('categories.destroy');
    });
