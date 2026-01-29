<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migre les données existantes de la table `donations` vers la nouvelle table `revenues`
     * selon la logique de catégorisation définie.
     */
    public function up(): void
    {
        // Vérifier si la table donations existe et contient des données
        if (Schema::hasTable('donations') && DB::table('donations')->count() > 0) {
            
            // Récupérer toutes les donations existantes
            $donations = DB::table('donations')->get();
            
            // Récupérer les catégories et types depuis les tables
            $categories = DB::table('revenue_categories')->pluck('id', 'code')->toArray();
            $types = DB::table('revenue_types')->pluck('id', 'code')->toArray();
            
            foreach ($donations as $donation) {
                // Déterminer la catégorie et le type selon l'ancien type
                $categorieCode = 'quete_ordinaire'; // par défaut
                $typeCode = 'messe_semaine'; // par défaut
                $periodeMesse = null;
                $jourSemaine = null;
                $estRecurrent = false;
                $frequenceRecurrence = null;
                
                // Mapping de l'ancien type vers la nouvelle structure
                switch ($donation->type) {
                    case 'messe':
                        $categorieCode = 'quete_ordinaire';
                        $typeCode = 'messe_semaine';
                        // Essayer de déterminer si c'est dimanche ou semaine
                        if ($donation->event_id) {
                            // Si lié à un événement, vérifier le jour
                            $event = DB::table('events')->where('id', $donation->event_id)->first();
                            if ($event) {
                                $dateEvent = \Carbon\Carbon::parse($event->date_evenement ?? $donation->date_don);
                                $jourSemaine = strtolower($dateEvent->locale('fr')->dayName);
                                if ($jourSemaine === 'dimanche') {
                                    $typeCode = 'messe_dimanche';
                                    $periodeMesse = 'dimanche';
                                } else {
                                    $periodeMesse = 'semaine';
                                }
                            }
                        }
                        break;
                        
                    case 'quête':
                        $categorieCode = 'quete_ordinaire';
                        $typeCode = 'messe_semaine';
                        $periodeMesse = 'semaine';
                        break;
                        
                    case 'dîme':
                        $categorieCode = 'procure';
                        $typeCode = 'dime';
                        break;
                        
                    case 'offrande':
                        $categorieCode = 'quete_ordinaire';
                        $typeCode = 'messe_semaine';
                        break;
                        
                    case 'don':
                        $categorieCode = 'quete_extraordinaire';
                        $typeCode = 'autre_extraordinaire';
                        break;
                        
                    case 'cotisation':
                        $categorieCode = 'quete_extraordinaire';
                        $typeCode = 'autre_extraordinaire';
                        break;
                        
                    default:
                        $categorieCode = 'quete_ordinaire';
                        $typeCode = 'messe_semaine';
                }
                
                // Déterminer le jour de la semaine si possible
                if (!$jourSemaine && $donation->date_don) {
                    try {
                        $date = \Carbon\Carbon::parse($donation->date_don);
                        $jourSemaine = strtolower($date->locale('fr')->dayName);
                    } catch (\Exception $e) {
                        $jourSemaine = null;
                    }
                }
                
                // Vérifier si c'est une recette liée à un événement
                $estRecetteEvenement = false;
                if ($donation->event_id) {
                    $estRecetteEvenement = true;
                    // Si l'événement est un mariage, obsèques, etc.
                    $event = DB::table('events')->where('id', $donation->event_id)->first();
                    if ($event) {
                        $eventType = strtolower($event->type ?? '');
                        if (str_contains($eventType, 'mariage') || str_contains($event->titre ?? '', 'mariage')) {
                            $categorieCode = 'quete_extraordinaire';
                            $typeCode = 'mariage';
                        } elseif (str_contains($eventType, 'obsèque') || str_contains($event->titre ?? '', 'obsèque')) {
                            $categorieCode = 'quete_extraordinaire';
                            $typeCode = 'obseques';
                        }
                    }
                }
                
                // Récupérer les IDs des catégories et types
                $revenueCategoryId = $categories[$categorieCode] ?? $categories['quete_ordinaire'];
                $revenueTypeId = $types[$typeCode] ?? $types['messe_semaine'];
                
                // Insérer dans la nouvelle table revenues
                DB::table('revenues')->insert([
                    'paroisse_id' => $donation->paroisse_id ?? 1, // Par défaut si null
                    'revenue_category_id' => $revenueCategoryId,
                    'revenue_type_id' => $revenueTypeId,
                    'periode_messe' => $periodeMesse,
                    'jour_semaine' => $jourSemaine,
                    'event_id' => $donation->event_id,
                    'montant' => $donation->montant,
                    'date_recette' => $donation->date_don,
                    'est_recurrent' => $estRecurrent,
                    'frequence_recurrence' => $frequenceRecurrence,
                    'methode_paiement' => $donation->methode_paiement ?? 'especes',
                    'reference_paiement' => $donation->reference_paiement ?? null,
                    'statut' => $donation->statut ?? 'valide',
                    'notes' => $donation->notes,
                    'created_by' => 1, // Utilisateur par défaut (à adapter selon votre système)
                    'validated_by' => null,
                    'validated_at' => null,
                    'created_at' => $donation->created_at ?? now(),
                    'updated_at' => $donation->updated_at ?? now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Note: Cette migration ne supprime pas les données de revenues
     * car elles peuvent avoir été modifiées. Si vous voulez restaurer,
     * vous devrez le faire manuellement.
     */
    public function down(): void
    {
        // Ne rien faire en down pour préserver les données migrées
        // Si vous voulez vraiment restaurer, vous devrez le faire manuellement
    }
};

