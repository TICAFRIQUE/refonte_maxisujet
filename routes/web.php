<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\RoleController;
use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\SujetController;
use App\Http\Controllers\backend\ModuleController;
use App\Http\Controllers\backend\NiveauController;
use App\Http\Controllers\backend\SliderController;
use App\Http\Controllers\frontend\HomeControlleur;
use App\Http\Controllers\frontend\UserControlleur;
use App\Http\Controllers\backend\MatiereController;
use App\Http\Controllers\backend\CategorieController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\ParametreController;
use App\Http\Controllers\backend\PermissionController;
use App\Http\Controllers\frontend\SujetFrontController;
use App\Http\Controllers\frontend\UserDashboardControlleur;



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
        route::get('show/{id}', 'show')->name('sujet.show')->middleware('can:voir-sujet');
        Route::post('sujet/{id}/approuve/{etat}', [SujetController::class, 'approuve'])->name('sujet.approuve');
        route::get('edit/{id}', 'edit')->name('sujet.edit')->middleware('can:modifier-sujet');
        route::post('update/{id}', 'update')->name('sujet.update')->middleware('can:modifier-sujet');
        route::get('delete/{id}', 'delete')->name('sujet.delete')->middleware('can:supprimer-sujet');
    });

    // sliders
    Route::prefix('slider')->controller(SliderController::class)->group(function () {
        route::get('', 'index')->name('slider.index');
        route::get('create', 'create')->name('slider.create');
        route::post('store', 'store')->name('slider.store');
        route::get('show/{id}', 'show')->name('slider.show');
        route::get('edit/{id}', 'edit')->name('slider.edit');
        route::post('update/{id}', 'update')->name('slider.update');
        route::get('delete/{id}', 'delete')->name('slider.delete');
    });
});

// ROUTES FRONTEND (PUBLIC)
Route::get('/', function () {
    return view('frontend.index');
})->name('accueil');

Route::get('/', HomeControlleur::class)->name('accueil');

// Liste des sujets 
Route::prefix('sujets')->controller(SujetFrontController::class)->group(function () {
    route::get('', 'index')->name('sujet.front.index');
    Route::get('/detail/{libelle}', 'show')->name('sujet.front.show');
    Route::get('/apercu/{id}/{type}', 'apercu')->name('sujet.front.apercu')->middleware('auth');
    Route::get('/download/{id}/{type}', 'download')->name('sujet.front.download')->middleware('auth');
});

// utilisateurs
Route::prefix('user')->controller(UserControlleur::class)->group(function () {
    // login and logout
    route::get('login', 'loginForm')->name('user.loginForm');
    route::post('loginStore', 'login')->name('user.login');
    route::get('register', 'registerForm')->name('user.registerForm');
    route::post('registerStore', 'register')->name('user.register');
    route::post('logout', 'logout')->name('user.logout')->middleware('auth');


    // password reset
    route::get('password/forgot', 'showForgot')->name('password.request');
    route::post('password/email', 'sendResetLink')->name('password.email');
    route::get('password/reset/{token}', 'showReset')->name('password.reset');
    route::post('password/reset', 'resetPassword')->name('password.update');
});

// dashboard user
Route::prefix('user')->controller(UserDashboardControlleur::class)->middleware('auth')->group(function () {
    route::get('dashboard', 'dashboard')->name('user.dashboard');
    route::post('updateProfile', 'updateProfile')->name('user.profile');
    route::get('sujet/index', 'indexSujet')->name('user.sujet.index');
    route::get('sujet/create', 'createSujet')->name('user.sujet.create');
    route::post('sujet/store', 'storeSujet')->name('user.sujet.store');
    route::get('sujet/{id}/edit', 'editSujet')->name('user.sujet.edit');
    route::post('sujet/{id}/update', 'updateSujet')->name('user.sujet.update');
    route::get('sujet/delete/{id}', 'delete')->name('user.sujet.delete');
});

// Pages statiques
Route::view('/cgu', 'frontend.pages.static.cgu')->name('cgu');
Route::view('/confidentialite', 'frontend.pages.static.confidentialite')->name('confidentialite');

// SEO Routes
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
