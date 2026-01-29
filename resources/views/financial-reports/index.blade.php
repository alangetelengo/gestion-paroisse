@extends('layouts.app')

@section('title', 'Rapports financiers')
@section('page-title', 'Rapports financiers')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-calculator me-2"></i>
                    Rapport mensuel de justification
                </h4>
                <div class="card-action">
                    <a href="{{ route('financial-reports.list') }}" class="btn btn-secondary">
                        Voir les rapports enregistrés
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('financial-reports.index') }}" class="mb-4">
                    <div class="row g-3 align-items-end">
                        @if(auth()->user()->hasRole('super_admin') && $paroisses->count() > 0)
                            <div class="col-md-4">
                                <label class="form-label">Paroisse <span class="text-danger">*</span></label>
                                <select name="paroisse_id" class="form-control" required>
                                    <option value="">Sélectionner...</option>
                                    @foreach($paroisses as $paroisse)
                                        <option value="{{ $paroisse->id }}" @selected($selectedParoisseId == $paroisse->id)>
                                            {{ $paroisse->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="paroisse_id" value="{{ auth()->user()->paroisse_id }}">
                        @endif

                        <div class="col-md-4">
                            <label class="form-label">Mois <span class="text-danger">*</span></label>
                            <select name="month" class="form-control" required>
                                @foreach([
                                    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                                    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                                    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre',
                                ] as $num => $nom)
                                    <option value="{{ $num }}" @selected($selectedMonth == $num)>{{ $nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Année <span class="text-danger">*</span></label>
                            <select name="year" class="form-control" required>
                                @for($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" @selected($selectedYear == $y)>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Calculer le rapport</button>
                        </div>
                    </div>
                </form>

                @if($report)
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>Total Recettes</h5>
                                    <h3>{{ number_format($report['total_recettes'], 0, ',', ' ') }} FCFA</h3>
                                    <small>Popote / Subvention</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5>Total Dépenses</h5>
                                    <h3>{{ number_format($report['total_depenses'], 0, ',', ' ') }} FCFA</h3>
                                    <small>Toutes catégories</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card {{ $report['solde'] >= 0 ? 'bg-info' : 'bg-warning' }} text-white">
                                <div class="card-body text-center">
                                    <h5>Solde</h5>
                                    <h3>{{ number_format($report['solde'], 0, ',', ' ') }} FCFA</h3>
                                    <small>{{ $report['solde'] >= 0 ? 'Excédent' : 'Déficit' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Détails des Recettes (Popote/Subvention)</h5>
                                </div>
                                <div class="card-body">
                                    @if(count($report['details_recettes']) > 0)
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th class="text-end">Montant</th>
                                                    <th class="text-center">Nb</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($report['details_recettes'] as $detail)
                                                    <tr>
                                                        <td>{{ $detail['nom'] }}</td>
                                                        <td class="text-end">{{ number_format($detail['montant'], 0, ',', ' ') }} FCFA</td>
                                                        <td class="text-center">{{ $detail['count'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted">Aucune recette popote/subvention pour cette période.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Détails des Dépenses par Catégorie</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Catégorie</th>
                                                <th class="text-end">Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Charges fixes</td>
                                                <td class="text-end">{{ number_format($report['details_depenses']['charge_fixe'], 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                            <tr>
                                                <td>Charges variables</td>
                                                <td class="text-end">{{ number_format($report['details_depenses']['charge_variable'], 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                            <tr>
                                                <td>Charges exceptionnelles</td>
                                                <td class="text-end">{{ number_format($report['details_depenses']['charge_exceptionnelle'], 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Liste détaillée des Dépenses</h5>
                                </div>
                                <div class="card-body">
                                    @if($report['expenses']->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Catégorie</th>
                                                        <th>Type</th>
                                                        <th>Fournisseur</th>
                                                        <th class="text-end">Montant</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($report['expenses'] as $expense)
                                                        <tr>
                                                            <td>{{ $expense->date_depense?->format('d/m/Y') }}</td>
                                                            <td>
                                                                @php
                                                                    $cats = [
                                                                        'charge_fixe' => 'Charge fixe',
                                                                        'charge_variable' => 'Charge variable',
                                                                        'charge_exceptionnelle' => 'Charge exceptionnelle',
                                                                    ];
                                                                @endphp
                                                                {{ $cats[$expense->categorie_charge] ?? $expense->categorie_charge }}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $types = [
                                                                        'carburant' => 'Carburant',
                                                                        'hosties' => 'Hosties',
                                                                        'internet' => 'Internet',
                                                                        'maintenance_materiel' => 'Maintenance matériel',
                                                                        'gaz' => 'Gaz',
                                                                        'eau' => 'Eau',
                                                                        'electricite' => 'Électricité',
                                                                        'jardinage' => 'Jardinage',
                                                                        'salaire_ouvrier' => 'Salaire ouvrier',
                                                                        'autre' => 'Autre',
                                                                    ];
                                                                @endphp
                                                                {{ $types[$expense->type_charge] ?? $expense->type_charge }}
                                                            </td>
                                                            <td>{{ $expense->fournisseur ?? '—' }}</td>
                                                            <td class="text-end">{{ number_format($expense->montant, 0, ',', ' ') }} FCFA</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">Aucune dépense enregistrée pour cette période.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @can('generate_financial_reports')
                    <div class="mt-4 text-end">
                        <form action="{{ route('financial-reports.store') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="paroisse_id" value="{{ $selectedParoisseId }}">
                            <input type="hidden" name="month" value="{{ $selectedMonth }}">
                            <input type="hidden" name="year" value="{{ $selectedYear }}">
                            <button type="submit" class="btn btn-primary">Enregistrer ce rapport</button>
                        </form>
                    </div>
                    @endcan
                @else
                    <div class="text-center py-5">
                        <i class="flaticon-381-calculator" style="font-size:64px;color:#ccc;margin-bottom:20px;"></i>
                        <h5 class="text-muted">Sélectionnez une période pour générer le rapport</h5>
                        <p class="text-muted">Le rapport calculera automatiquement les recettes popote/subvention et les dépenses pour la période choisie.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
