<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Rôle : admin ou enseignant
            $table->enum('role', ['admin', 'enseignant'])->default('admin')->after('email');
            // Classe assignée à l'enseignant (null pour admin)
            $table->foreignId('classe_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('classes')
                  ->onDelete('set null');
            // Statut du compte
            $table->boolean('active')->default(true)->after('classe_id');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'classe_id', 'active']);
        });
    }
};