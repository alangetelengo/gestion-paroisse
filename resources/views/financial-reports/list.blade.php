@extends('layouts.app')

@section('title', 'Rapports financiers enregistrés')
@section('page-title', 'Rapports financiers enregistrés')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-calculator me-2"></i>
                    Liste des rapports enregistrés
                </h4>
                <div class="card-action">
                    <a href="{{ route('financial-reports.index') }}" class="btn btn-secondary me-2">
                        Générer un nouveau rapport
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('financial-reports.list') }}" class="mb-3">
                    <div class="row g-3 align-items-end">
                        @if(auth()->user()->hasRole('super_admin') && $paroisses->count() > 0)
                            <div class="col-md-4">
                                <label class="form-label">Paroisse</label>
                                <select name="paroisse_id" class="form-control">
                                    <option value="">Toutes</option>
                                    @foreach($paroisses as $paroisse)
                                        <option value="{{ $paroisse->id }}" @selected(request('paroisse_id') == $paroisse->id)>
                                            {{ $paroisse->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <label class="form-label">Année</label>
                            <select name="year" class="form-control">
                                <option value="">Toutes</option>
                                @for($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" @selected(request('year') == $y)>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                            <a href="{{ route('financial-reports.list') }}" class="btn btn-secondary">Réinitialiser</a>
                        </div>
                    </div>
                </form>

                @if($reports->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Période</th>
                                    @if(auth()->user()->hasRole('super_admin'))
                                        <th>Paroisse</th>
                                    @endif
                                    <th class="text-end">Total Recettes</th>
                                    <th class="text-end">Total Dépenses</th>
                                    <th class="text-end">Solde</th>
                                    <th>Créé le</th>
                                    <th>Créé par</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $report)
                                    <tr>
                                        <td>
                                            {{ $report->date_debut->format('F Y') }}
                                            <br>
                                            <small class="text-muted">
                                                {{ $report->date_debut->format('d/m/Y') }} - {{ $report->date_fin->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        @if(auth()->user()->hasRole('super_admin'))
                                            <td>{{ $report->paroisse->nom ?? '—' }}</td>
                                        @endif
                                        <td class="text-end">
                                            <span class="text-success fw-bold">
                                                {{ number_format($report->total_recettes, 0, ',', ' ') }} FCFA
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-danger fw-bold">
                                                {{ number_format($report->total_depenses, 0, ',', ' ') }} FCFA
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold {{ $report->solde >= 0 ? 'text-info' : 'text-warning' }}">
                                                {{ number_format($report->solde, 0, ',', ' ') }} FCFA
                                            </span>
                                        </td>
                                        <td>
                                            {{ $report->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            {{ $report->createdBy->name ?? '—' }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('financial-reports.show', $report) }}" class="btn btn-sm btn-primary">
                                                Voir / Imprimer
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $reports->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="flaticon-381-calculator" style="font-size:64px;color:#ccc;margin-bottom:20px;"></i>
                        <h5 class="text-muted">Aucun rapport enregistré</h5>
                        <p class="text-muted">Générez un rapport mensuel pour commencer.</p>
                        <a href="{{ route('financial-reports.index') }}" class="btn btn-primary">Générer un rapport</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
