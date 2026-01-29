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
        Schema::table('events', function (Blueprint $table) {
            // Vérifier si les colonnes existent déjà avant de les créer
            if (!Schema::hasColumn('events', 'is_recurring')) {
                $table->boolean('is_recurring')->default(false)->after('type');
            }
            
            // Type de récurrence : daily, weekly, monthly, yearly
            if (!Schema::hasColumn('events', 'recurrence_type')) {
                $table->enum('recurrence_type', ['daily', 'weekly', 'monthly', 'yearly'])->nullable()->after('is_recurring');
            }
            
            // Intervalle de récurrence (chaque X jours/semaines/mois/années)
            if (!Schema::hasColumn('events', 'recurrence_interval')) {
                $table->integer('recurrence_interval')->default(1)->after('recurrence_type');
            }
            
            // Date de fin de récurrence (optionnel)
            if (!Schema::hasColumn('events', 'recurrence_end_date')) {
                $table->date('recurrence_end_date')->nullable()->after('recurrence_interval');
            }
            
            // Nombre d'occurrences à générer (optionnel, alternative à recurrence_end_date)
            if (!Schema::hasColumn('events', 'recurrence_count')) {
                $table->integer('recurrence_count')->nullable()->after('recurrence_end_date');
            }
            
            // Jour de la semaine pour récurrence hebdomadaire (0=dimanche, 1=lundi, etc.)
            if (!Schema::hasColumn('events', 'recurrence_day_of_week')) {
                $table->integer('recurrence_day_of_week')->nullable()->after('recurrence_count');
            }
            
            // ID de l'événement parent (null si c'est un événement parent, ID si c'est une occurrence)
            if (!Schema::hasColumn('events', 'parent_event_id')) {
                $table->foreignId('parent_event_id')->nullable()->constrained('events')->onDelete('cascade')->after('paroisse_id');
            }
            
            // Indicateur si l'événement est une occurrence générée automatiquement
            if (!Schema::hasColumn('events', 'is_generated')) {
                $table->boolean('is_generated')->default(false)->after('parent_event_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['parent_event_id']);
            $table->dropColumn([
                'is_recurring',
                'recurrence_type',
                'recurrence_interval',
                'recurrence_end_date',
                'recurrence_count',
                'recurrence_day_of_week',
                'parent_event_id',
                'is_generated'
            ]);
        });
    }
};
