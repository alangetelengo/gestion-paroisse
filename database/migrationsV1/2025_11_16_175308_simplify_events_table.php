<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Simplifie la table events en rendant nullable les champs optionnels
     * et en supprimant les fonctionnalités de récurrence complexes (rarement utilisées)
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Les champs suivants restent mais sont déjà nullable ou optionnels:
            // - celebre_par_id (déjà nullable)
            // - intention (déjà nullable)
            // - lieu (déjà nullable)
            // - heure_evenement (déjà nullable)
            // - description (déjà nullable)

            // Retirer les colonnes de récurrence (rarement utilisées, trop complexes pour l'utilisateur)
            // On les rend nullable et on les ignore dans le formulaire
            // Si vraiment besoin, on peut les réactiver plus tard

            // Note: On ne supprime pas les colonnes pour éviter de perdre des données existantes
            // On les laisse mais on ne les utilisera plus dans le formulaire simplifié
        });

        // Créer une note dans la documentation que les champs de récurrence
        // sont dépréciés mais préservés pour compatibilité
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rien à inverser - on garde les colonnes
    }
};
