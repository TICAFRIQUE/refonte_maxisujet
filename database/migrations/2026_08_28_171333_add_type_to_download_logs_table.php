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
        Schema::table('download_logs', function (Blueprint $table) {
            // 'non_corrige' ou 'corrige' : jusqu'ici envoyé par le contrôleur mais silencieusement
            // ignoré (ni colonne, ni $fillable), donc impossible de distinguer les deux.
            $table->string('type')->nullable()->after('sujet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('download_logs', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
