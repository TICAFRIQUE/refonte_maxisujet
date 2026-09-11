<?php

namespace App\Console\Commands\Support;

/**
 * Décisions de rapprochement pour l'import de recovery/toussujets.csv, établies
 * manuellement (relecture des 104 valeurs de Niveau, 37 d'Établissement, etc. sorties
 * par `import:legacy-sujets --dry-run`) plutôt que par un algorithme, car un simple
 * score de ressemblance textuelle fusionnait à tort des séries de Bac distinctes
 * (ex. "Terminale D" / "Terminale A" — un seul caractère d'écart, sens différent).
 *
 * Règle générale : seules les variantes clairement identiques (fautes de frappe,
 * numéro d'année de filière BTS, casse) sont fusionnées. Les séries/filières
 * réellement distinctes restent des niveaux séparés, même si leurs noms se
 * ressemblent.
 */
class LegacyDataMap
{
    public const CYCLE_COLLEGE = 'Collège';
    public const CYCLE_LYCEE = 'Lycée';
    public const CYCLE_SUPERIEUR = 'Université';

    /**
     * Catégorie brute (CSV) => libellé canonique (déjà en base ou à créer).
     */
    public const CATEGORIES = [
        'Exercice' => 'Exercices',
        'Examen' => 'Examens',
        'Devoir' => 'Devoirs',
        'TP' => 'TP',
    ];

    /**
     * Matière brute (CSV, après ne-garder-que-la-première-valeur) => libellé canonique.
     * Seules les fautes de frappe/variantes évidentes sont listées ici ; toute valeur
     * absente de cette table est créée telle quelle (après mise en forme Titre Case).
     */
    public const MATIERES = [
        'Mathematique' => 'Mathématiques',
        'Mthematique' => 'Mathématiques',
        'PHYSIQUE CHIMIE' => 'Physique-Chimie',
        'PHISYQUE-CHIMIE' => 'Physique-Chimie',
        'Espagnole' => 'Espagnol',
        'E-marketing' => 'Marketing',
        'TREPRENEURIAT' => 'Entrepreneuriat',
    ];

    /**
     * Établissement brut (CSV) => libellé canonique. "null" = donnée jugée non
     * exploitable (ex. "2016", "national" — pas un nom d'établissement), la ligne
     * est importée sans établissement plutôt que de créer une entrée absurde.
     */
    public const ETABLISSEMENTS = [
        'PIGER' => 'PIGIER',
        'CHAMBRE COMMERCE' => 'CHAMBRE DE COMMERCE',
        'Lycée Mamie Adjoua' => 'Lycée Mamie Adjoua de Yamoussoukro',
        '2016' => null,
        'national' => null,
    ];

    /**
     * Niveau brut (CSV, après explosion des cellules multi-valeurs et des listes
     * parenthésées) => un ou plusieurs libellés canoniques (plusieurs quand le libellé
     * brut désigne en fait plusieurs séries/filières à la fois, ex. "Terminal C-D").
     * Toute valeur absente de cette table est créée telle quelle (un seul niveau).
     */
    public const NIVEAUX = [
        // --- Collège ---
        '3ème' => ['3ème'],
        'BEPC' => ['BEPC'],

        // --- Lycée : Terminale, variantes/fautes de frappe fusionnées ---
        'Terminal D' => ['Terminale D'],
        'BAC D' => ['Terminale D'],
        'teerminal' => ['Terminale'],
        'Baccalauréat' => ['Terminale'],
        'Baccalauriat' => ['Terminale'],
        'BAC A' => ['Terminale A'],
        'BAC A2' => ['Terminale A2'],
        'BAC B' => ['Terminale B'],
        'BAC C' => ['Terminale C'],
        'BAC G1' => ['Terminale G1'],
        'BAC G2' => ['Terminale G2'],
        'BAC G' => ['Terminale G'],
        'BAC H' => ['Terminale H'],
        'BAC F' => ['Terminale F'],
        'BAC F3' => ['Terminale F3'],
        '1ere' => ['Première'],

        // --- Lycée : libellés combinant plusieurs séries à la fois (n:n) ---
        'Terminal C-D' => ['Terminale C', 'Terminale D'],
        'Terminal A B C D' => ['Terminale A', 'Terminale B', 'Terminale C', 'Terminale D'],
        'Terminal A et D' => ['Terminale A', 'Terminale D'],
        'Terminale A-C-D' => ['Terminale A', 'Terminale C', 'Terminale D'],
        'BAC BLANC TERMINAL A1 A2 C ET D' => ['Terminale A1', 'Terminale A2', 'Terminale C', 'Terminale D'],
        'BAC A B C D' => ['Terminale A', 'Terminale B', 'Terminale C', 'Terminale D'],
        'BAC A-B-C-D-E-H' => ['Terminale A', 'Terminale B', 'Terminale C', 'Terminale D', 'Terminale E', 'Terminale H'],
        'BAC A-D' => ['Terminale A', 'Terminale D'],
        'Bac C D E' => ['Terminale C', 'Terminale D', 'Terminale E'],
        'BAC C E D' => ['Terminale C', 'Terminale E', 'Terminale D'],
        'BAC F-G' => ['Terminale F', 'Terminale G'],
        'BAC G1 G2' => ['Terminale G1', 'Terminale G2'],
        'BAC G1G2-H' => ['Terminale G1', 'Terminale G2', 'Terminale H'],

        // --- Université/Supérieur : BTS, variantes fusionnées ---
        'BTS 2-IDA' => ['BTS-IDA'],
        'BTS IDA' => ['BTS-IDA'],
        'BTS GEC' => ['BTS-GEC'],
        'BTS 2 GEC' => ['BTS-GEC'],
        'BTS GEC 1' => ['BTS-GEC'],
        'BTS GESTION COMMERCIALE' => ['BTS-GEC'],
        'BTS-GESCOM' => ['BTS-GEC'],
        'BT Tertiaire' => ['BTS Tertiaire'],
        'BTS 2 tertiaire' => ['BTS Tertiaire'],
        'BTS1 tertiaire' => ['BTS Tertiaire'],
        'BTS 1 tertiaire' => ['BTS Tertiaire'],
        'BTS2 tertiaire' => ['BTS Tertiaire'],
        'BTS toutes filieres tertiaires' => ['BTS Tertiaire'],
        'BT Industrielle' => ['BTS Industriel'],
        'BTS RH COM' => ['BTS-RHC'],
        'BTS-RHCOM' => ['BTS-RHC'],
        'BTS SEI' => ['BTS-(SEI)'],
        'BTS LOGISTIQUE' => ['BTS-LOGISTIQUE'],
        'BTS FINANCE COMPTABILITE GESTION DES ENTREPRISES' => ['BTS-FCGE'],
        'BTS FC' => ['BTS-FC'],
        'BTS 2 - FC' => ['BTS-FC'],
        'BTS BLANC' => ['BTS'],
        'BTS 2-ASSISTANAT DE DIRECTION' => ['BTS-AD'],
        'BTS 2 IG' => ['BTS-IG'],
        'BTS 2 RESEAU GENIE LOGICIEL' => ['BTS-RESEAU GENIE LOGICIEL'],
        'BTS informatique développeur d\'application' => ["BTS-Informatique Développeur d'Application"],
        'Licence professionnelle' => ['Licence Professionnelle'],
        'LICENCE PROFESSIONNELLE 2' => ['Licence Professionnelle 2'],
        'LICENCE PROFESIONNELLE 1' => ['Licence Professionnelle 1'],

        // --- Université/Supérieur : libellés combinant plusieurs filières (n:n) ---
        'BTS 2 TERTIAIRE-GESCOM' => ['BTS Tertiaire', 'BTS-GEC'],
        'BTS 2 TERTIAIRE-RHCOM' => ['BTS Tertiaire', 'BTS-RHC'],
        'BTS 2 TERTIAIRE-AD' => ['BTS Tertiaire', 'BTS-AD'],

        // --- Lignes à faible confiance : ignorées plutôt que de créer un niveau ambigu ---
        'S' => [],
    ];

    /**
     * Cycle parent de chaque niveau CANONIQUE (après application de NIVEAUX ci-dessus).
     * Tout canonique absent d'ici est rattaché à self::CYCLE_SUPERIEUR par défaut (la
     * grande majorité des valeurs non-Terminale/non-3ème/BEPC de ce jeu de données sont
     * des filières BTS/Licence/Master post-bac).
     */
    public static function cycleFor(string $canonicalNiveau): string
    {
        if (in_array($canonicalNiveau, ['3ème', 'BEPC'], true)) {
            return self::CYCLE_COLLEGE;
        }

        if (str_starts_with($canonicalNiveau, 'Terminale') || $canonicalNiveau === 'Première' || $canonicalNiveau === self::CYCLE_LYCEE) {
            return self::CYCLE_LYCEE;
        }

        return self::CYCLE_SUPERIEUR;
    }
}
