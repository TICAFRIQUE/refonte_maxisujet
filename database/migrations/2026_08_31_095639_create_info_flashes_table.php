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
        Schema::create('info_flashes', function (Blueprint $table) {
            $table->id();
            $table->string('message');
            $table->string('lien')->nullable();
            $table->string('lien_texte')->nullable();
            $table->enum('type', ['info', 'succes', 'attention', 'urgent'])->default('info');
            $table->integer('position')->default(1);
            $table->enum('statut', ['active', 'desactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_flashes');
    }
};
