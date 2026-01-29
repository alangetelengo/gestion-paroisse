<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vider le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============================================
        // CRÉATION DES PERMISSIONS
        // ============================================

        $makeLabel = static fn (string $name): string => ucfirst(str_replace('_', ' ', $name));

        // Dashboard
        Permission::firstOrCreate(
            ['name' => 'view_dashboard', 'guard_name' => 'web'],
            ['libelle_permission' => $makeLabel('view_dashboard')]
        );

        // Membres
        Permission::firstOrCreate(['name' => 'view_members', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_members')]);
        Permission::firstOrCreate(['name' => 'create_members', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('create_members')]);
        Permission::firstOrCreate(['name' => 'edit_members', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('edit_members')]);
        Permission::firstOrCreate(['name' => 'delete_members', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('delete_members')]);
        Permission::firstOrCreate(['name' => 'export_members', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('export_members')]);
        Permission::firstOrCreate(['name' => 'import_members', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('import_members')]);

        // Sacrements - Baptêmes
        Permission::firstOrCreate(['name' => 'view_baptisms', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_baptisms')]);
        Permission::firstOrCreate(['name' => 'create_baptisms', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('create_baptisms')]);
        Permission::firstOrCreate(['name' => 'edit_baptisms', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('edit_baptisms')]);
        Permission::firstOrCreate(['name' => 'delete_baptisms', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('delete_baptisms')]);

        // Sacrements - Confirmations
        Permission::firstOrCreate(['name' => 'view_confirmations', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_confirmations')]);
        Permission::firstOrCreate(['name' => 'create_confirmations', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('create_confirmations')]);
        Permission::firstOrCreate(['name' => 'edit_confirmations', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('edit_confirmations')]);
        Permission::firstOrCreate(['name' => 'delete_confirmations', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('delete_confirmations')]);

        // Sacrements - Communions
        Permission::firstOrCreate(['name' => 'view_communions', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_communions')]);
        Permission::firstOrCreate(['name' => 'create_communions', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('create_communions')]);
        Permission::firstOrCreate(['name' => 'edit_communions', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('edit_communions')]);
        Permission::firstOrCreate(['name' => 'delete_communions', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('delete_communions')]);

        // Sacrements - Mariages
        Permission::firstOrCreate(['name' => 'view_marriages', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_marriages')]);
        Permission::firstOrCreate(['name' => 'create_marriages', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('create_marriages')]);
        Permission::firstOrCreate(['name' => 'edit_marriages', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('edit_marriages')]);
        Permission::firstOrCreate(['name' => 'delete_marriages', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('delete_marriages')]);

        // Sacrements - Obsèques
        Permission::firstOrCreate(['name' => 'view_funerals', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_funerals')]);
        Permission::firstOrCreate(['name' => 'create_funerals', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('create_funerals')]);
        Permission::firstOrCreate(['name' => 'edit_funerals', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('edit_funerals')]);
        Permission::firstOrCreate(['name' => 'delete_funerals', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('delete_funerals')]);

        // Événements
        Permission::firstOrCreate(['name' => 'view_events', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_events')]);
        Permission::firstOrCreate(['name' => 'create_events', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('create_events')]);
        Permission::firstOrCreate(['name' => 'edit_events', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('edit_events')]);
        Permission::firstOrCreate(['name' => 'delete_events', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('delete_events')]);
        Permission::firstOrCreate(['name' => 'manage_event_participants', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('manage_event_participants')]);

        // Groupes
        Permission::firstOrCreate(['name' => 'view_groups', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_groups')]);
        Permission::firstOrCreate(['name' => 'create_groups', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('create_groups')]);
        Permission::firstOrCreate(['name' => 'edit_groups', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('edit_groups')]);
        Permission::firstOrCreate(['name' => 'delete_groups', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('delete_groups')]);
        Permission::firstOrCreate(['name' => 'manage_group_members', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('manage_group_members')]);

        // Finances - Recettes
        Permission::firstOrCreate(['name' => 'view_revenues', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_revenues')]);
        Permission::firstOrCreate(['name' => 'create_revenues', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('create_revenues')]);
        Permission::firstOrCreate(['name' => 'edit_revenues', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('edit_revenues')]);
        Permission::firstOrCreate(['name' => 'delete_revenues', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('delete_revenues')]);
        Permission::firstOrCreate(['name' => 'validate_revenues', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('validate_revenues')]);

        // Finances - Dépenses
        Permission::firstOrCreate(['name' => 'view_expenses', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_expenses')]);
        Permission::firstOrCreate(['name' => 'create_expenses', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('create_expenses')]);
        Permission::firstOrCreate(['name' => 'edit_expenses', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('edit_expenses')]);
        Permission::firstOrCreate(['name' => 'delete_expenses', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('delete_expenses')]);
        Permission::firstOrCreate(['name' => 'validate_expenses', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('validate_expenses')]);

        // Finances - Rapports
        Permission::firstOrCreate(['name' => 'view_financial_reports', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_financial_reports')]);
        Permission::firstOrCreate(['name' => 'generate_financial_reports', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('generate_financial_reports')]);

        // Configuration
        Permission::firstOrCreate(['name' => 'view_configuration', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('view_configuration')]);
        Permission::firstOrCreate(['name' => 'edit_configuration', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('edit_configuration')]);

        // Administration
        Permission::firstOrCreate(['name' => 'manage_users', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('manage_users')]);
        Permission::firstOrCreate(['name' => 'manage_roles', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('manage_roles')]);
        Permission::firstOrCreate(['name' => 'manage_permissions', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('manage_permissions')]);
        Permission::firstOrCreate(['name' => 'manage_paroisses', 'guard_name' => 'web'], ['libelle_permission' => $makeLabel('manage_paroisses')]);

        // ============================================
        // CRÉATION DES RÔLES
        // ============================================

        // Super Admin - Toutes les permissions
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web'],
            ['libelle_role' => 'Super administrateur']
        );
        $superAdmin->syncPermissions(Permission::all());

        // Paroisse Admin - Presque toutes les permissions sauf gestion globale
        $paroisseAdmin = Role::firstOrCreate(
            ['name' => 'paroisse_admin', 'guard_name' => 'web'],
            ['libelle_role' => 'Administrateur de paroisse']
        );
        $paroisseAdmin->syncPermissions([
            'view_dashboard',
            'view_members', 'create_members', 'edit_members', 'delete_members', 'export_members', 'import_members',
            'view_baptisms', 'create_baptisms', 'edit_baptisms', 'delete_baptisms',
            'view_confirmations', 'create_confirmations', 'edit_confirmations', 'delete_confirmations',
            'view_communions', 'create_communions', 'edit_communions', 'delete_communions',
            'view_marriages', 'create_marriages', 'edit_marriages', 'delete_marriages',
            'view_funerals', 'create_funerals', 'edit_funerals', 'delete_funerals',
            'view_events', 'create_events', 'edit_events', 'delete_events', 'manage_event_participants',
            'view_groups', 'create_groups', 'edit_groups', 'delete_groups', 'manage_group_members',
            'view_revenues', 'create_revenues', 'edit_revenues', 'delete_revenues', 'validate_revenues',
            'view_expenses', 'create_expenses', 'edit_expenses', 'delete_expenses', 'validate_expenses',
            'view_financial_reports', 'generate_financial_reports',
            'view_configuration', 'edit_configuration',
            'manage_users', // Peut gérer les utilisateurs de sa paroisse
        ]);

        // Paroisse Secrétaire - Création et modification, pas de suppression ni validation financière
        $secretaire = Role::firstOrCreate(
            ['name' => 'paroisse_secretaire', 'guard_name' => 'web'],
            ['libelle_role' => 'Secrétaire de paroisse']
        );
        $secretaire->syncPermissions([
            'view_dashboard',
            'view_members', 'create_members', 'edit_members', 'export_members',
            'view_baptisms', 'create_baptisms', 'edit_baptisms',
            'view_confirmations', 'create_confirmations', 'edit_confirmations',
            'view_communions', 'create_communions', 'edit_communions',
            'view_marriages', 'create_marriages', 'edit_marriages',
            'view_funerals', 'create_funerals', 'edit_funerals',
            'view_events', 'create_events', 'edit_events', 'manage_event_participants',
            'view_groups', 'create_groups', 'edit_groups', 'manage_group_members',
            'view_revenues', 'create_revenues', 'edit_revenues',
            'view_expenses', 'create_expenses', 'edit_expenses',
            'view_financial_reports',
            'view_configuration',
        ]);

        // Paroisse Trésorier - Toutes les permissions financières
        $tresorier = Role::firstOrCreate(
            ['name' => 'paroisse_tresorier', 'guard_name' => 'web'],
            ['libelle_role' => 'Trésorier de paroisse']
        );
        $tresorier->syncPermissions([
            'view_dashboard',
            'view_members', 'export_members',
            'view_events',
            'view_revenues', 'create_revenues', 'edit_revenues', 'delete_revenues', 'validate_revenues',
            'view_expenses', 'create_expenses', 'edit_expenses', 'delete_expenses', 'validate_expenses',
            'view_financial_reports', 'generate_financial_reports',
            'view_configuration',
        ]);

        // Paroisse Lecteur - Consultation seule
        $lecteur = Role::firstOrCreate(
            ['name' => 'paroisse_lecteur', 'guard_name' => 'web'],
            ['libelle_role' => 'Lecteur (consultation)']
        );
        $lecteur->syncPermissions([
            'view_dashboard',
            'view_members',
            'view_baptisms',
            'view_confirmations',
            'view_communions',
            'view_marriages',
            'view_funerals',
            'view_events',
            'view_groups',
            'view_revenues',
            'view_expenses',
            'view_financial_reports',
            'view_configuration',
        ]);

        $this->command->info('Rôles et permissions créés avec succès !');
    }
}
