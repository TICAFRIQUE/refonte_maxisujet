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
        Schema::create('sujets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('libelle')->unique()->nullable();
            $table->text('description')->nullable();
            $table->enum('statut', ['active', 'desactive'])->default('active')->nullable();
            $table->boolean('approuve')->default(false);
            $table->string('annee')->nullable();

            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade')->nullable();
            // $table->foreignId('etablissement_id')->constrained('etablissements')->onDelete('cascade')->onUpdate('cascade')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sujets');
    }
};
