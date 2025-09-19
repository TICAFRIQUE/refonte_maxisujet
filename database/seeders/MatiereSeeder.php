<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matiere;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matieres = [
            ['libelle' => 'Mathématiques', 'statut' => 'active'],
            ['libelle' => 'Français', 'statut' => 'active'],
            ['libelle' => 'Physique-Chimie', 'statut' => 'active'],
            ['libelle' => 'SVT', 'statut' => 'active'],
            ['libelle' => 'Histoire-Géographie', 'statut' => 'active'],
            ['libelle' => 'Anglais', 'statut' => 'active'],
            ['libelle' => 'Espagnol', 'statut' => 'active'],
            ['libelle' => 'Philosophie', 'statut' => 'active'],
            ['libelle' => 'Informatique', 'statut' => 'active'],
            ['libelle' => 'Économie', 'statut' => 'active'],
            ['libelle' => 'Gestion', 'statut' => 'active'],
            ['libelle' => 'Sciences de l’ingénieur', 'statut' => 'active'],
            ['libelle' => 'Arts plastiques', 'statut' => 'active'],
            ['libelle' => 'Musique', 'statut' => 'active'],
            ['libelle' => 'Technologie', 'statut' => 'active'],
            ['libelle' => 'Droit', 'statut' => 'active'],
            ['libelle' => 'Comptabilité', 'statut' => 'active'],
            ['libelle' => 'Marketing', 'statut' => 'active'],
            ['libelle' => 'Biologie', 'statut' => 'active'],
            ['libelle' => 'Géologie', 'statut' => 'active'],
        ];

        foreach ($matieres as $matiere) {
            Matiere::updateOrCreate(['libelle' => $matiere['libelle']], $matiere);
        }
    }
}
