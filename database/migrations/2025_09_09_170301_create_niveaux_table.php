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
        Schema::create('niveaux', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('url')->nullable();
            $table->string('statut')->nullable();
            $table->string('position')->nullable();


            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('niveaux')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveaux');
    }
};
