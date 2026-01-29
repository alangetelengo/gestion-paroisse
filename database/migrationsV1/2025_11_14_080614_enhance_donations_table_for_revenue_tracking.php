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
        Schema::table('donations', function (Blueprint $table) {
            // Si c'est une recette liée à une messe/événement
            $table->boolean('est_recette_evenement')->default(false)->after('event_id');

            // Type de recette pour les événements (source plus détaillée)
            $table->enum('source_recette', ['messe', 'evenement', 'don_direct', 'cotisation', 'quete', 'dime', 'offrande'])->nullable()->after('type');

            // Statut de validation (pour les trésoriers)
            $table->enum('statut', ['en_attente', 'valide', 'rejete'])->default('valide')->after('notes');

            // Référence du reçu/chèque
            $table->string('reference_paiement')->nullable()->after('statut');

            // Méthode de paiement
            $table->enum('methode_paiement', ['especes', 'cheque', 'virement', 'carte', 'mobile_money'])->default('especes')->after('reference_paiement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn([
                'est_recette_evenement',
                'source_recette',
                'statut',
                'reference_paiement',
                'methode_paiement'
            ]);
        });
    }
};
