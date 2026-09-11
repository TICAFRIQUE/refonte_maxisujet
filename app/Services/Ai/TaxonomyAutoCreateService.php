<?php

namespace App\Services\Ai;

use App\Models\Categorie;
use App\Models\Concours;
use App\Models\Matiere;
use App\Models\Niveau;
use Illuminate\Support\Str;

/**
 * Crée réellement les entrées de taxonomie suggérées par l'IA et absentes
 * de la base, au moment (et seulement au moment) où un sujet est
 * effectivement enregistré. Reproduit fidèlement le motif déjà utilisé dans
 * CategorieController/MatiereController/ConcourController/NiveauController
 * plutôt que de les réutiliser directement : leurs sémantiques diffèrent
 * (rejet sur doublon pour un formulaire humain vs "trouver ou créer"
 * idempotent ici), les faire dépendre de ce service risquerait de modifier
 * leur comportement pour un bénéfice nul.
 */
class TaxonomyAutoCreateService
{
    public function firstOrCreateCategorie(string $label): Categorie
    {
        $formatted = $this->format($label);

        return Categorie::whereRaw('LOWER(libelle) = ?', [Str::lower($formatted)])->first()
            ?? Categorie::create(['libelle' => $formatted, 'statut' => 'active']);
    }

    public function firstOrCreateMatiere(string $label): Matiere
    {
        $formatted = $this->format($label);

        return Matiere::whereRaw('LOWER(libelle) = ?', [Str::lower($formatted)])->first()
            ?? Matiere::create(['libelle' => $formatted, 'statut' => 'active']);
    }

    public function firstOrCreateConcours(string $label): Concours
    {
        $formatted = $this->format($label);

        return Concours::whereRaw('LOWER(libelle) = ?', [Str::lower($formatted)])->first()
            ?? Concours::create(['libelle' => $formatted, 'statut' => 'active']);
    }

    /**
     * Crée la portion manquante d'un chemin de niveaux à la suite d'un
     * parent (existant ou null pour un nouveau cycle racine), et retourne
     * l'id du dernier niveau créé (la feuille du chemin).
     *
     * @param  array<int, string>  $labels  libellés à créer, du plus haut au plus précis
     */
    public function createNiveauPath(array $labels, ?string $parentId): string
    {
        foreach ($labels as $label) {
            $formatted = $this->format($label);

            $existing = Niveau::where('parent_id', $parentId)
                ->whereRaw('LOWER(libelle) = ?', [Str::lower($formatted)])
                ->first();

            if ($existing) {
                $parentId = $existing->id;
                continue;
            }

            $niveau = Niveau::create([
                'libelle' => $formatted,
                'parent_id' => $parentId,
                'statut' => 'active',
                'position' => (string) (Niveau::where('parent_id', $parentId)->count() + 1),
                'url' => null,
            ]);

            $parentId = $niveau->id;
        }

        return $parentId;
    }

    private function format(string $label): string
    {
        return Str::ucfirst(Str::lower(trim($label)));
    }
}
