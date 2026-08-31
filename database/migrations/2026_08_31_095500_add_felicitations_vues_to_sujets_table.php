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
            // Trace si l'auteur a déjà vu le message de félicitations pour les points
            // gagnés sur ce sujet. Par défaut à true pour ne pas ressusciter d'anciennes
            // approbations : seuls les nouveaux passages à points_attribues=true (à partir
            // de maintenant) mettent explicitement ce champ à false.
            $table->boolean('felicitations_vues')->default(true)->after('points_attribues');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sujets', function (Blueprint $table) {
            $table->dropColumn('felicitations_vues');
        });
    }
};
