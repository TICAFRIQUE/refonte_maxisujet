<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

/**
 * Équivalent de ExistsOrNewTaxonomy pour chaque entrée du tableau niveaux[] :
 * accepte un id de niveau réel, ou un sentinel new:N représentant un chemin
 * de niveaux (potentiellement plusieurs crans) suggéré par l'IA.
 */
class NiveauIdOrNewSentinel implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/^new:\d+$/', (string) $value)) {
            return;
        }

        if (!DB::table('niveaux')->where('id', $value)->exists()) {
            $fail('Le niveau sélectionné est invalide.');
        }
    }
}
