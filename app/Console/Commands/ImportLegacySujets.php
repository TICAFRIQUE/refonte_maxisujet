<?php

namespace App\Console\Commands;

use App\Console\Commands\Support\LegacyDataMap;
use App\Models\Categorie;
use App\Models\Etablissement;
use App\Models\Matiere;
use App\Models\Niveau;
use App\Models\Sujet;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Analyse (et, plus tard, importe) les sujets de l'ancien site à partir de
 * recovery/toussujets.csv + recovery/sujets_maxisujets/. Pour l'instant,
 * seule l'analyse (--dry-run, comportement par défaut) est implémentée :
 * elle ne touche à aucune donnée, et sert à valider avec l'utilisateur le
 * rapprochement proposé pour les catégories/matières/niveaux/établissements
 * avant d'écrire quoi que ce soit.
 */
class ImportLegacySujets extends Command
{
    private const FUZZY_THRESHOLD = 85;

    protected $signature = 'import:legacy-sujets
        {csv=recovery/toussujets.csv : Chemin du CSV exporté de l\'ancien site}
        {files-dir=recovery/sujets_maxisujets : Dossier contenant les fichiers}
        {--dry-run : Analyse uniquement, aucune écriture (comportement par défaut)}
        {--write : Exécute réellement l\'import (crée la taxonomie manquante puis les sujets)}
        {--owner-email=developpeur@gmail.com : Compte auquel rattacher les sujets importés}';

    protected $description = 'Analyse le CSV des anciens sujets et propose un rapprochement de taxonomie avant import réel';

    public function handle(): int
    {
        $csvPath = base_path($this->argument('csv'));
        $filesDir = base_path($this->argument('files-dir'));

        if (!file_exists($csvPath)) {
            $this->error("CSV introuvable : {$csvPath}");
            return self::FAILURE;
        }

        $rows = $this->parseCsv($csvPath);
        $this->info(count($rows) . ' lignes lues dans le CSV.');
        $this->newLine();

        if ($this->option('write')) {
            return $this->handleWrite($rows, $filesDir);
        }

        // Seul Niveau est une vraie relation n:n (un sujet peut avoir plusieurs niveaux,
        // comme le permet déjà la table pivot niveau_sujet). Catégorie et Matière restent
        // à valeur unique : pour Matière, quand plusieurs valeurs sont listées (rare, 12
        // lignes sur 752), on ne garde que la première — la ou les suivantes sont
        // signalées séparément pour ne pas disparaître silencieusement.
        $this->reportDimension('CATÉGORIE', $rows, 'Catégorie', Categorie::all(['id', 'libelle']), mode: 'none');
        $this->reportDimension('MATIÈRE', $rows, 'Matière', Matiere::all(['id', 'libelle']), mode: 'first');
        $this->reportDimension('NIVEAU', $rows, 'Niveau', Niveau::all(['id', 'libelle']), mode: 'multi');
        $this->reportDimension('ÉTABLISSEMENT', $rows, 'Établissement', Etablissement::all(['id', 'libelle']), mode: 'none');

        $this->reportDroppedSecondaryValues($rows);

        $this->reportFiles($rows, $filesDir);

        return self::SUCCESS;
    }

    /**
     * Import réel : crée la taxonomie manquante (selon les décisions figées dans
     * LegacyDataMap) puis les sujets eux-mêmes, fichier inclus. Chaque ligne est
     * traitée indépendamment (une erreur sur une ligne n'annule pas les autres).
     */
    private function handleWrite(array $rows, string $filesDir): int
    {
        $owner = User::where('email', $this->option('owner-email'))->first();
        if (!$owner) {
            $this->error('Compte propriétaire introuvable : ' . $this->option('owner-email'));
            return self::FAILURE;
        }
        $this->info("Sujets rattachés à : {$owner->email}");

        $categorieCache = [];
        $matiereCache = [];
        $etablissementCache = [];
        $cycleCache = [];
        $niveauCache = [];

        $created = 0;
        $skippedNoFile = 0;
        $errors = [];

        foreach ($rows as $row) {
            $fichier = trim($row['Fichier'] ?? '');
            $sourcePath = $filesDir . DIRECTORY_SEPARATOR . $fichier;

            if ($fichier === '' || !file_exists($sourcePath)) {
                $skippedNoFile++;
                continue;
            }

            try {
                DB::transaction(function () use ($row, $sourcePath, $owner, &$categorieCache, &$matiereCache, &$etablissementCache, &$cycleCache, &$niveauCache) {
                    $categorieLabel = LegacyDataMap::CATEGORIES[trim($row['Catégorie'] ?? '')] ?? trim($row['Catégorie'] ?? '');
                    $categorie = $this->firstOrCreateCached($categorieCache, $categorieLabel, fn ($label) => Categorie::firstOrCreate(
                        ['libelle' => $label],
                        ['statut' => 'active']
                    ));

                    $matiereRaw = trim(explode(',', trim($row['Matière'] ?? ''))[0] ?? '');
                    $matiere = null;
                    if ($matiereRaw !== '') {
                        $matiereLabel = LegacyDataMap::MATIERES[$matiereRaw] ?? $matiereRaw;
                        $matiere = $this->firstOrCreateCached($matiereCache, $matiereLabel, fn ($label) => Matiere::firstOrCreate(
                            ['libelle' => $label],
                            ['statut' => 'active']
                        ));
                    }

                    $etabRaw = trim($row['Établissement'] ?? '');
                    $etablissement = null;
                    if ($etabRaw !== '' && array_key_exists($etabRaw, LegacyDataMap::ETABLISSEMENTS) && LegacyDataMap::ETABLISSEMENTS[$etabRaw] === null) {
                        // valeur jugée non exploitable (ex. "2016", "national") : ignorée
                    } elseif ($etabRaw !== '') {
                        $etabLabel = LegacyDataMap::ETABLISSEMENTS[$etabRaw] ?? $etabRaw;
                        $etablissement = $this->firstOrCreateCached($etablissementCache, $etabLabel, fn ($label) => Etablissement::firstOrCreate(
                            ['libelle' => $label],
                            ['statut' => 'active']
                        ));
                    }

                    $niveauIds = $this->resolveNiveauIds(trim($row['Niveau'] ?? ''), $cycleCache, $niveauCache);

                    $sujet = new Sujet();
                    $sujet->categorie_id = $categorie->id;
                    $sujet->matiere_id = $matiere?->id;
                    $sujet->etablissement_id = $etablissement?->id;
                    $sujet->statut = 'active';
                    $sujet->approuve = true;
                    $sujet->points_attribues = true; // pas de crédit de points : compte système, pas un auteur
                    $sujet->felicitations_vues = true;
                    $sujet->annee = trim($row['Année'] ?? '') ?: null;
                    $sujet->user_id = $owner->id;

                    $corrigeNote = trim($row['Corrigé'] ?? '');
                    $sujet->description = ($corrigeNote !== '' && strcasecmp($corrigeNote, 'Aucun') !== 0)
                        ? 'Corrigé mentionné dans l\'export d\'origine ("' . $corrigeNote . '") mais fichier non retrouvé lors de la récupération.'
                        : null;

                    $sujet->libelle = $categorie->libelle . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ0123456789'), 0, 5);
                    $sujet->code = 'MS' . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ0123456789'), 0, 5);
                    $sujet->save();

                    if (!empty($niveauIds)) {
                        $sujet->niveaux()->sync($niveauIds);
                    }

                    $sujet->addMedia($sourcePath)->preservingOriginal()->toMediaCollection('non_corrige');
                });

                $created++;
            } catch (\Throwable $e) {
                $errors[] = ($row['Titre'] ?? '?') . ' : ' . $e->getMessage();
            }
        }

        $this->newLine();
        $this->info("Sujets créés : {$created}");
        $this->warn("Ignorés (fichier introuvable) : {$skippedNoFile}");
        if ($errors) {
            $this->error(count($errors) . ' erreur(s) :');
            foreach ($errors as $e) {
                $this->line('  - ' . $e);
            }
        }

        return self::SUCCESS;
    }

    private function firstOrCreateCached(array &$cache, string $label, \Closure $factory)
    {
        if (!isset($cache[$label])) {
            $cache[$label] = $factory($label);
        }

        return $cache[$label];
    }

    /**
     * Éclate la cellule Niveau (multi-valeurs + listes parenthésées), passe chaque
     * valeur brute par LegacyDataMap::NIVEAUX (fusion / expansion n:n déjà décidées),
     * puis crée (ou réutilise) chaque niveau canonique résultant sous le bon cycle.
     *
     * @return array<int, string> ids des niveaux à rattacher au sujet
     */
    private function resolveNiveauIds(string $cell, array &$cycleCache, array &$niveauCache): array
    {
        if ($cell === '') {
            return [];
        }

        $rawTokens = array_map('trim', explode(',', $this->expandParenthesizedList($cell)));
        $canonicalLabels = [];

        foreach ($rawTokens as $token) {
            if ($token === '') {
                continue;
            }
            $mapped = LegacyDataMap::NIVEAUX[$token] ?? [$token];
            foreach ($mapped as $canonical) {
                $canonicalLabels[$canonical] = true; // dédoublonne
            }
        }

        $ids = [];
        foreach (array_keys($canonicalLabels) as $label) {
            if (!isset($niveauCache[$label])) {
                if ($label === LegacyDataMap::CYCLE_LYCEE) {
                    $niveauCache[$label] = $this->getOrCreateCycle($label, $cycleCache);
                } else {
                    $cycle = $this->getOrCreateCycle(LegacyDataMap::cycleFor($label), $cycleCache);
                    $position = (string) (Niveau::where('parent_id', $cycle->id)->count() + 1);
                    $niveauCache[$label] = Niveau::firstOrCreate(
                        ['libelle' => $label, 'parent_id' => $cycle->id],
                        ['statut' => 'active', 'position' => $position, 'url' => null]
                    );
                }
            }
            $ids[] = $niveauCache[$label]->id;
        }

        return $ids;
    }

    private function getOrCreateCycle(string $name, array &$cycleCache): Niveau
    {
        if (!isset($cycleCache[$name])) {
            $position = (string) (Niveau::whereNull('parent_id')->count() + 1);
            $cycleCache[$name] = Niveau::firstOrCreate(
                ['libelle' => $name, 'parent_id' => null],
                ['statut' => 'active', 'position' => $position, 'url' => null]
            );
        }

        return $cycleCache[$name];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function parseCsv(string $path): array
    {
        $fh = fopen($path, 'r');
        $header = array_map('trim', fgetcsv($fh, 0, ';'));
        $rows = [];

        while (($row = fgetcsv($fh, 0, ';')) !== false) {
            if (count($row) !== count($header)) {
                continue;
            }
            $rows[] = array_combine($header, array_map('trim', $row));
        }

        fclose($fh);

        return $rows;
    }

    private function normalize(string $value): string
    {
        return Str::of($value)->ascii()->lower()->squish()->toString();
    }

    /**
     * Cas rare (2 lignes sur 752) : "BTS-(RHCOM, FC, AD, GESCOM)" ne veut pas dire "un seul
     * niveau nommé littéralement avec des virgules dedans", mais "BTS-RHCOM, BTS-FC, BTS-AD,
     * BTS-GESCOM" — le préfixe factorisé une seule fois avant la parenthèse. On détecte donc
     * un "préfixe-(a, b, c)" (liste à virgule À L'INTÉRIEUR des parenthèses) et on l'éclate
     * en répétant le préfixe, AVANT le découpage général sur virgule. Un "(SEI)" ou "(HST)"
     * seul (sans virgule dedans) n'est pas touché : il fait partie du code lui-même.
     */
    private function expandParenthesizedList(string $cell): string
    {
        return preg_replace_callback(
            '/([A-Za-zÀ-ÿ0-9 ]*?-)\(([^)]*,[^)]*)\)/',
            function ($matches) {
                $prefix = $matches[1];
                $inner = array_map('trim', explode(',', $matches[2]));

                return implode(', ', array_map(fn ($v) => $prefix . $v, $inner));
            },
            $cell
        );
    }

    private function reportDimension(string $title, array $rows, string $column, Collection $existing, string $mode): void
    {
        $this->line("<fg=cyan;options=bold>=== {$title} ===</>");

        // valeur brute (originale, la mieux "capitalisée" rencontrée) => nb de lignes qui l'utilisent.
        // mode 'multi' (Niveau) : vraie relation n:n, chaque valeur séparée par virgule compte.
        // mode 'first' (Matière) : valeur unique — si plusieurs sont listées, seule la
        //   première est retenue ici (les suivantes sont dans reportDroppedSecondaryValues()).
        // mode 'none' (Catégorie, Établissement) : jamais de virgule dans ces colonnes, valeur telle quelle.
        $raw = [];
        foreach ($rows as $row) {
            $cell = trim($row[$column] ?? '');
            if ($cell === '') {
                continue;
            }

            if ($mode === 'multi') {
                $values = array_map('trim', explode(',', $this->expandParenthesizedList($cell)));
            } elseif ($mode === 'first') {
                $values = [trim(explode(',', $cell)[0])];
            } else {
                $values = [$cell];
            }

            foreach ($values as $value) {
                if ($value === '') {
                    continue;
                }
                $raw[$value] = ($raw[$value] ?? 0) + 1;
            }
        }

        // Regroupement "sûr" niveau 1 : même forme normalisée = fusion automatique.
        $groups = []; // normalisé => ['label' => meilleur libellé, 'count' => total, 'variants' => [...]]
        foreach ($raw as $value => $count) {
            $norm = $this->normalize($value);
            if (!isset($groups[$norm])) {
                $groups[$norm] = ['label' => $value, 'count' => 0, 'variants' => [], 'bestCount' => 0];
            }
            $groups[$norm]['count'] += $count;
            $groups[$norm]['variants'][] = $value;
            if ($count > $groups[$norm]['bestCount']) {
                $groups[$norm]['label'] = $value;
                $groups[$norm]['bestCount'] = $count;
            }
        }

        $existingByNorm = $existing->keyBy(fn ($e) => $this->normalize($e->libelle));

        $exact = [];
        $fuzzyExisting = [];
        $candidatesForNew = []; // norm => group, pour celles qui ne matchent rien en base

        foreach ($groups as $norm => $group) {
            if ($existingByNorm->has($norm)) {
                $exact[] = $group['label'] . ' → ' . $existingByNorm[$norm]->libelle . ' (déjà en base, ' . $group['count'] . ' lignes)';
                continue;
            }

            $bestMatch = null;
            $bestScore = 0;
            foreach ($existing as $e) {
                similar_text($norm, $this->normalize($e->libelle), $percent);
                if ($percent > $bestScore) {
                    $bestScore = $percent;
                    $bestMatch = $e;
                }
            }

            if ($bestMatch && $bestScore >= self::FUZZY_THRESHOLD) {
                $fuzzyExisting[] = $group['label'] . ' → possible correspondance avec "' . $bestMatch->libelle . '" (' . round($bestScore) . '%, ' . $group['count'] . ' lignes) — À CONFIRMER';
            } else {
                $candidatesForNew[$norm] = $group;
            }
        }

        // IMPORTANT : au-delà de la fusion "sûre" (même forme normalisée, déjà faite plus
        // haut), on ne fusionne plus rien automatiquement. Sur ce jeu de données, des
        // codes courts comme "Terminale D" / "Terminale A" / "Terminale C" ne diffèrent
        // que d'un caractère alors qu'ils désignent des séries totalement différentes —
        // un simple score de ressemblance textuelle les aurait fusionnés à tort (constaté
        // en test). On se contente donc d'annoter les valeurs qui SE RESSEMBLENT entre
        // elles, sans jamais les regrouper d'office : la décision reste manuelle.
        $newList = array_values($candidatesForNew);
        usort($newList, fn ($a, $b) => $b['count'] <=> $a['count']);

        $this->info(count($exact) . ' déjà en base (réutilisation directe).');
        $this->info(count($fuzzyExisting) . ' correspondance(s) floue(s) avec l\'existant à confirmer :');
        foreach ($fuzzyExisting as $line) {
            $this->line('  - ' . $line);
        }
        $this->info(count($newList) . ' valeur(s) distincte(s) sans correspondance en base :');
        foreach ($newList as $group) {
            $variantsInfo = count(array_unique($group['variants'])) > 1
                ? ' [variantes identiques fusionnées: ' . implode(' / ', array_unique($group['variants'])) . ']'
                : '';

            $similar = [];
            foreach ($newList as $other) {
                if ($other === $group) {
                    continue;
                }
                similar_text($this->normalize($group['label']), $this->normalize($other['label']), $percent);
                if ($percent >= self::FUZZY_THRESHOLD) {
                    $similar[] = $other['label'];
                }
            }
            $similarInfo = $similar ? ' ⚠ ressemble à : ' . implode(', ', array_unique($similar)) . ' — à vérifier, PAS fusionné automatiquement' : '';

            $this->line('  - ' . $group['label'] . ' (nouveau, ' . $group['count'] . ' lignes)' . $variantsInfo . $similarInfo);
        }

        $this->newLine();
    }

    /**
     * Matière est à valeur unique : quand une ligne en liste plusieurs (12 lignes sur
     * 752, ex. "Economie, Droit"), seule la première est utilisée comme matière du
     * sujet. Cette méthode liste ces lignes-là pour que la ou les valeurs écartées
     * restent visibles quelque part, au lieu de disparaître sans trace.
     */
    private function reportDroppedSecondaryValues(array $rows): void
    {
        $this->line('<fg=cyan;options=bold>=== MATIÈRE(S) SECONDAIRE(S) NON RETENUE(S) ===</>');

        $dropped = [];
        foreach ($rows as $row) {
            $cell = trim($row['Matière'] ?? '');
            if (!str_contains($cell, ',')) {
                continue;
            }
            $parts = array_map('trim', explode(',', $cell));
            $dropped[] = $row['Titre'] . ' — retenu: "' . $parts[0] . '", écarté(s): ' . implode(', ', array_slice($parts, 1));
        }

        $this->info(count($dropped) . ' ligne(s) avec une matière secondaire écartée :');
        foreach ($dropped as $line) {
            $this->line('  - ' . $line);
        }

        $this->newLine();
    }

    private function reportFiles(array $rows, string $filesDir): void
    {
        $this->line('<fg=cyan;options=bold>=== FICHIERS ===</>');

        $missingFile = 0;
        $corrigeUnrecoverable = 0;
        $corrigeUnrecoverableList = [];

        foreach ($rows as $row) {
            $fichier = trim($row['Fichier'] ?? '');
            if ($fichier === '' || !file_exists($filesDir . DIRECTORY_SEPARATOR . $fichier)) {
                $missingFile++;
            }

            $corrige = trim($row['Corrigé'] ?? '');
            if ($corrige !== '' && strcasecmp($corrige, 'Aucun') !== 0) {
                // Le nom de fichier du corrigé (lisible) ne correspond jamais à un fichier
                // physique réel dans ce dossier (seule la colonne Fichier en a un).
                $corrigeUnrecoverable++;
                $corrigeUnrecoverableList[] = $row['Titre'] . ' — corrigé attendu: ' . $corrige;
            }
        }

        $this->info(count($rows) - $missingFile . '/' . count($rows) . ' lignes ont un fichier sujet récupérable.');
        if ($missingFile > 0) {
            $this->warn($missingFile . ' ligne(s) sans fichier sujet retrouvé.');
        }

        $this->info($corrigeUnrecoverable . ' ligne(s) mentionnent un corrigé, dont le fichier réel est introuvable (info gardée en note uniquement) :');
        foreach ($corrigeUnrecoverableList as $line) {
            $this->line('  - ' . $line);
        }
    }
}
