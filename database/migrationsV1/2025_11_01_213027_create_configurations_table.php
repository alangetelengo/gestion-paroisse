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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paroisse_id')->nullable()->constrained('paroisses')->onDelete('cascade');
            $table->string('cle'); // Clé de configuration (ex: 'monnaie', 'format_date', 'langue')
            $table->text('valeur')->nullable(); // Valeur de la configuration (JSON ou texte)
            $table->string('type')->default('string'); // Type : string, integer, boolean, json
            $table->text('description')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->unique(['paroisse_id', 'cle']); // Une seule configuration par clé par paroisse
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
