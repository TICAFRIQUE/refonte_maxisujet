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
        // VARCHAR(255) était trop court pour de vraies annonces (ex: un titre d'actualité
        // complet) : passage à TEXT via SQL brut pour ne pas dépendre de doctrine/dbal.
        DB::statement('ALTER TABLE info_flashes MODIFY message TEXT NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE info_flashes MODIFY message VARCHAR(255) NOT NULL');
    }
};
