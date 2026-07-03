<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Table pivot parent <-> élève.
 * Un parent peut suivre plusieurs enfants, et (cas rare mais possible)
 * un élève peut être suivi par deux comptes parents différents
 * (ex : père et mère avec des comptes séparés).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('eleve_parent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->foreignId('parent_id')->constrained('parents')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['eleve_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleve_parent');
    }
};
