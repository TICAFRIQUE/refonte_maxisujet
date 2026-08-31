<?php

namespace App\Providers;

use App\Models\Niveau;
use App\Models\Categorie;
use App\Models\Parametre;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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

        //pagination par defaut a 10
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        Schema::defaultStringLength(191);


        // Les nouvelles permissions sont désormais attribuées aux rôles à pleins pouvoirs
        // (superadmin, administrateur, developpeur) au moment de leur création, dans
        // ModuleController::store() — plus besoin de resynchroniser TOUTES les permissions
        // sur CHAQUE requête (coûteux et inutile une fois la création à jour).

        //recuperer les parametres
        if (Schema::hasTable('parametres')) {
            $data_parametre = Parametre::with('media')->first();
            view()->share([
                'parametre' => $data_parametre ?? null,
            ]);
        }

        //partager les niveaux avec toutes les vues
        if (Schema::hasTable('niveaux')) {
            $data_niveaux = Niveau::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')->OrderBy('position', 'ASC')->active()->get();
            view()->share([
                'data_niveaux' => $data_niveaux ?? null,
            ]);
        }

        // partager les categories avec toutes les vues
        if (Schema::hasTable('categories')) {
            $data_categories = Categorie::active()->get();
            view()->share([
                'data_categories' => $data_categories ?? null,
            ]);
        }

        //partager les matieres avec toutes les vues
        if (Schema::hasTable('matieres')) {
            $data_matieres = \App\Models\Matiere::active()->get();
            view()->share([
                'data_matieres' => $data_matieres ?? null,
            ]);
        }

        // Chiffres réels du footer (jamais de chiffres inventés dans les vues)
        if (Schema::hasTable('sujets') && Schema::hasTable('users')) {
            view()->share([
                'footer_stats' => [
                    'sujets' => \App\Models\Sujet::active()->approuve()->count(),
                    'membres' => \App\Models\User::where('statut', 'active')->count(),
                ],
            ]);
        }
    }
}
