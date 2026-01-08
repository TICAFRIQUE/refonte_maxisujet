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
        Schema::create('rubriques', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->longText('contenu');
            $table->text('resume')->nullable();
            $table->string('type_rubrique'); // 'actualite' ou 'astuce_conseil'
            $table->string('image_principale')->nullable();
            $table->boolean('est_publie')->default(false);
            $table->boolean('est_featured')->default(false);
            $table->integer('ordre_affichage')->default(0);
            $table->json('tags')->nullable();
            $table->unsignedBigInteger('auteur_id')->nullable();
            $table->timestamp('date_publication')->nullable();
            $table->integer('nb_vues')->default(0);
            $table->timestamps();
            
            $table->foreign('auteur_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['type_rubrique', 'est_publie']);
            $table->index(['date_publication', 'est_publie']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubriques');
    }
};
