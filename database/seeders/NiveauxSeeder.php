<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Niveau;

class NiveauxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cycles principaux
        $cycles = [
            ['libelle' => 'Primaire', 'statut' => 'active', 'position' => 1],
            ['libelle' => 'Collège', 'statut' => 'active', 'position' => 2],
            ['libelle' => 'Lycée', 'statut' => 'active', 'position' => 3],
            ['libelle' => 'Université', 'statut' => 'active', 'position' => 4],
        ];

        $cycleIds = [];
        foreach ($cycles as $cycle) {
            $cycleIds[$cycle['libelle']] = Niveau::create([
                'libelle' => $cycle['libelle'],
                'statut' => $cycle['statut'],
                'position' => $cycle['position'],
                'parent_id' => null,
            ]);
        }

        // Niveaux pour Primaire (2 niveaux)
        $primaire = $cycleIds['Primaire'];
        $cp = Niveau::create([
            'libelle' => 'CP',
            'statut' => 'active',
            'position' => 1,
            'parent_id' => $primaire->id,
        ]);
        Niveau::create([
            'libelle' => 'CP1',
            'statut' => 'active',
            'position' => 1,
            'parent_id' => $cp->id,
        ]);
        Niveau::create([
            'libelle' => 'CP2',
            'statut' => 'active',
            'position' => 2,
            'parent_id' => $cp->id,
        ]);

        // Niveaux pour Collège (2 niveaux)
        $college = $cycleIds['Collège'];
        $sixieme = Niveau::create([
            'libelle' => '6e',
            'statut' => 'active',
            'position' => 1,
            'parent_id' => $college->id,
        ]);
        Niveau::create([
            'libelle' => '6eA',
            'statut' => 'active',
            'position' => 1,
            'parent_id' => $sixieme->id,
        ]);
        Niveau::create([
            'libelle' => '6eB',
            'statut' => 'active',
            'position' => 2,
            'parent_id' => $sixieme->id,
        ]);

        // Niveaux pour Lycée (3 niveaux)
        $lycee = $cycleIds['Lycée'];
        $seconde = Niveau::create([
            'libelle' => '2nde',
            'statut' => 'active',
            'position' => 1,
            'parent_id' => $lycee->id,
        ]);
        $secondeS = Niveau::create([
            'libelle' => '2nde S',
            'statut' => 'active',
            'position' => 1,
            'parent_id' => $seconde->id,
        ]);
        Niveau::create([
            'libelle' => '2nde S1',
            'statut' => 'active',
            'position' => 1,
            'parent_id' => $secondeS->id,
        ]);
        Niveau::create([
            'libelle' => '2nde S2',
            'statut' => 'active',
            'position' => 2,
            'parent_id' => $secondeS->id,
        ]);

        // Niveaux pour Université (2 niveaux)
        $universite = $cycleIds['Université'];
        $licence = Niveau::create([
            'libelle' => 'Licence',
            'statut' => 'active',
            'position' => 1,
            'parent_id' => $universite->id,
        ]);
        Niveau::create([
            'libelle' => 'Licence 1',
            'statut' => 'active',
            'position' => 1,
            'parent_id' => $licence->id,
        ]);
        Niveau::create([
            'libelle' => 'Licence 2',
            'statut' => 'active',
            'position' => 2,
            'parent_id' => $licence->id,
        ]);
        Niveau::create([
            'libelle' => 'Licence 3',
            'statut' => 'active',
            'position' => 3,
            'parent_id' => $licence->id,
        ]);
    }
}
