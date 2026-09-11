<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

/**
 * Accepte soit un id réel existant dans la table donnée, soit un "sentinel"
 * IA au format new:N (ex. "new:1") représentant une catégorie/matière/
 * concours suggérée par l'IA mais pas encore créée en base — sa création
 * réelle est faite plus tard, uniquement si le formulaire est soumis
 * (voir HandlesAiSujetAnalysis::resolveAiTaxonomySentinels()).
 */
class ExistsOrNewTaxonomy implements ValidationRule
{
    public function __construct(private readonly string $table)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/^new:\d+$/', (string) $value)) {
            return;
        }

        if (!DB::table($this->table)->where('id', $value)->exists()) {
            $fail('Le champ :attribute sélectionné est invalide.');
        }
    }
}
