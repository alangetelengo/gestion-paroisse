<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ajoute le champ mois_location pour enregistrer le mois de paiement
     * des loyers (location boutique mensuelle).
     */
    public function up(): void
    {
        Schema::table('revenues', function (Blueprint $table): void {
            // Format: YYYY-MM (ex: 2026-01 pour Janvier 2026)
            $table->string('mois_location', 7)->nullable()->after('jour_semaine');

            $table->index(['mois_location', 'revenue_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revenues', function (Blueprint $table): void {
            $table->dropIndex(['mois_location', 'revenue_type_id']);
            $table->dropColumn('mois_location');
        });
    }
};
