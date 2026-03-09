@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 28px;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .chart-container-large {
        position: relative;
        height: 400px;
    }
</style>
@endpush

@section('content')
{{-- Cartes de statistiques principales --}}
<div class="row">
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 mb-4">
        <div class="widget-stat card stat-card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 stat-icon" style="background: var(--rgba-primary-1); color: var(--primary);">
                        <i class="fas fa-user"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1">Membres</p>
                        <h4 class="mb-0">{{ number_format($statsMembers['total'], 0, ',', ' ') }}</h4>
                        <small class="text-muted">Total inscrits</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 mb-4">
        <div class="widget-stat card stat-card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 stat-icon" style="background: rgba(45, 80, 22, 0.1); color: var(--success);">
                        <i class="fas fa-calendar"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1">Événements</p>
                        <h4 class="mb-0">{{ number_format($statsEvents['a_venir'], 0, ',', ' ') }}</h4>
                        <small class="text-muted">À venir</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 mb-4">
        <div class="widget-stat card stat-card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 stat-icon" style="background: rgba(255, 140, 0, 0.1); color: var(--warning);">
                        <i class="fas fa-money-bill-alt"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1">Recettes</p>
                        <h4 class="mb-0">{{ \App\Helpers\ParoisseConfig::formatMontant($statsFinances['recettes_mois'] ?? 0) }}</h4>
                        <small class="text-muted">Ce mois</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 mb-4">
        <div class="widget-stat card stat-card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 stat-icon" style="background: rgba(220, 20, 60, 0.1); color: var(--danger);">
                        <i class="fas fa-users"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1">Groupes</p>
                        <h4 class="mb-0">{{ number_format($statsGroups['total'], 0, ',', ' ') }}</h4>
                        <small class="text-muted">Actifs</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Résumé financier --}}
<div class="row">
    <div class="col-xl-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Résumé financier</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center p-3" style="background: rgba(40, 167, 69, 0.05); border-radius: 8px;">
                            <h5 class="text-success mb-1">{{ number_format($statsFinances['recettes_mois'], 0, ',', ' ') }}</h5>
                            <small class="text-muted">Recettes du mois</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center p-3" style="background: rgba(220, 53, 69, 0.05); border-radius: 8px;">
                            <h5 class="text-danger mb-1">{{ number_format($statsFinances['depenses_mois'], 0, ',', ' ') }}</h5>
                            <small class="text-muted">Dépenses du mois</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center p-3" style="background: rgba(0, 123, 255, 0.05); border-radius: 8px;">
                            <h5 class="mb-1 {{ $statsFinances['solde_mois'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($statsFinances['solde_mois'], 0, ',', ' ') }}
                            </h5>
                            <small class="text-muted">Solde du mois</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center p-3" style="background: rgba(108, 117, 125, 0.05); border-radius: 8px;">
                            <h5 class="mb-1 {{ $statsFinances['solde_annee'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($statsFinances['solde_annee'], 0, ',', ' ') }}
                            </h5>
                            <small class="text-muted">Solde de l'année</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section d'actions rapides --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Actions rapides</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @can('manage_users')
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('users.index') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center" style="min-height: 60px;">
                            <i class="fas fa-user me-2" style="font-size: 24px;"></i>
                            <span>Gérer les utilisateurs</span>
                        </a>
                    </div>
                    @endcan

                    @can('manage_paroisses')
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('paroisses.index') }}" class="btn btn-info w-100 d-flex align-items-center justify-content-center" style="min-height: 60px;">
                            <i class="fas fa-home me-2" style="font-size: 24px;"></i>
                            <span>Gérer les paroisses</span>
                        </a>
                    </div>
                    @endcan

                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('configurations.index') }}" class="btn btn-warning w-100 d-flex align-items-center justify-content-center" style="min-height: 60px;">
                            <i class="fas fa-cog me-2" style="font-size: 24px;"></i>
                            <span>Configuration</span>
                        </a>
                    </div>

                    @can('view_financial_reports')
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('financial-reports.index') }}" class="btn btn-success btn-rounded w-100 d-flex align-items-center justify-content-center" style="min-height: 60px;">
                            <i class="fas fa-file me-2" style="font-size: 24px;"></i>
                            <span>Rapports</span>
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Graphiques et statistiques détaillées --}}
<div class="row">
    {{-- Évolution financière sur 6 mois --}}
    <div class="col-xl-8 col-lg-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Évolution financière (6 derniers mois)</h4>
            </div>
            <div class="card-body">
                <div class="chart-container-large">
                    <canvas id="evolutionFinanciereChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Répartition des recettes --}}
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Répartition des recettes</h4>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="repartitionRecettesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Statistiques membres détaillées --}}
    <div class="col-xl-6 col-lg-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Statistiques des membres</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stat-icon" style="background: rgba(40, 167, 69, 0.1); color: #28a745; width: 50px; height: 50px;">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ number_format($statsMembers['actifs'], 0, ',', ' ') }}</h5>
                                <small class="text-muted">Actifs</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stat-icon" style="background: rgba(108, 117, 125, 0.1); color: #6c757d; width: 50px; height: 50px;">
                                    <i class="fas fa-pause"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ number_format($statsMembers['inactifs'], 0, ',', ' ') }}</h5>
                                <small class="text-muted">Inactifs</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stat-icon" style="background: rgba(220, 53, 69, 0.1); color: #dc3545; width: 50px; height: 50px;">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ number_format($statsMembers['decedes'], 0, ',', ' ') }}</h5>
                                <small class="text-muted">Décédés</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stat-icon" style="background: rgba(0, 123, 255, 0.1); color: #007bff; width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ number_format($statsMembers['par_sexe']['homme'] + $statsMembers['par_sexe']['femme'], 0, ',', ' ') }}</h5>
                                <small class="text-muted">Hommes / Femmes</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chart-container mt-3">
                    <canvas id="membresParSexeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques événements --}}
    <div class="col-xl-6 col-lg-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Statistiques des événements</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stat-icon" style="background: rgba(40, 167, 69, 0.1); color: #28a745; width: 50px; height: 50px;">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ number_format($statsEvents['total'], 0, ',', ' ') }}</h5>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1); color: #ffc107; width: 50px; height: 50px;">
                                    <i class="fas fa-calendar-1"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ number_format($statsEvents['ce_mois'], 0, ',', ' ') }}</h5>
                                <small class="text-muted">Ce mois</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stat-icon" style="background: rgba(23, 162, 184, 0.1); color: #17a2b8; width: 50px; height: 50px;">
                                    <i class="fas fa-calendar-2"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ number_format($statsEvents['passes'], 0, ',', ' ') }}</h5>
                                <small class="text-muted">Passés</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chart-container mt-3">
                    <canvas id="evenementsParTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('tpl/vendor/chart.js/Chart.bundle.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour les graphiques
    const evolutionData = @json($statsFinances['evolution_6_mois']);
    const repartitionRecettes = @json($statsFinances['repartition_recettes']);
    const repartitionDepenses = @json($statsFinances['repartition_depenses']);
    const membresParSexe = @json($statsMembers['par_sexe']);
    const evenementsParType = @json($statsEvents['par_type']);

    // Graphique évolution financière (ligne)
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
                    legend: {
                        position: 'top',
                    },
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

    // Graphique répartition recettes (doughnut)
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
                    legend: {
                        position: 'bottom',
                    },
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

    // Graphique membres par sexe (pie)
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
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }

    // Graphique événements par type (bar)
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
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
