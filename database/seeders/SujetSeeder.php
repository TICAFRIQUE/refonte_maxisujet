<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sujet;
use App\Models\Categorie;
use App\Models\Matiere;
use App\Models\User;
use App\Models\Niveau;

class SujetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Categorie::pluck('id', 'libelle')->toArray();
        $matieres = Matiere::pluck('id', 'libelle')->toArray();
        $users = User::pluck('id')->toArray();
        $niveaux = Niveau::pluck('id')->toArray();

        for ($i = 1; $i <= 100; $i++) {
            $catLibelle = array_rand($categories);
            $matLibelle = array_rand($matieres);

            $sujet = Sujet::create([
                'code' => 'SUJ' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'libelle' => $catLibelle . ' ' . $i . '-' . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ'), 0, 5),
                'description' => 'Sujet de ' . $catLibelle . ' en ' . $matLibelle . ' - Année ' . (2025 - rand(0, 10)),
                'statut' => 'active',
                'approuve' => (bool)rand(0, 1),
                'annee' => 2025 - rand(0, 10),
                'categorie_id' => $categories[$catLibelle],
                'matiere_id' => $matieres[$matLibelle],
                'user_id' => $users[array_rand($users)],
            ]);

            // Associer 1 à 3 niveaux aléatoires
            $niveauIds = collect($niveaux)->shuffle()->take(rand(1, 3))->toArray();
            $sujet->niveaux()->sync($niveauIds);
        }
    }
}
