<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Informations du donateur (dîme, don à la paroisse) — catégorie Procure.
     */
    public function up(): void
    {
        Schema::table('revenues', function (Blueprint $table): void {
            $table->string('donateur_nom')->nullable()->after('notes');
            $table->string('donateur_telephone')->nullable()->after('donateur_nom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revenues', function (Blueprint $table): void {
            $table->dropColumn(['donateur_nom', 'donateur_telephone']);
        });
    }
};
