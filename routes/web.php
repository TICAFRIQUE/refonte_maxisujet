<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\RoleController;
use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\SujetController;
use App\Http\Controllers\backend\ModuleController;
use App\Http\Controllers\backend\NiveauController;
use App\Http\Controllers\backend\MatiereController;
use App\Http\Controllers\backend\CategorieController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\ParametreController;
use App\Http\Controllers\backend\PermissionController;



Route::fallback(function () {
    return view('backend.utility.auth-404-basic');
});

Route::middleware(['admin'])->prefix('admin')->group(function () {

    // login and logout
    Route::controller(AdminController::class)->group(function () {
        route::get('/login', 'login')->name('admin.login')->withoutMiddleware('admin'); // page formulaire de connexion
        route::post('/login', 'login')->name('admin.login')->withoutMiddleware('admin'); // envoi du formulaire
        route::post('/logout', 'logout')->name('admin.logout');
    });



    // dashboard admin
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // parametre application
    Route::prefix('parametre')->controller(ParametreController::class)->group(function () {
        route::get('', 'index')->name('parametre.index');
        route::post('store', 'store')->name('parametre.store');
        route::get('maintenance-up', 'maintenanceUp')->name('parametre.maintenance-up');
        route::get('maintenance-down', 'maintenanceDown')->name('parametre.maintenance-down');
        route::get('optimize-clear', 'optimizeClear')->name('parametre.optimize-clear');
        Route::get('download-backup/{file}', 'downloadBackup')->name('setting.download-backup');  // download backup db
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


    // categories
    Route::prefix('categorie')->controller(CategorieController::class)->group(function () {
        route::get('', 'index')->name('categorie.index');
        route::post('store', 'store')->name('categorie.store')->middleware('can:creer-categorie');
        route::post('update/{id}', 'update')->name('categorie.update')->middleware('can:modifier-categorie');
        route::get('delete/{id}', 'delete')->name('categorie.delete')->middleware('can:supprimer-categorie');
    });

    // matieres
    Route::prefix('matiere')->controller(MatiereController::class)->group(function () {
        route::get('', 'index')->name('matiere.index');
        route::post('store', 'store')->name('matiere.store')->middleware('can:creer-matiere');
        route::post('update/{id}', 'update')->name('matiere.update')->middleware('can:modifier-matiere');
        route::get('delete/{id}', 'delete')->name('matiere.delete')->middleware('can:supprimer-matiere');
    });

    //niveau & cycle
    Route::prefix('niveau')->controller(NiveauController::class)->group(function () {
        route::get('create', 'create')->name('niveau.create')->middleware('can:creer-niveau');
        route::post('store', 'store')->name('niveau.store')->middleware('can:creer-niveau');
        route::get('add-subCat/{id}', 'addSubCat')->name('niveau.add-subCat')->middleware('can:creer-niveau'); // add subCategorie
        route::post('add-subCat-store', 'addSubCatStore')->name('niveau.add-subCat-store')->middleware('can:creer-niveau'); // add subCategorie
        route::get('edit/{id}', 'edit')->name('niveau.edit')->middleware('can:modifier-niveau');
        route::post('update/{id}', 'update')->name('niveau.update')->middleware('can:modifier-niveau');
        route::get('delete/{id}', 'delete')->name('niveau.delete')->middleware('can:supprimer-niveau');
    });

    // sujets
    Route::prefix('sujet')->controller(SujetController::class)->group(function () {
        route::get('', 'index')->name('sujet.index');
        route::get('create', 'create')->name('sujet.create')->middleware('can:creer-sujet');
        route::post('store', 'store')->name('sujet.store')->middleware('can:creer-sujet');
        route::get('edit/{id}', 'edit')->name('sujet.edit')->middleware('can:modifier-sujet');
        route::post('update/{id}', 'update')->name('sujet.update')->middleware('can:modifier-sujet');
        route::get('delete/{id}', 'delete')->name('sujet.delete')->middleware('can:supprimer-sujet');
    });

});
