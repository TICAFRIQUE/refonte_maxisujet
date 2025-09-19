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
        Schema::table('users', function (Blueprint $table) {
            //
            // autheurs fields
            $table->string('profil')->nullable(); // eleve, enseignant, parent
            $table->datetime('last_login_at')->nullable(); // dernier connexion
            $table->string('last_login_ip')->nullable(); //dernier ip
            $table->integer('point')->nullable()->default(0);
            $table->enum('statut', ['active', 'desactive'])->default('active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
