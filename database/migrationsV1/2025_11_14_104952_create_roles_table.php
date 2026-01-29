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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du rôle (ex: "Administrateur Paroisse")
            $table->string('slug')->unique(); // Slug unique (ex: "paroisse_admin")
            $table->text('description')->nullable(); // Description du rôle
            $table->boolean('is_system')->default(false); // Rôle système (ne peut pas être supprimé)
            $table->boolean('active')->default(true); // Actif ou non
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
