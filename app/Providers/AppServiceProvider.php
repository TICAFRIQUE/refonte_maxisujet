<?php

namespace App\Providers;

use App\Models\Parametre;
use Spatie\Permission\Models\Role;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //



        $this->app->booted(function () {
            $permissions = Permission::pluck('id')->toArray();

            $developpeurRole = Role::where('name', 'developpeur')->first();
            $superadminRole = Role::where('name', 'superadmin')->first();

            if ($developpeurRole) {
                $developpeurRole->permissions()->sync($permissions);
            }

            if ($superadminRole) {
                $superadminRole->permissions()->sync($permissions);
            }
        });



        //get setting data
        $data_parametre = Parametre::with('media')->first();


        view()->share([
            'parametre' => $data_parametre,
        ]);
    }
}
