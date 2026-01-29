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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de la permission (ex: "Gérer les membres")
            $table->string('slug')->unique(); // Slug unique (ex: "manage_members")
            $table->text('description')->nullable(); // Description de la permission
            $table->string('category')->default('general'); // Catégorie (ex: "members", "finances", "reports")
            $table->boolean('active')->default(true); // Actif ou non
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
