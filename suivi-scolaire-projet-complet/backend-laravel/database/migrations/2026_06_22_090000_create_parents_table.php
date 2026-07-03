<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Table des comptes parents.
 * Un parent dispose d'un compte distinct des "users" (admin/enseignant)
 * et se connecte uniquement depuis l'application mobile via l'API (Sanctum).
 * Le compte est créé par l'administrateur, qui transmet ensuite les
 * identifiants au parent.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
