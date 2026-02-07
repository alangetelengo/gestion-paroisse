@extends('layouts.app')

@section('title', 'Statistiques financières')
@section('page-title', 'Statistiques financières')

@section('content')
@php
    $currency = \App\Helpers\ParoisseConfig::get(null, 'monnaie', 'FCFA');
@endphp
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h4 class="card-title mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    Vue d'ensemble annuelle
                </h4>
                <div class="card-action d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ route('financial-reports.index') }}" class="btn btn-secondary">
                        Générer un rapport
                    </a>
                    <a href="{{ route('financial-reports.list') }}" class="btn btn-outline-secondary">
                        Rapports enregistrés
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('financial-reports.statistics') }}" class="mb-4">
                    <div class="row g-3 align-items-end">
                        @if(auth()->user()->hasRole('super_admin') && $paroisses->count() > 0)
                            <div class="col-md-6">
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
                            <label class="form-label">Année <span class="text-danger">*</span></label>
                            <select name="year" class="form-control" required>
                                @for($y = now()->year; $y >= now()->year - 10; $y--)
                                    <option value="{{ $y }}" @selected($selectedYear == $y)>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Actualiser</button>
                        </div>
                    </div>
                </form>

                @if($stats)
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>Total Recettes {{ $selectedYear }}</h5>
                                    <h3>{{ number_format($stats['total_recettes'], 0, ',', ' ') }} {{ $currency }}</h3>
                                    <small>Toutes catégories</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5>Total Dépenses {{ $selectedYear }}</h5>
                                    <h3>{{ number_format($stats['total_depenses'], 0, ',', ' ') }} {{ $currency }}</h3>
                                    <small>Toutes catégories</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card {{ $stats['solde'] >= 0 ? 'bg-info' : 'bg-warning' }} text-white">
                                <div class="card-body text-center">
                                    <h5>Solde {{ $selectedYear }}</h5>
                                    <h3>{{ number_format($stats['solde'], 0, ',', ' ') }} {{ $currency }}</h3>
                                    <small>{{ $stats['solde'] >= 0 ? 'Excédent' : 'Déficit' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5>Répartition par mois</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Mois</th>
                                            <th class="text-end">Recettes</th>
                                            <th class="text-end">Dépenses</th>
                                            <th class="text-end">Solde</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($byMonth ?? [] as $m => $row)
                                            <tr>
                                                <td>{{ $row['nom'] }}</td>
                                                <td class="text-end">{{ number_format($row['recettes'], 0, ',', ' ') }} {{ $currency }}</td>
                                                <td class="text-end">{{ number_format($row['depenses'], 0, ',', ' ') }} {{ $currency }}</td>
                                                <td class="text-end {{ $row['solde'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($row['solde'], 0, ',', ' ') }} {{ $currency }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    @if(!empty($byMonth))
                                        <tfoot class="fw-bold">
                                            <tr>
                                                <td>Total {{ $selectedYear }}</td>
                                                <td class="text-end">{{ number_format($stats['total_recettes'], 0, ',', ' ') }} {{ $currency }}</td>
                                                <td class="text-end">{{ number_format($stats['total_depenses'], 0, ',', ' ') }} {{ $currency }}</td>
                                                <td class="text-end {{ $stats['solde'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($stats['solde'], 0, ',', ' ') }} {{ $currency }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calculator" style="font-size:64px;color:#ccc;margin-bottom:20px;"></i>
                        <h5 class="text-muted">Sélectionnez une paroisse et une année</h5>
                        <p class="text-muted">Les statistiques afficheront le total des recettes, des dépenses et le solde sur l'année, avec la répartition par mois.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
