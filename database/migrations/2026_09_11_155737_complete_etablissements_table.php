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
        // La table etablissements était une coquille vide (id + timestamps seulement) :
        // on la complète pour suivre le même schéma que categories/matieres/concours.
        Schema::table('etablissements', function (Blueprint $table) {
            $table->string('libelle')->after('id');
            $table->string('slug')->unique()->after('libelle');
            $table->enum('statut', ['active', 'desactive'])->default('active')->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etablissements', function (Blueprint $table) {
            $table->dropColumn(['libelle', 'slug', 'statut']);
        });
    }
};
