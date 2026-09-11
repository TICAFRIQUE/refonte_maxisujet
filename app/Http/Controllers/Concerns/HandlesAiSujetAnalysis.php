<?php

namespace App\Http\Controllers\Concerns;

use App\Exceptions\AiAnalysisException;
use App\Models\Categorie;
use App\Models\Concours;
use App\Models\Matiere;
use App\Models\Niveau;
use App\Services\Ai\GeminiSujetAnalyzerService;
use App\Services\Ai\TaxonomyAutoCreateService;
use App\Services\Ai\TaxonomyMatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Glue partagée entre le flux admin (SujetController) et le flux auteur
 * (UserDashboardControlleur) pour l'analyse IA d'un sujet à l'upload :
 * construction du contexte taxonomie envoyé à Gemini, endpoint d'analyse
 * (qui n'enregistre rien), et résolution des sentinels "new:N" au moment
 * de l'enregistrement réel du sujet.
 */
trait HandlesAiSujetAnalysis
{
    /**
     * @return array{categories: Collection, matieres: Collection, concours: Collection, niveaux_flat: Collection, niveaux_tree: array}
     */
    protected function buildTaxonomyContext(bool $includeConcours): array
    {
        $categories = Categorie::active()->get(['id', 'libelle']);
        $matieres = Matiere::active()->get(['id', 'libelle']);
        $concours = $includeConcours ? Concours::active()->get(['id', 'libelle']) : collect();
        $niveauxFlat = Niveau::active()->get(['id', 'libelle', 'parent_id']);

        return [
            'categories' => $categories,
            'matieres' => $matieres,
            'concours' => $concours,
            'niveaux_flat' => $niveauxFlat,
            'niveaux_tree' => $this->buildNiveauTree($niveauxFlat, null),
        ];
    }

    private function buildNiveauTree(Collection $allNiveaux, ?string $parentId): array
    {
        return $allNiveaux->filter(fn ($n) => $n->parent_id === $parentId)
            ->map(fn ($n) => [
                'libelle' => $n->libelle,
                'children' => $this->buildNiveauTree($allNiveaux, $n->id),
            ])
            ->values()
            ->all();
    }

    /**
     * Endpoint AJAX : analyse le fichier envoyé et renvoie des suggestions.
     * N'écrit jamais rien en base — la création éventuelle des éléments
     * manquants n'a lieu qu'au moment d'un enregistrement réel du sujet
     * (voir resolveAiTaxonomySentinels()).
     */
    public function runAiAnalysis(Request $request, bool $includeConcours): JsonResponse
    {
        if (!config('services.gemini.key')) {
            return response()->json(['status' => 'disabled']);
        }

        $request->validate([
            'fichier' => 'required|file|mimes:pdf|max:10240',
        ]);

        $context = $this->buildTaxonomyContext($includeConcours);

        try {
            $aiResult = app(GeminiSujetAnalyzerService::class)->analyze(
                $request->file('fichier'),
                [
                    'categories' => $context['categories']->toArray(),
                    'matieres' => $context['matieres']->toArray(),
                    'concours' => $context['concours']->toArray(),
                    'niveaux' => $context['niveaux_tree'],
                ]
            );
        } catch (AiAnalysisException $e) {
            report($e);

            return response()->json([
                'status' => 'error',
                'message' => "L'analyse IA a échoué, merci de sélectionner manuellement.",
            ], 503);
        }

        $matcher = app(TaxonomyMatcher::class);
        $sentinels = [];
        $nextKey = 1;

        $categorie = $this->attachSentinel(
            $matcher->resolveFlat($aiResult['categorie'], $context['categories']),
            'categorie',
            $sentinels,
            $nextKey
        );

        $matiere = $this->attachSentinel(
            $matcher->resolveFlat($aiResult['matiere'], $context['matieres']),
            'matiere',
            $sentinels,
            $nextKey
        );

        $concours = null;
        if ($includeConcours) {
            $concours = $this->attachSentinel(
                $matcher->resolveFlat($aiResult['concours'], $context['concours']),
                'concours',
                $sentinels,
                $nextKey
            );
        }

        $niveauPath = $matcher->resolveNiveauPath($aiResult['niveau_path'] ?? null, $context['niveaux_flat']);
        $niveau = $this->attachNiveauSentinel($niveauPath, $sentinels, $nextKey);

        return response()->json([
            'status' => 'ok',
            'categorie' => $categorie,
            'matiere' => $matiere,
            'concours' => $concours,
            'niveau' => $niveau,
            'sentinels' => $sentinels,
        ]);
    }

    private function attachSentinel(?array $resolved, string $type, array &$sentinels, int &$nextKey): ?array
    {
        if (!$resolved) {
            return null;
        }

        if ($resolved['status'] === 'new') {
            $key = 'new:' . $nextKey++;
            $sentinels[$key] = ['type' => $type, 'label' => $resolved['label']];
            $resolved['key'] = $key;
        }

        return $resolved;
    }

    /**
     * @param  array<int, array{status: 'existing'|'new', id?: string, label: string}>  $path
     */
    private function attachNiveauSentinel(array $path, array &$sentinels, int &$nextKey): array
    {
        if (empty($path)) {
            return [];
        }

        $firstNewIndex = null;
        foreach ($path as $i => $level) {
            if ($level['status'] === 'new') {
                $firstNewIndex = $i;
                break;
            }
        }

        if ($firstNewIndex === null) {
            return $path; // chemin entièrement existant, rien à créer
        }

        $parentId = $firstNewIndex > 0 ? ($path[$firstNewIndex - 1]['id'] ?? null) : null;
        $labels = array_map(fn ($level) => $level['label'], array_slice($path, $firstNewIndex));

        $key = 'new:' . $nextKey++;
        $sentinels[$key] = ['type' => 'niveau', 'parent_id' => $parentId, 'labels' => $labels];

        // La clé est portée par la feuille (dernier élément) : c'est la seule
        // option que le multi-select existant doit afficher/sélectionner.
        $path[array_key_last($path)]['key'] = $key;

        return $path;
    }

    /**
     * Étape de résolution appelée juste après une validation de formulaire
     * réussie (store/update) : transforme les éventuels sentinels "new:N"
     * présents dans categorie_id/matiere_id/concours_id/niveaux[] en ids
     * réels, en créant les entrées de taxonomie manquantes au passage.
     *
     * @return array{categorie_id: ?string, matiere_id: ?string, concours_id: ?string, niveaux: array<int, string>}
     */
    protected function resolveAiTaxonomySentinels(Request $request): array
    {
        $sentinels = json_decode((string) $request->input('ai_new_taxonomy', '{}'), true) ?: [];
        $autoCreate = app(TaxonomyAutoCreateService::class);
        $resolvedIds = [];

        $resolveOne = function (?string $value) use ($sentinels, $autoCreate, &$resolvedIds) {
            if ($value === null || $value === '') {
                return null;
            }

            if (!preg_match('/^new:\d+$/', $value)) {
                return $value; // déjà un id réel choisi manuellement ou pré-sélectionné
            }

            if (isset($resolvedIds[$value])) {
                return $resolvedIds[$value];
            }

            $payload = $sentinels[$value] ?? null;

            if (!$payload) {
                throw new \RuntimeException("Élément suggéré par l'IA introuvable ({$value}) — merci de resélectionner manuellement.");
            }

            $id = match ($payload['type']) {
                'categorie' => $autoCreate->firstOrCreateCategorie($payload['label'])->id,
                'matiere' => $autoCreate->firstOrCreateMatiere($payload['label'])->id,
                'concours' => $autoCreate->firstOrCreateConcours($payload['label'])->id,
                'niveau' => $autoCreate->createNiveauPath($payload['labels'], $payload['parent_id'] ?? null),
                default => throw new \RuntimeException('Type de suggestion IA inconnu.'),
            };

            return $resolvedIds[$value] = $id;
        };

        $niveaux = collect($request->input('niveaux', []))
            ->map(fn ($v) => $resolveOne($v))
            ->filter()
            ->values()
            ->all();

        return [
            'categorie_id' => $resolveOne($request->input('categorie_id')),
            'matiere_id' => $resolveOne($request->input('matiere_id')),
            'concours_id' => $resolveOne($request->input('concours_id')),
            'niveaux' => $niveaux,
        ];
    }
}
