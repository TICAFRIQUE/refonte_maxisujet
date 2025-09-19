<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['libelle' => 'Cours', 'statut' => 'active'],
            ['libelle' => 'Devoirs', 'statut' => 'active'],
            ['libelle' => 'Examens', 'statut' => 'active'],
            ['libelle' => 'Concours', 'statut' => 'active'],
            ['libelle' => 'Annales', 'statut' => 'active'],
            ['libelle' => 'Fiches de révision', 'statut' => 'active'],
            ['libelle' => 'Sujets corrigés', 'statut' => 'active'],
            ['libelle' => 'Supports de cours', 'statut' => 'active'],
            ['libelle' => 'Exercices', 'statut' => 'active'],
            ['libelle' => 'Documents pédagogiques', 'statut' => 'active'],
        ];

        foreach ($categories as $cat) {
            Categorie::updateOrCreate(['libelle' => $cat['libelle']], $cat);
        }
    }
}
