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
     * Crée les tables pour la gestion financière de la paroisse selon les besoins :
     * - revenues : Recettes (quête ordinaire, extraordinaire, location, popote/subvention, procure)
     * - expenses : Dépenses (charges fixes et variables)
     * - financial_reports : Rapports financiers (optionnel, pour historique)
     */
    public function up(): void
    {
        // ============================================
        // TABLE REVENUE_CATEGORIES (Catégories de Recettes)
        // ============================================
        // Table pour les catégories de recettes (évolutive)
        Schema::create('revenue_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Code unique (ex: 'quete_ordinaire')
            $table->string('nom'); // Nom affiché (ex: 'Quête Ordinaire')
            $table->text('description')->nullable();
            $table->boolean('actif')->default(true);
            $table->integer('ordre')->default(0); // Pour l'ordre d'affichage
            $table->timestamps();

            $table->index('code');
            $table->index('actif');
        });

        // ============================================
        // TABLE REVENUE_TYPES (Types de Recettes)
        // ============================================
        // Table pour les types de recettes (liés aux catégories)
        Schema::create('revenue_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revenue_category_id')
                  ->constrained('revenue_categories')
                  ->onDelete('cascade');
            $table->string('code')->unique(); // Code unique (ex: 'messe_semaine')
            $table->string('nom'); // Nom affiché (ex: 'Messe Semaine')
            $table->text('description')->nullable();
            $table->boolean('actif')->default(true);
            $table->integer('ordre')->default(0); // Pour l'ordre d'affichage
            $table->timestamps();

            $table->index(['revenue_category_id', 'actif']);
            $table->index('code');
        });

        // ============================================
        // TABLE REVENUES (Recettes)
        // ============================================
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();

            // Lien avec la paroisse
            $table->foreignId('paroisse_id')
                  ->constrained('paroisses')
                  ->onDelete('cascade');

            // Catégorisation des recettes (foreign key vers revenue_categories)
            $table->foreignId('revenue_category_id')
                  ->constrained('revenue_categories')
                  ->onDelete('restrict');

            // Type spécifique de recette (foreign key vers revenue_types)
            $table->foreignId('revenue_type_id')
                  ->constrained('revenue_types')
                  ->onDelete('restrict');

            // Pour distinguer semaine/dimanche (quête ordinaire)
            $table->enum('periode_messe', ['semaine', 'dimanche'])->nullable();
            $table->enum('jour_semaine', [
                'lundi', 'mardi', 'mercredi', 'jeudi',
                'vendredi', 'samedi', 'dimanche'
            ])->nullable();

            // Lien avec événement (mariage, obsèques, etc.)
            $table->foreignId('event_id')
                  ->nullable()
                  ->constrained('events')
                  ->onDelete('set null');

            // Montant et date
            $table->decimal('montant', 10, 2);
            $table->date('date_recette');

            // Gestion des recettes récurrentes (popote/subvention mensuelle)
            $table->boolean('est_recurrent')->default(false);
            $table->enum('frequence_recurrence', ['mensuel', 'trimestriel', 'annuel'])
                  ->nullable();

            // Informations de paiement
            $table->enum('methode_paiement', [
                'especes',
                'cheque',
                'virement',
                'carte',
                'mobile_money'
            ])->default('especes');
            $table->string('reference_paiement')->nullable();

            // Validation
            $table->enum('statut', ['en_attente', 'valide', 'rejete'])
                  ->default('valide');

            // Notes additionnelles
            $table->text('notes')->nullable();

            // Traçabilité
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('restrict');
            $table->foreignId('validated_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamp('validated_at')->nullable();

            $table->timestamps();

            // Index pour optimiser les requêtes fréquentes
            $table->index(['paroisse_id', 'date_recette']);
            $table->index(['revenue_category_id', 'date_recette']);
            $table->index(['revenue_type_id', 'date_recette']);
            $table->index(['periode_messe', 'date_recette']);
            $table->index(['est_recurrent', 'frequence_recurrence']);
            $table->index('statut');
        });

        // ============================================
        // INSERTION DES DONNÉES INITIALES
        // ============================================
        // Insérer les catégories de recettes par défaut
        $this->seedRevenueCategories();

        // Insérer les types de recettes par défaut
        $this->seedRevenueTypes();

        // ============================================
        // TABLE EXPENSES (Dépenses/Charges)
        // ============================================
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // Lien avec la paroisse
            $table->foreignId('paroisse_id')
                  ->constrained('paroisses')
                  ->onDelete('cascade');

            // Catégorisation des charges
            $table->enum('categorie_charge', [
                'charge_fixe',           // Charges fixes récurrentes
                'charge_variable',       // Charges variables
                'charge_exceptionnelle'  // Charges exceptionnelles
            ])->default('charge_fixe');

            // Type spécifique de charge selon les besoins du client
            $table->enum('type_charge', [
                'carburant',
                'hosties',
                'internet',
                'maintenance_materiel',
                'gaz',
                'eau',
                'electricite',
                'jardinage',
                'salaire_ouvrier',
                'autre'
            ]);

            // Montant et date
            $table->decimal('montant', 10, 2);
            $table->date('date_depense');

            // Informations facture/fournisseur
            $table->string('facture_reference')->nullable();
            $table->string('fournisseur')->nullable();

            // Informations de paiement
            $table->enum('methode_paiement', [
                'especes',
                'cheque',
                'virement',
                'carte',
                'mobile_money'
            ])->default('especes');

            // Validation
            $table->enum('statut', ['en_attente', 'valide', 'rejete'])
                  ->default('valide');

            // Notes additionnelles
            $table->text('notes')->nullable();

            // Traçabilité
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('restrict');
            $table->foreignId('validated_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamp('validated_at')->nullable();

            $table->timestamps();

            // Index pour optimiser les requêtes fréquentes
            $table->index(['paroisse_id', 'date_depense']);
            $table->index(['categorie_charge', 'date_depense']);
            $table->index(['type_charge', 'date_depense']);
            $table->index('statut');
        });

        // ============================================
        // TABLE FINANCIAL_REPORTS (Rapports Financiers)
        // ============================================
        // Table optionnelle pour stocker les rapports générés
        // Permet de garder un historique des rapports
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();

            // Lien avec la paroisse
            $table->foreignId('paroisse_id')
                  ->constrained('paroisses')
                  ->onDelete('cascade');

            // Type de période selon les besoins du client
            // - semaine : Lundi à Samedi
            // - dimanche : Dimanche uniquement
            // - total : Semaine + Dimanche
            $table->enum('periode_type', ['semaine', 'dimanche', 'total']);

            // Période du rapport
            $table->date('date_debut');
            $table->date('date_fin');

            // Totaux calculés
            $table->decimal('total_recettes', 10, 2)->default(0);
            $table->decimal('total_depenses', 10, 2)->default(0);
            $table->decimal('solde', 10, 2)->default(0);

            // Détails en JSON pour flexibilité
            // Structure : {
            //   "recettes": {
            //     "quete_ordinaire": {...},
            //     "quete_extraordinaire": {...},
            //     ...
            //   },
            //   "depenses": {
            //     "charge_fixe": {...},
            //     ...
            //   }
            // }
            $table->json('details_recettes')->nullable();
            $table->json('details_depenses')->nullable();

            // Traçabilité
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('restrict');

            $table->timestamps();

            // Index
            $table->index(['paroisse_id', 'date_debut', 'date_fin']);
            $table->index('periode_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('revenues');
        Schema::dropIfExists('revenue_types');
        Schema::dropIfExists('revenue_categories');
    }

    /**
     * Insérer les catégories de recettes par défaut
     */
    private function seedRevenueCategories(): void
    {
        $categories = [
            [
                'code' => 'quete_ordinaire',
                'nom' => 'Quête Ordinaire',
                'description' => 'Messes de la semaine (Lundi à Samedi) et messe du dimanche',
                'ordre' => 1,
            ],
            [
                'code' => 'quete_extraordinaire',
                'nom' => 'Quête Extraordinaire',
                'description' => 'Mariage, obsèques, action de grâce, caritas, la grotte, etc.',
                'ordre' => 2,
            ],
            [
                'code' => 'location',
                'nom' => 'Location',
                'description' => 'Loyers (boutiques), salle de fête, chapiteaux, cour de la paroisse',
                'ordre' => 3,
            ],
            [
                'code' => 'popote_subvention',
                'nom' => 'Popote / Subvention',
                'description' => 'Subventions mensuelles récurrentes venant de la hiérarchie',
                'ordre' => 4,
            ],
            [
                'code' => 'procure',
                'nom' => 'Procure',
                'description' => 'Dîmes, denier du culte, casuel (baptêmes des enfants)',
                'ordre' => 5,
            ],
        ];

        foreach ($categories as $category) {
            DB::table('revenue_categories')->insert(array_merge($category, [
                'actif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Insérer les types de recettes par défaut
     */
    private function seedRevenueTypes(): void
    {
        // Récupérer les catégories
        $queteOrdinaire = DB::table('revenue_categories')->where('code', 'quete_ordinaire')->first();
        $queteExtraordinaire = DB::table('revenue_categories')->where('code', 'quete_extraordinaire')->first();
        $location = DB::table('revenue_categories')->where('code', 'location')->first();
        $popoteSubvention = DB::table('revenue_categories')->where('code', 'popote_subvention')->first();
        $procure = DB::table('revenue_categories')->where('code', 'procure')->first();

        $types = [
            // Quête ordinaire
            [
                'revenue_category_id' => $queteOrdinaire->id,
                'code' => 'messe_semaine',
                'nom' => 'Messe Semaine',
                'description' => 'Messes du lundi au samedi',
                'ordre' => 1,
            ],
            [
                'revenue_category_id' => $queteOrdinaire->id,
                'code' => 'messe_dimanche',
                'nom' => 'Messe Dimanche',
                'description' => 'Messe du dimanche',
                'ordre' => 2,
            ],
            // Quête extraordinaire
            [
                'revenue_category_id' => $queteExtraordinaire->id,
                'code' => 'mariage',
                'nom' => 'Mariage',
                'description' => 'Recette de mariage',
                'ordre' => 1,
            ],
            [
                'revenue_category_id' => $queteExtraordinaire->id,
                'code' => 'obseques',
                'nom' => 'Obsèques',
                'description' => 'Recette d\'obsèques',
                'ordre' => 2,
            ],
            [
                'revenue_category_id' => $queteExtraordinaire->id,
                'code' => 'action_grace',
                'nom' => 'Action de Grâce',
                'description' => 'Action de grâce (anniversaire, etc.)',
                'ordre' => 3,
            ],
            [
                'revenue_category_id' => $queteExtraordinaire->id,
                'code' => 'caritas',
                'nom' => 'Caritas',
                'description' => 'Recette Caritas',
                'ordre' => 4,
            ],
            [
                'revenue_category_id' => $queteExtraordinaire->id,
                'code' => 'grotte',
                'nom' => 'La Grotte',
                'description' => 'Recette de la grotte',
                'ordre' => 5,
            ],
            [
                'revenue_category_id' => $queteExtraordinaire->id,
                'code' => 'autre_extraordinaire',
                'nom' => 'Autre Extraordinaire',
                'description' => 'Autre recette extraordinaire',
                'ordre' => 6,
            ],
            // Location
            [
                'revenue_category_id' => $location->id,
                'code' => 'loyer_boutique',
                'nom' => 'Loyer Boutique',
                'description' => 'Loyer d\'une boutique',
                'ordre' => 1,
            ],
            [
                'revenue_category_id' => $location->id,
                'code' => 'salle_fete',
                'nom' => 'Salle de Fête',
                'description' => 'Location de salle de fête',
                'ordre' => 2,
            ],
            [
                'revenue_category_id' => $location->id,
                'code' => 'chapiteau',
                'nom' => 'Chapiteau',
                'description' => 'Location de chapiteau',
                'ordre' => 3,
            ],
            [
                'revenue_category_id' => $location->id,
                'code' => 'cour_paroisse',
                'nom' => 'Cour de la Paroisse',
                'description' => 'Location de la cour de la paroisse',
                'ordre' => 4,
            ],
            // Popote/Subvention
            [
                'revenue_category_id' => $popoteSubvention->id,
                'code' => 'popote_mensuelle',
                'nom' => 'Popote Mensuelle',
                'description' => 'Popote mensuelle récurrente',
                'ordre' => 1,
            ],
            [
                'revenue_category_id' => $popoteSubvention->id,
                'code' => 'subvention_hierarchie_mensuelle',
                'nom' => 'Subvention Hiérarchie Mensuelle',
                'description' => 'Subvention mensuelle de la hiérarchie',
                'ordre' => 2,
            ],
            [
                'revenue_category_id' => $popoteSubvention->id,
                'code' => 'autre_subvention',
                'nom' => 'Autre Subvention',
                'description' => 'Autre subvention',
                'ordre' => 3,
            ],
            // Procure
            [
                'revenue_category_id' => $procure->id,
                'code' => 'dime',
                'nom' => 'Dîme',
                'description' => 'Dîmes',
                'ordre' => 1,
            ],
            [
                'revenue_category_id' => $procure->id,
                'code' => 'denier_culte',
                'nom' => 'Denier du Culte',
                'description' => 'Denier du culte',
                'ordre' => 2,
            ],
            [
                'revenue_category_id' => $procure->id,
                'code' => 'casuel_bapteme',
                'nom' => 'Casuel (Baptêmes)',
                'description' => 'Casuel pour les baptêmes des enfants',
                'ordre' => 3,
            ],
        ];

        foreach ($types as $type) {
            DB::table('revenue_types')->insert(array_merge($type, [
                'actif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
};

