<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sujets', function (Blueprint $table) {
            // Trace si les points de publication ont déjà été crédités à l'auteur pour ce
            // sujet, afin de ne les donner qu'à l'approbation (et de les reprendre si un
            // admin retire l'approbation) sans jamais créditer/débiter deux fois.
            $table->boolean('points_attribues')->default(false)->after('approuve');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sujets', function (Blueprint $table) {
            $table->dropColumn('points_attribues');
        });
    }
};
