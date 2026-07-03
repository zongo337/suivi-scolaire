<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Annonces de l'école et notifications importantes (examens, réunions,
 * échéances de paiement) destinées aux parents.
 * - classe_id = null  -> annonce visible par tous les parents de l'école
 * - classe_id rempli  -> annonce visible uniquement par les parents
 *   dont un enfant appartient à cette classe
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu');
            $table->enum('type', ['annonce', 'notification'])->default('annonce');
            $table->foreignId('classe_id')->nullable()->constrained('classes')->onDelete('cascade');
            $table->dateTime('date_publication')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
