@php
    $navActive = 'flex items-center gap-3 px-5 py-3 rounded-xl text-white/85 hover:bg-[rgba(212,168,75,0.12)] hover:text-white transition-all border border-transparent hover:border-[rgba(212,168,75,0.15)]';
    $navOn = $navActive . ' nav-link-active shadow-[0_0_20px_rgba(120,80,160,0.15)]';
@endphp
<aside class="sidebar theme-sidebar fixed top-20 left-0 w-[250px] h-[calc(100vh-80px)] z-[998] flex flex-col overflow-hidden transition-all duration-300">
    <nav class="flex-1 py-5 px-4 overflow-y-auto overflow-x-hidden sidebar-nav-scroll">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? $navOn : $navActive }}">
                    <span class="text-lg flex-shrink-0" aria-hidden="true">📊</span>
                    <span class="nav-text">Tableau de bord</span>
                </a>
            </li>

            @if(auth()->user()->can('view_members') || auth()->user()->can('create_members'))
            <li>
                <details class="group" @if(request()->routeIs('members.*')) open @endif>
                    <summary class="{{ request()->routeIs('members.*') ? $navOn : $navActive }}">
                        <span class="text-lg flex-shrink-0" aria-hidden="true">👥</span>
                        <span class="nav-text">Membres</span>
                    </summary>
                    <ul class="sidebar-sub space-y-0.5 mt-1">
                        @can('view_members')
                        <li><a href="{{ route('members.index') }}" class="{{ request()->routeIs('members.index') ? 'nav-link-active rounded-lg' : '' }}">Liste des membres</a></li>
                        @endcan
                        @can('create_members')
                        <li><a href="{{ route('members.create') }}">Ajouter un membre</a></li>
                        @endcan
                    </ul>
                </details>
            </li>
            @endif

            @if(auth()->user()->can('view_baptisms') || auth()->user()->can('view_confirmations') || auth()->user()->can('view_communions') || auth()->user()->can('view_marriages') || auth()->user()->can('view_funerals'))
            <li>
                <details class="group" @if(request()->routeIs('sacraments.*')) open @endif>
                    <summary class="{{ request()->routeIs('sacraments.*') ? $navOn : $navActive }}">
                        <span class="text-lg flex-shrink-0" aria-hidden="true">💒</span>
                        <span class="nav-text">Sacrements</span>
                    </summary>
                    <ul class="sidebar-sub space-y-0.5 mt-1">
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
                </details>
            </li>
            @endif

            <li>
                <details class="group" @if(request()->routeIs('events.*')) open @endif>
                    <summary class="{{ request()->routeIs('events.*') ? $navOn : $navActive }}">
                        <span class="text-lg flex-shrink-0" aria-hidden="true">📅</span>
                        <span class="nav-text">Événements</span>
                    </summary>
                    <ul class="sidebar-sub space-y-0.5 mt-1">
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
                </details>
            </li>

            <li>
                <details class="group" @if(request()->routeIs('groups.*')) open @endif>
                    <summary class="{{ request()->routeIs('groups.*') ? $navOn : $navActive }}">
                        <span class="text-lg flex-shrink-0" aria-hidden="true">🙏</span>
                        <span class="nav-text">Groupes</span>
                    </summary>
                    <ul class="sidebar-sub space-y-0.5 mt-1">
                        @can('view_groups')
                        <li><a href="{{ route('groups.index') }}">Tous les groupes</a></li>
                        @endcan
                        @can('create_groups')
                        <li><a href="{{ route('groups.create') }}">Ajouter un groupe</a></li>
                        @endcan
                    </ul>
                </details>
            </li>

            @if(auth()->user()->can('view_revenues') || auth()->user()->can('view_expenses'))
            <li>
                <details class="group" @if(request()->routeIs('revenues.*', 'expenses.*', 'revenue-categories.*', 'revenue-types.*')) open @endif>
                    <summary class="{{ request()->routeIs('revenues.*', 'expenses.*', 'revenue-categories.*', 'revenue-types.*') ? $navOn : $navActive }}">
                        <span class="text-lg flex-shrink-0" aria-hidden="true">💰</span>
                        <span class="nav-text">Finances</span>
                    </summary>
                    <ul class="sidebar-sub space-y-1 mt-1">
                        @can('view_revenues')
                        <li>
                            <details class="sidebar-sub-nested" @if(request()->routeIs('revenues.*')) open @endif>
                                <summary class="text-white/75 text-sm py-1 cursor-pointer list-none" style="list-style: none;">Recettes</summary>
                                <ul class="sidebar-sub mt-0.5 space-y-0.5">
                                    <li><a href="{{ route('revenues.index') }}">Toutes les recettes</a></li>
                                    @can('create_revenues')
                                    <li><a href="{{ route('revenues.create') }}">Ajouter une recette</a></li>
                                    @endcan
                                </ul>
                            </details>
                        </li>
                        @endcan
                        @can('view_expenses')
                        <li>
                            <details class="sidebar-sub-nested" @if(request()->routeIs('expenses.*')) open @endif>
                                <summary class="text-white/75 text-sm py-1 cursor-pointer">Dépenses</summary>
                                <ul class="sidebar-sub mt-0.5 space-y-0.5">
                                    <li><a href="{{ route('expenses.index') }}">Toutes les dépenses</a></li>
                                    @can('create_expenses')
                                    <li><a href="{{ route('expenses.create') }}">Ajouter une dépense</a></li>
                                    @endcan
                                </ul>
                            </details>
                        </li>
                        @endcan
                        @can('view_revenues')
                        <li>
                            <details class="sidebar-sub-nested" @if(request()->routeIs('revenue-categories.*', 'revenue-types.*')) open @endif>
                                <summary class="text-white/75 text-sm py-1 cursor-pointer">Paramètres</summary>
                                <ul class="sidebar-sub mt-0.5 space-y-0.5">
                                    <li><a href="{{ route('revenue-categories.index') }}">Catégories recettes</a></li>
                                    <li><a href="{{ route('revenue-types.index') }}">Types recettes</a></li>
                                </ul>
                            </details>
                        </li>
                        @endcan
                    </ul>
                </details>
            </li>
            @endif

            @can('view_financial_reports')
            @php
                $isFinancialReportsMenu = request()->routeIs('financial-reports.*') && ! request()->routeIs('financial-reports.statistics');
            @endphp
            <li>
                <details class="group" @if($isFinancialReportsMenu) open @endif>
                    <summary class="{{ $isFinancialReportsMenu ? $navOn : $navActive }}">
                        <span class="text-lg flex-shrink-0" aria-hidden="true">📑</span>
                        <span class="nav-text">Rapports</span>
                    </summary>
                    <ul class="sidebar-sub space-y-0.5 mt-1">
                        <li><a href="{{ route('financial-reports.index') }}">Générer un rapport</a></li>
                        <li><a href="{{ route('financial-reports.list') }}">Rapports enregistrés</a></li>
                        <li><a href="{{ route('financial-reports.revenues-weekly') }}">Rapport Quête ordinaire</a></li>
                        <li><a href="{{ route('financial-reports.popote') }}">Rapport Subvention Popote</a></li>
                        <li><a href="{{ route('financial-reports.charges-fixes') }}">Rapport Charges fixes</a></li>
                    </ul>
                </details>
            </li>
            <li>
                <a href="{{ route('financial-reports.statistics') }}" class="{{ request()->routeIs('financial-reports.statistics') ? $navOn : $navActive }}">
                    <span class="text-lg flex-shrink-0" aria-hidden="true">📈</span>
                    <span class="nav-text">Statistiques</span>
                </a>
            </li>
            @endcan

            <li>
                <details class="group" @if(request()->routeIs('inventaire-magasin.*', 'inventaire-patrimoine.*')) open @endif>
                    <summary class="{{ request()->routeIs('inventaire-magasin.*', 'inventaire-patrimoine.*') ? $navOn : $navActive }}">
                        <span class="text-lg flex-shrink-0" aria-hidden="true">📦</span>
                        <span class="nav-text">Inventaire</span>
                    </summary>
                    <ul class="sidebar-sub space-y-0.5 mt-1">
                        <li><a href="{{ route('inventaire-magasin.index') }}">Produits alimentaires</a></li>
                        <li><a href="{{ route('inventaire-magasin.create') }}">Ajouter un article alimentaire</a></li>
                        <li><a href="{{ route('inventaire-patrimoine.index') }}">Patrimoine</a></li>
                        <li><a href="{{ route('inventaire-patrimoine.create') }}">Ajouter un bien patrimonial</a></li>
                    </ul>
                </details>
            </li>

            @can('manage_paroisses')
            <li>
                <details class="group" @if(request()->routeIs('paroisses.*')) open @endif>
                    <summary class="{{ request()->routeIs('paroisses.*') ? $navOn : $navActive }}">
                        <span class="text-lg flex-shrink-0" aria-hidden="true">⛪</span>
                        <span class="nav-text">Paroisses</span>
                    </summary>
                    <ul class="sidebar-sub space-y-0.5 mt-1">
                        <li><a href="{{ route('paroisses.index') }}">Liste des paroisses</a></li>
                        <li><a href="{{ route('paroisses.create') }}">Ajouter une paroisse</a></li>
                    </ul>
                </details>
            </li>
            @endcan

            @if(auth()->user()->can('manage_users') || auth()->user()->can('manage_roles') || auth()->user()->can('manage_permissions'))
            <li>
                <details class="group" @if(request()->routeIs('users.*', 'roles.*', 'permissions.*')) open @endif>
                    <summary class="{{ request()->routeIs('users.*', 'roles.*', 'permissions.*') ? $navOn : $navActive }}">
                        <span class="text-lg flex-shrink-0" aria-hidden="true">⚙️</span>
                        <span class="nav-text">Gestion utilisateur</span>
                    </summary>
                    <ul class="sidebar-sub space-y-0.5 mt-1">
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
                </details>
            </li>
            @endif

            <li>
                <details class="group" @if(request()->routeIs('configurations.*')) open @endif>
                    <summary class="{{ request()->routeIs('configurations.*') ? $navOn : $navActive }}">
                        <span class="text-lg flex-shrink-0" aria-hidden="true">🔧</span>
                        <span class="nav-text">Configuration</span>
                    </summary>
                    <ul class="sidebar-sub space-y-0.5 mt-1">
                        <li><a href="{{ route('configurations.index') }}">Paramètres généraux</a></li>
                        @can('manage_paroisses')
                        <li><a href="{{ route('paroisses.index') }}">Paroisses</a></li>
                        @endcan
                    </ul>
                </details>
            </li>
        </ul>
    </nav>

    <div class="flex-shrink-0 p-4 border-t border-[rgba(212,168,75,0.15)] space-y-2">
        <p class="px-2 text-xs text-white/50 nav-text text-center">© {{ date('Y') }} Gestion de paroisse</p>
    </div>
</aside>

<style>
.sidebar details > summary::-webkit-details-marker { display: none; }
.sidebar .sidebar-sub-nested > summary { list-style: none; }
.sidebar .sidebar-sub-nested > summary::-webkit-details-marker { display: none; }
</style>
