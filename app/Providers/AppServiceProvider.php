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

        // infos flash actives, affichées en bandeau dans l'en-tête du site public
        if (Schema::hasTable('info_flashes')) {
            view()->share([
                'info_flashes' => \App\Models\InfoFlash::active()->ordered()->get(),
            ]);
        }

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

        // Félicite l'auteur (une seule fois) pour les points gagnés sur ses sujets
        // approuvés depuis sa dernière visite. Déclenché sur le layout public, pas
        // le back-office, pour ne pas surprendre un admin qui approuve son propre sujet.
        \Illuminate\Support\Facades\View::composer('frontend.layouts.front_app', function ($view) {
            if (!\Illuminate\Support\Facades\Auth::check()) {
                return;
            }

            $sujetsAFeliciter = \App\Models\Sujet::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->where('points_attribues', true)
                ->where('felicitations_vues', false)
                ->get();

            if ($sujetsAFeliciter->isEmpty()) {
                return;
            }

            $points = $sujetsAFeliciter->count() * \App\Services\PointsService::POINTS_PUBLICATION_SUJET;

            if ($sujetsAFeliciter->count() === 1) {
                $message = 'Votre sujet « ' . $sujetsAFeliciter->first()->libelle . ' » a été approuvé, vous avez gagné ' . $points . ' points !';
            } else {
                $message = $sujetsAFeliciter->count() . ' de vos sujets ont été approuvés, vous avez gagné ' . $points . ' points au total !';
            }

            \RealRashid\SweetAlert\Facades\Alert::success('Félicitations !', $message);

            \App\Models\Sujet::whereIn('id', $sujetsAFeliciter->pluck('id'))->update(['felicitations_vues' => true]);
        });
    }
}
