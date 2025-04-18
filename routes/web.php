<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\user\AdminController;
use App\Http\Controllers\backend\module\ModuleController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\backend\permission\RoleController;
use App\Http\Controllers\backend\permission\PermissionController;

// Route::get('/', function () {
//     return view('backend.pages.index');
// });

Route::fallback(function () {
    return view('backend.utility.auth-404-basic');
});

Route::prefix('admin')->group(function () {

    // dashboard admin
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // login and logout
    Route::controller(AdminController::class)->prefix('admin')->group(function () {
        route::get('/login', 'login')->name('admin.login'); // page formulaire de connexion
        route::post('/login', 'login')->name('admin.login'); // envoi du formulaire
        route::post('/logout', 'logout')->name('admin.logout');
    });





    //register admin
    Route::prefix('register')->controller(AdminController::class)->group(function () {
        route::get('', 'index')->name('admin-register.index');
        route::post('store', 'store')->name('admin-register.store');
        route::post('update/{id}', 'update')->name('admin-register.update');
        route::get('delete/{id}', 'delete')->name('admin-register.delete');
        route::get('profil/{id}', 'profil')->name('admin-register.profil');
        route::post('change-password', 'changePassword')->name('admin-register.new-password');
    });

    //role
    Route::prefix('role')->controller(RoleController::class)->group(function () {
        route::get('', 'index')->name('role.index');
        route::post('store', 'store')->name('role.store');
        route::post('update/{id}', 'update')->name('role.update');
        route::get('delete/{id}', 'delete')->name('role.delete');
    });

    //permission
    Route::prefix('permission')->controller(PermissionController::class)->group(function () {
        route::get('', 'index')->name('permission.index');
        route::get('create', 'create')->name('permission.create');
        route::post('store', 'store')->name('permission.store');
        route::get('edit{id}', 'edit')->name('permission.edit');
        route::put('update/{id}', 'update')->name('permission.update');
        route::get('delete/{id}', 'delete')->name('permission.delete');
    });

    //module
    Route::prefix('module')->controller(ModuleController::class)->group(function () {
        route::get('', 'index')->name('module.index');
        route::post('store', 'store')->name('module.store');
        route::post('update/{id}', 'update')->name('module.update');
        route::get('delete/{id}', 'delete')->name('module.delete');
    });
});
