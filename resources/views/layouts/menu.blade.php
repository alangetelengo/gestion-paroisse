{{-- Menu statique basé sur l'analyse du système d'information --}}
<ul class="metismenu" id="menu">
    {{-- Dashboard --}}
    <li>
        <a class="ai-icon" href="{{ route('dashboard') }}" aria-expanded="false">
            <i class="fas fa-chart-line"></i>
            <span class="nav-text">Tableau de bord</span>
        </a>
    </li>

    {{-- Membres --}}
    <li>
        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
            <i class="flaticon-381-user"></i>
            <span class="nav-text">Membres</span>
        </a>
        <ul aria-expanded="false">
            @can('view_members')
            <li><a href="{{ route('members.index') }}">Liste des membres</a></li>
            @endcan
            @can('create_members')
            <li><a href="{{ route('members.create') }}">Ajouter un membre</a></li>
            @endcan
        </ul>
    </li>

    {{-- Sacrements --}}
    @if(auth()->user()->can('view_baptisms') || auth()->user()->can('view_confirmations') || auth()->user()->can('view_communions') || auth()->user()->can('view_marriages') || auth()->user()->can('view_funerals'))
    <li>
        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
            <i class="fas fa-heart"></i>
            <span class="nav-text">Sacrements</span>
        </a>
        <ul aria-expanded="false">
            @can('view_baptisms')
            <li><a href="{{ route('sacraments.index', ['type' => 'bapteme']) }}">Baptêmes</a></li>
            @endcan
            @can('view_confirmations')
            <li><a href="{{ route('sacraments.index', ['type' => 'confirmation']) }}">Confirmations</a></li>
            @endcan
            @can('view_communions')
            <li><a href="{{ route('sacraments.index', ['type' => 'communion']) }}">Communions</a></li>
            @endcan
            @can('view_marriages')
            <li><a href="{{ route('sacraments.index', ['type' => 'mariage']) }}">Mariages</a></li>
            @endcan
            @can('view_funerals')
            <li><a href="{{ route('sacraments.index', ['type' => 'obseques']) }}">Obsèques</a></li>
            @endcan
        </ul>
    </li>
    @endif

    {{-- Événements --}}
    <li>
        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
            <i class="fas fa-calendar-alt"></i>
            <span class="nav-text">Événements</span>
        </a>
        <ul aria-expanded="false">
            @can('view_events')
            <li><a href="{{ route('events.index') }}">Tous les événements</a></li>
            <li><a href="{{ route('events.index', ['type' => 'messe']) }}">Messes</a></li>
            <li><a href="{{ route('events.index', ['type' => 'célébration']) }}">Célébrations</a></li>
            <li><a href="{{ route('events.index', ['type' => 'activité']) }}">Activités</a></li>
            @endcan
            @can('create_events')
            <li><a href="{{ route('events.create') }}">Créer un événement</a></li>
            @endcan
        </ul>
    </li>

    {{-- Groupes --}}
    <li>
        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
            <i class="flaticon-381-user"></i>
            <span class="nav-text">Groupes</span>
        </a>
        <ul aria-expanded="false">
            @can('view_groups')
            <li><a href="{{ route('groups.index') }}">Tous les groupes</a></li>
            @endcan
            @can('create_groups')
            <li><a href="{{ route('groups.create') }}">Ajouter un groupe</a></li>
            @endcan
        </ul>
    </li>

    {{-- Finances --}}
    <li>
        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
            <i class="fas fa-calculator"></i>
            <span class="nav-text">Finances</span>
        </a>
        <ul aria-expanded="false">
            {{-- Sous-menu Recettes --}}
            @can('view_revenues')
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">Recettes</a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('revenues.index') }}">Toutes les recettes</a></li>
                    @can('create_revenues')
                    <li><a href="{{ route('revenues.create') }}">Ajouter une recette</a></li>
                    @endcan
                </ul>
            </li>
            @endcan

            {{-- Sous-menu Dépenses --}}
            @can('view_expenses')
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">Dépenses</a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('expenses.index') }}">Toutes les dépenses</a></li>
                    @can('create_expenses')
                    <li><a href="{{ route('expenses.create') }}">Ajouter une dépense</a></li>
                    @endcan
                </ul>
            </li>
            @endcan
            {{-- Sous-menu Paramètres --}}
            @can('view_revenues')
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">Paramètres</a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('revenue-categories.index') }}">Catégories recettes</a></li>
                    <li><a href="{{ route('revenue-types.index') }}">Types recettes</a></li>
                </ul>
            </li>
            @endcan
        </ul>
    </li>
       {{-- Sous-menu Rapports --}}
       @can('view_financial_reports')
       <li>
        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
            <i class="fas fa-file-alt"></i>
            <span class="nav-text">Rapports</span>
        </a>
         <ul aria-expanded="false">
               <li><a href="{{ route('financial-reports.index') }}">Générer un rapport</a></li>
               <li><a href="{{ route('financial-reports.list') }}">Rapports enregistrés</a></li>
               <li><a href="{{ route('financial-reports.revenues-weekly') }}">Rapport Quête ordinaire</a></li>
               {{-- <li><a href="{{ route('financial-reports.revenues-by-category') }}">Rapport par catégories</a></li> --}}
               <li><a href="{{ route('financial-reports.popote') }}">Rapport Subvention Popote</a></li>
               <li><a href="{{ route('financial-reports.charges-fixes') }}">Rapport Charges fixes</a></li>
           </ul>
       </li>
       <li>
           <a href="{{ route('financial-reports.statistics') }}" class="ai-icon">
               <i class="fas fa-chart-line"></i>
               <span class="nav-text">Statistiques</span>
           </a>
       </li>
       @endcan

    {{-- Inventaire --}}
    <li>
        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
            <i class="fas fa-boxes-stacked"></i>
            <span class="nav-text">Inventaire</span>
        </a>
        <ul aria-expanded="false">
            <li><a href="{{ route('inventaire-magasin.index') }}">Produits alimentaires</a></li>
            <li><a href="{{ route('inventaire-magasin.create') }}">Ajouter un article alimentaire</a></li>
            <li><a href="{{ route('inventaire-patrimoine.index') }}">Patrimoine</a></li>
            <li><a href="{{ route('inventaire-patrimoine.create') }}">Ajouter un bien patrimonial</a></li>
        </ul>
    </li>

    {{-- Paroisses --}}
    @can('manage_paroisses')
    <li>
        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
            <i class="fas fa-home"></i>
            <span class="nav-text">Paroisses</span>
        </a>
        <ul aria-expanded="false">
            <li><a href="{{ route('paroisses.index') }}">Liste des paroisses</a></li>
            <li><a href="{{ route('paroisses.create') }}">Ajouter une paroisse</a></li>
        </ul>
    </li>
    @endcan

    {{-- Gestion utilisateur (Utilisateurs, Rôles, Permissions) --}}
    @if(auth()->user()->can('manage_users') || auth()->user()->can('manage_roles') || auth()->user()->can('manage_permissions'))
    <li>
        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
            <i class="fas fa-users-cog"></i>
            <span class="nav-text">Gestion utilisateur</span>
        </a>
        <ul aria-expanded="false">
            @can('manage_users')
            <li><a href="{{ route('users.index') }}">Utilisateurs</a></li>
            @endcan
            @can('manage_roles')
            <li><a href="{{ route('roles.index') }}">Rôles</a></li>
            @endcan
            @can('manage_permissions')
            <li><a href="{{ route('permissions.index') }}">Permissions</a></li>
            @endcan
        </ul>
    </li>
    @endif

    {{-- Configuration --}}
    <li>
        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
            <i class="flaticon-381-settings-2"></i>
            <span class="nav-text">Configuration</span>
        </a>
        <ul aria-expanded="false">
            <li><a href="{{ route('configurations.index') }}">Paramètres généraux</a></li>
            @can('manage_paroisses')
            <li><a href="{{ route('paroisses.index') }}">Paroisses</a></li>
            @endcan
        </ul>
    </li>
</ul>
