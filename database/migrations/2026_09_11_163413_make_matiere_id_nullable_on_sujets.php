<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 7 sujets de l'import legacy n'ont aucune matière renseignée dans la source —
        // une donnée réelle, pas une erreur. matiere_id passe donc nullable (SQL brut,
        // pas de ->change() pour ne pas dépendre de doctrine/dbal).
        DB::statement('ALTER TABLE sujets MODIFY matiere_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE sujets MODIFY matiere_id BIGINT UNSIGNED NOT NULL');
    }
};
