<?php

namespace App\Services\Ai;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Rapproche un libellé (ou un chemin de niveaux) proposé par l'IA des
 * entrées déjà existantes en base, pour éviter de créer des quasi-doublons
 * quand l'IA reformule légèrement un libellé existant (ex. "Maths" vs
 * "Mathématiques"). Si rien ne correspond suffisamment, l'élément est
 * marqué "new" — sa création réelle est faite ailleurs (TaxonomyAutoCreateService),
 * uniquement si le formulaire est effectivement soumis.
 */
class TaxonomyMatcher
{
    /**
     * Résout un libellé IA contre une liste plate d'entrées existantes
     * (catégorie, matière ou concours).
     *
     * @param  Collection<int, object{id: string, libelle: string}>  $existing
     * @return array{status: 'existing'|'new', id?: string, label: string}
     */
    public function resolveFlat(?string $aiLabel, Collection $existing): ?array
    {
        $aiLabel = trim((string) $aiLabel);

        if ($aiLabel === '') {
            return null;
        }

        $match = $this->findBestMatch($aiLabel, $existing, fn ($item) => $item->libelle);

        if ($match) {
            return ['status' => 'existing', 'id' => $match->id, 'label' => $match->libelle];
        }

        return ['status' => 'new', 'label' => $aiLabel];
    }

    /**
     * Résout un chemin de niveaux (ex. ["Lycée", "2nde", "2nde S1"]) en
     * parcourant l'arbre réel niveau par niveau à partir des cycles racine.
     * Dès qu'un niveau du chemin ne correspond à aucun enfant existant du
     * nœud courant, lui et tous les niveaux suivants du chemin sont "new".
     *
     * @param  Collection<int, object{id: string, libelle: string, parent_id: ?string}>  $allNiveaux  toute la table à plat
     * @return array<int, array{status: 'existing'|'new', id?: string, label: string}>
     */
    public function resolveNiveauPath(?array $pathLabels, Collection $allNiveaux): array
    {
        if (empty($pathLabels)) {
            return [];
        }

        $resolved = [];
        $parentId = null;

        foreach ($pathLabels as $label) {
            $label = trim((string) $label);
            if ($label === '') {
                continue;
            }

            $siblings = $allNiveaux->filter(fn ($n) => $n->parent_id === $parentId);
            $match = $this->findBestMatch($label, $siblings, fn ($item) => $item->libelle);

            if ($match) {
                $resolved[] = ['status' => 'existing', 'id' => $match->id, 'label' => $match->libelle];
                $parentId = $match->id;
            } else {
                // à partir d'ici, plus aucun niveau du chemin ne peut déjà exister
                // (un enfant ne peut pas exister sous un parent qui n'existe pas encore)
                $resolved[] = ['status' => 'new', 'label' => $label];
                $parentId = null; // valeur inutilisée ensuite, tout le reste du chemin est "new"
            }
        }

        return $resolved;
    }

    /**
     * @param  Collection<int, object>  $candidates
     * @param  callable(object): string  $labelOf
     */
    private function findBestMatch(string $aiLabel, Collection $candidates, callable $labelOf): ?object
    {
        if ($candidates->isEmpty()) {
            return null;
        }

        $normalizedTarget = $this->normalize($aiLabel);
        $threshold = (int) config('services.gemini.match_threshold', 80);

        $best = null;
        $bestScore = 0;

        foreach ($candidates as $candidate) {
            $normalizedCandidate = $this->normalize($labelOf($candidate));

            if ($normalizedCandidate === $normalizedTarget) {
                return $candidate;
            }

            similar_text($normalizedTarget, $normalizedCandidate, $percent);

            if ($percent > $bestScore) {
                $bestScore = $percent;
                $best = $candidate;
            }
        }

        return $bestScore >= $threshold ? $best : null;
    }

    private function normalize(string $value): string
    {
        return Str::of($value)->ascii()->lower()->squish()->toString();
    }
}
