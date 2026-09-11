<?php

namespace App\Services\Ai;

use App\Exceptions\AiAnalysisException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

/**
 * Envoie un sujet (PDF) à l'API Gemini pour en déduire la catégorie, la
 * matière, le niveau (chemin complet dans la hiérarchie) et, si pertinent,
 * le concours concerné. Ne renvoie que des libellés texte proposés par
 * l'IA — jamais des ids : le rapprochement avec les entrées existantes en
 * base (ou la détection "nouveau") est fait séparément par TaxonomyMatcher,
 * pour ne jamais faire confiance à un id éventuellement halluciné par l'IA.
 */
class GeminiSujetAnalyzerService
{
    private const ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent';

    /**
     * @param array{categories: array<int,array{libelle:string}>, matieres: array<int,array{libelle:string}>, concours: array<int,array{libelle:string}>, niveaux: array} $context
     * @return array{categorie: ?string, matiere: ?string, niveau_path: ?array<int,string>, concours: ?string}
     */
    public function analyze(UploadedFile $file, array $context): array
    {
        $apiKey = config('services.gemini.key');

        if (!$apiKey) {
            throw new AiAnalysisException('Clé API Gemini non configurée.');
        }

        $model = config('services.gemini.model');
        $url = sprintf(self::ENDPOINT, $model) . '?key=' . urlencode($apiKey);

        try {
            // Le temps de réponse de ce modèle est très variable (quelques secondes à
            // près d'une minute selon la complexité perçue du document) — délai généreux
            // pour ne pas échouer inutilement ; le bouton affiche un état de chargement
            // pendant toute la durée de l'appel côté interface.
            $response = Http::timeout(90)->connectTimeout(5)->post($url, [
                'contents' => [[
                    'parts' => [
                        [
                            'inline_data' => [
                                'mime_type' => 'application/pdf',
                                'data' => base64_encode(file_get_contents($file->getRealPath())),
                            ],
                        ],
                        ['text' => $this->buildPrompt($context)],
                    ],
                ]],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                    'temperature' => 0.1,
                ],
            ]);
        } catch (\Throwable $e) {
            throw new AiAnalysisException('Impossible de joindre l\'API Gemini : ' . $e->getMessage(), previous: $e);
        }

        if ($response->failed()) {
            throw new AiAnalysisException('Erreur API Gemini (HTTP ' . $response->status() . ') : ' . $response->body());
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

        if (!$text) {
            throw new AiAnalysisException('Réponse Gemini vide ou de forme inattendue.');
        }

        $decoded = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            throw new AiAnalysisException('Réponse Gemini non conforme au JSON attendu : ' . json_last_error_msg());
        }

        return [
            'categorie' => $decoded['categorie'] ?? null,
            'matiere' => $decoded['matiere'] ?? null,
            'niveau_path' => $decoded['niveau_path'] ?? null,
            'concours' => $decoded['concours'] ?? null,
        ];
    }

    private function buildPrompt(array $context): string
    {
        $categories = $this->formatFlatList($context['categories'] ?? []);
        $matieres = $this->formatFlatList($context['matieres'] ?? []);
        $niveauTree = $this->formatNiveauTree($context['niveaux'] ?? []);

        $concoursSection = '';
        if (!empty($context['concours'])) {
            $concoursListe = $this->formatFlatList($context['concours']);
            $concoursSection = "\n\nConcours déjà existants dans la base (réutilise EXACTEMENT un de ces libellés si le document correspond) :\n{$concoursListe}\n\nSi ce document est lié à un concours (examen d'entrée, concours administratif ou scolaire...), indique son nom dans \"concours\". Sinon mets null.";
        }

        return <<<PROMPT
Tu es un assistant qui aide à classer des sujets d'examens scolaires/universitaires pour une plateforme éducative ivoirienne (MaxiSujets).

Analyse le document PDF fourni et détermine :
1. La catégorie du document
2. La matière scolaire concernée
3. Le niveau scolaire précis (la classe)
4. Si applicable, le concours concerné

Catégories déjà existantes dans la base (réutilise EXACTEMENT un de ces libellés si le document correspond, sinon propose un nouveau libellé court et clair) :
{$categories}

Matières déjà existantes dans la base (même consigne) :
{$matieres}

Arbre des niveaux déjà existants dans la base (hiérarchie cycle > niveau > sous-niveau...). Indique le CHEMIN COMPLET du niveau détecté, du cycle racine jusqu'au niveau le plus précis identifiable dans le document, sous forme de liste ordonnée. Réutilise EXACTEMENT les libellés existants quand ils correspondent ; si seul un niveau parent existe déjà, réutilise ce parent et ajoute uniquement les niveaux manquants à la suite du chemin :
{$niveauTree}

IMPORTANT : recopie les libellés ci-dessus caractère pour caractère quand ils correspondent, même sous une forme abrégée ou inhabituelle (ex. si la liste contient "2nde", réponds "2nde" et non "Seconde" ; si elle contient "6e", réponds "6e" et non "Sixième" ; si elle contient "Tle", réponds "Tle" et non "Terminale"). Ne reformule et ne développe jamais un libellé déjà présent dans la liste — une simple différence de formulation créerait un doublon inutile dans la base. Cette consigne s'applique aussi aux catégories et aux matières.
{$concoursSection}

Réponds STRICTEMENT en JSON, sans aucun texte autour, au format exact suivant :
{
  "categorie": "libellé de la catégorie" ou null,
  "matiere": "libellé de la matière" ou null,
  "niveau_path": ["Cycle", "Niveau", "Sous-niveau"] ou null,
  "concours": "libellé du concours" ou null
}

Si tu ne peux pas déterminer un champ avec confiance à partir du contenu réel du document, mets sa valeur à null plutôt que de deviner au hasard.
PROMPT;
    }

    private function formatFlatList(array $items): string
    {
        if (empty($items)) {
            return '(aucune pour le moment)';
        }

        return collect($items)->map(fn ($item) => '- ' . $item['libelle'])->implode("\n");
    }

    private function formatNiveauTree(array $niveaux, int $depth = 0): string
    {
        if (empty($niveaux)) {
            return '(aucun pour le moment)';
        }

        $lines = [];
        foreach ($niveaux as $niveau) {
            $lines[] = str_repeat('  ', $depth) . '- ' . $niveau['libelle'];
            if (!empty($niveau['children'])) {
                $lines[] = $this->formatNiveauTree($niveau['children'], $depth + 1);
            }
        }

        return implode("\n", $lines);
    }
}
