@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-6">
    <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-5 flex gap-4 items-center transition hover:shadow-md">
        <span class="shrink-0 w-14 h-14 rounded-xl flex items-center justify-center text-2xl" style="background: var(--rgba-primary-1); color: var(--primary);">
            <i class="fas fa-user" aria-hidden="true"></i>
        </span>
        <div class="min-w-0">
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-0.5">Membres</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ number_format($statsMembers['total'], 0, ',', ' ') }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total inscrits</p>
        </div>
    </div>
    <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-5 flex gap-4 items-center transition hover:shadow-md">
        <span class="shrink-0 w-14 h-14 rounded-xl flex items-center justify-center text-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
            <i class="fas fa-calendar" aria-hidden="true"></i>
        </span>
        <div class="min-w-0">
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-0.5">Événements</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ number_format($statsEvents['a_venir'], 0, ',', ' ') }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">À venir</p>
        </div>
    </div>
    <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-5 flex gap-4 items-center transition hover:shadow-md">
        <span class="shrink-0 w-14 h-14 rounded-xl flex items-center justify-center text-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
            <i class="fas fa-money-bill-alt" aria-hidden="true"></i>
        </span>
        <div class="min-w-0">
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-0.5">Recettes</p>
            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ \App\Helpers\ParoisseConfig::formatMontant($statsFinances['recettes_mois'] ?? 0) }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ce mois</p>
        </div>
    </div>
    <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-5 flex gap-4 items-center transition hover:shadow-md">
        <span class="shrink-0 w-14 h-14 rounded-xl flex items-center justify-center text-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400">
            <i class="fas fa-users" aria-hidden="true"></i>
        </span>
        <div class="min-w-0">
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-0.5">Groupes</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ number_format($statsGroups['total'], 0, ',', ' ') }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Actifs</p>
        </div>
    </div>
</div>

<div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0">Résumé financier</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="text-center p-4 rounded-xl bg-emerald-500/5 dark:bg-emerald-500/10 border border-emerald-200/50 dark:border-emerald-800/40">
                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400 tabular-nums mb-1">{{ number_format($statsFinances['recettes_mois'], 0, ',', ' ') }}</p>
                <p class="text-xs text-slate-600 dark:text-slate-400 m-0">Recettes du mois</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-rose-500/5 dark:bg-rose-500/10 border border-rose-200/50 dark:border-rose-800/40">
                <p class="text-lg font-bold text-rose-600 dark:text-rose-400 tabular-nums mb-1">{{ number_format($statsFinances['depenses_mois'], 0, ',', ' ') }}</p>
                <p class="text-xs text-slate-600 dark:text-slate-400 m-0">Dépenses du mois</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-sky-500/5 dark:bg-sky-500/10 border border-sky-200/50 dark:border-sky-800/40">
                <p class="text-lg font-bold tabular-nums mb-1 {{ $statsFinances['solde_mois'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                    {{ number_format($statsFinances['solde_mois'], 0, ',', ' ') }}
                </p>
                <p class="text-xs text-slate-600 dark:text-slate-400 m-0">Solde du mois</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-slate-500/5 dark:bg-slate-500/10 border border-slate-200 dark:border-slate-600">
                <p class="text-lg font-bold tabular-nums mb-1 {{ $statsFinances['solde_annee'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                    {{ number_format($statsFinances['solde_annee'], 0, ',', ' ') }}
                </p>
                <p class="text-xs text-slate-600 dark:text-slate-400 m-0">Solde de l'année</p>
            </div>
        </div>
    </div>
</div>

<div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0">Actions rapides</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            @can('manage_users')
            <a href="{{ route('users.index') }}" class="adventiste-btn-primary min-h-[3.75rem] justify-center text-center no-underline">
                <i class="fas fa-user text-xl" aria-hidden="true"></i>
                <span>Gérer les utilisateurs</span>
            </a>
            @endcan
            @can('manage_paroisses')
            <a href="{{ route('paroisses.index') }}" class="inline-flex items-center justify-center gap-2 min-h-[3.75rem] rounded-xl border border-sky-300 dark:border-sky-700 bg-sky-50/90 dark:bg-sky-950/40 px-4 py-3 text-sm font-semibold text-sky-800 dark:text-sky-200 shadow-sm hover:bg-sky-100 dark:hover:bg-sky-900/50 transition-colors no-underline">
                <i class="fas fa-home text-xl" aria-hidden="true"></i>
                <span>Gérer les paroisses</span>
            </a>
            @endcan
            <a href="{{ route('configurations.index') }}" class="inline-flex items-center justify-center gap-2 min-h-[3.75rem] rounded-xl border border-amber-300 dark:border-amber-700 bg-amber-50/90 dark:bg-amber-950/40 px-4 py-3 text-sm font-semibold text-amber-900 dark:text-amber-200 shadow-sm hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-colors no-underline">
                <i class="fas fa-cog text-xl" aria-hidden="true"></i>
                <span>Configuration</span>
            </a>
            @can('view_financial_reports')
            <a href="{{ route('financial-reports.index') }}" class="inline-flex items-center justify-center gap-2 min-h-[3.75rem] rounded-xl border border-emerald-300 dark:border-emerald-700 bg-emerald-50/90 dark:bg-emerald-950/40 px-4 py-3 text-sm font-semibold text-emerald-800 dark:text-emerald-200 shadow-sm hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors no-underline">
                <i class="fas fa-file text-xl" aria-hidden="true"></i>
                <span>Rapports</span>
            </a>
            @endcan
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mb-6">
    <div class="xl:col-span-8 rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0">Évolution financière (6 derniers mois)</h2>
        </div>
        <div class="p-6">
            <div class="relative h-96 w-full min-h-[300px]">
                <canvas id="evolutionFinanciereChart"></canvas>
            </div>
        </div>
    </div>
    <div class="xl:col-span-4 rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0">Répartition des recettes</h2>
        </div>
        <div class="p-6">
            <div class="relative h-72 w-full min-h-[260px]">
                <canvas id="repartitionRecettesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0">Statistiques des membres</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center gap-3">
                    <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-emerald-500/10 text-emerald-600">
                        <i class="fas fa-check" aria-hidden="true"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900 dark:text-white m-0 tabular-nums">{{ number_format($statsMembers['actifs'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Actifs</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-slate-500/10 text-slate-600 dark:text-slate-300">
                        <i class="fas fa-pause" aria-hidden="true"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900 dark:text-white m-0 tabular-nums">{{ number_format($statsMembers['inactifs'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Inactifs</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-rose-500/10 text-rose-600">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900 dark:text-white m-0 tabular-nums">{{ number_format($statsMembers['decedes'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Décédés</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-sky-500/10 text-sky-600">
                        <i class="fas fa-user" aria-hidden="true"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900 dark:text-white m-0 tabular-nums">{{ number_format($statsMembers['par_sexe']['homme'] + $statsMembers['par_sexe']['femme'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Hommes / Femmes</p>
                    </div>
                </div>
            </div>
            <div class="relative h-72 w-full min-h-[240px] mt-6">
                <canvas id="membresParSexeChart"></canvas>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0">Statistiques des événements</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center gap-3">
                    <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-emerald-500/10 text-emerald-600">
                        <i class="fas fa-calendar" aria-hidden="true"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900 dark:text-white m-0 tabular-nums">{{ number_format($statsEvents['total'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Total</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-amber-500/10 text-amber-600">
                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900 dark:text-white m-0 tabular-nums">{{ number_format($statsEvents['ce_mois'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Ce mois</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-cyan-500/10 text-cyan-600">
                        <i class="fas fa-calendar-check" aria-hidden="true"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900 dark:text-white m-0 tabular-nums">{{ number_format($statsEvents['passes'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Passés</p>
                    </div>
                </div>
            </div>
            <div class="relative h-72 w-full min-h-[240px] mt-6">
                <canvas id="evenementsParTypeChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('tpl/vendor/chart.js/Chart.bundle.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const evolutionData = @json($statsFinances['evolution_6_mois']);
    const repartitionRecettes = @json($statsFinances['repartition_recettes']);
    const repartitionDepenses = @json($statsFinances['repartition_depenses']);
    const membresParSexe = @json($statsMembers['par_sexe']);
    const evenementsParType = @json($statsEvents['par_type']);

    const ctxEvolution = document.getElementById('evolutionFinanciereChart');
    if (ctxEvolution) {
        new Chart(ctxEvolution, {
            type: 'line',
            data: {
                labels: evolutionData.map(d => d.mois),
                datasets: [
                    {
                        label: 'Recettes',
                        data: evolutionData.map(d => d.recettes),
                        borderColor: 'rgb(40, 167, 69)',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Dépenses',
                        data: evolutionData.map(d => d.depenses),
                        borderColor: 'rgb(220, 53, 69)',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Solde',
                        data: evolutionData.map(d => d.solde),
                        borderColor: 'rgb(0, 123, 255)',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        fill: false,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' +
                                    new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' {{ \App\Helpers\ParoisseConfig::get(null, "monnaie", "FCFA") }}';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value);
                            }
                        }
                    }
                }
            }
        });
    }

    const ctxRecettes = document.getElementById('repartitionRecettesChart');
    if (ctxRecettes && repartitionRecettes.length > 0) {
        new Chart(ctxRecettes, {
            type: 'doughnut',
            data: {
                labels: repartitionRecettes.map(r => r.categorie),
                datasets: [{
                    data: repartitionRecettes.map(r => r.total),
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(0, 123, 255, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(108, 117, 125, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' +
                                    new Intl.NumberFormat('fr-FR').format(context.parsed) + ' {{ \App\Helpers\ParoisseConfig::get(null, "monnaie", "FCFA") }} (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    const ctxMembresSexe = document.getElementById('membresParSexeChart');
    if (ctxMembresSexe) {
        new Chart(ctxMembresSexe, {
            type: 'pie',
            data: {
                labels: ['Hommes', 'Femmes', 'Non renseigné'],
                datasets: [{
                    data: [
                        membresParSexe.homme || 0,
                        membresParSexe.femme || 0,
                        membresParSexe.non_renseigne || 0
                    ],
                    backgroundColor: [
                        'rgba(0, 123, 255, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(108, 117, 125, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    const ctxEvenements = document.getElementById('evenementsParTypeChart');
    if (ctxEvenements) {
        new Chart(ctxEvenements, {
            type: 'bar',
            data: {
                labels: ['Messe', 'Célébration', 'Activité'],
                datasets: [{
                    label: 'Nombre d\'événements',
                    data: [
                        evenementsParType.messe || 0,
                        evenementsParType['célébration'] || 0,
                        evenementsParType.activité || 0
                    ],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(0, 123, 255, 0.8)',
                        'rgba(255, 193, 7, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
});
</script>
@endpush
