@extends('layouts.app')

@section('title', 'Dépenses')
@section('page-title', 'Gestion des dépenses')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-calculator me-2"></i>
                    Liste des dépenses
                </h4>
                <div class="card-action">
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary me-2">
                        Rafraîchir
                    </a>
                    @can('create_expenses')
                    <a href="{{ route('expenses.create') }}" class="btn btn-citron" style="font-weight:600;padding:10px 24px;">
                        Ajouter une dépense
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('expenses.index') }}" class="mb-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Catégorie</label>
                            @php
                                $categorie = request('categorie_charge');
                                $categories = [
                                    'charge_fixe' => 'Charge fixe',
                                    'charge_variable' => 'Charge variable',
                                    'charge_exceptionnelle' => 'Charge exceptionnelle',
                                ];
                            @endphp
                            <select name="categorie_charge" class="form-control">
                                <option value="">Toutes</option>
                                @foreach($categories as $value => $label)
                                    <option value="{{ $value }}" @selected($categorie === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date du</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Au</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        @if(isset($paroisses) && $paroisses->count() > 0)
                            <div class="col-md-3">
                                <label class="form-label">Paroisse</label>
                                <select name="paroisse_id" class="form-control">
                                    <option value="">Toutes</option>
                                    @foreach($paroisses as $paroisse)
                                        <option value="{{ $paroisse->id }}" @selected((string) request('paroisse_id') === (string) $paroisse->id)>
                                            {{ $paroisse->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-3 mt-3">
                            <label class="form-label">Recherche</label>
                            <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Fournisseur, facture, notes...">
                        </div>
                        <div class="col-md-3 mt-3 ms-auto">
                            <button class="btn btn-primary w-100" type="submit" style="margin-top:24px;">Appliquer les filtres</button>
                        </div>
                    </div>
                </form>

                @if($expenses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Catégorie</th>
                                <th>Type</th>
                                <th>Paroisse</th>
                                <th class="text-end">Montant</th>
                                <th>Fournisseur</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->date_depense?->format('d/m/Y') ?? '—' }}</td>
                                    <td>{{ $categories[$expense->categorie_charge] ?? $expense->categorie_charge }}</td>
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
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $expense->paroisse?->nom ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($expense->montant, 2, ',', ' ') }}</strong>
                                    </td>
                                    <td>{{ $expense->fournisseur ?? '—' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            @can('edit_expenses')
                                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning btn-sm me-1" title="Modifier">
                                                Modifier
                                            </a>
                                            @endcan
                                            @can('delete_expenses')
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?')"
                                                        title="Supprimer">
                                                    <i class="flaticon-381-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $expenses->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="flaticon-381-calculator" style="font-size:64px;color:#ccc;margin-bottom:20px;"></i>
                    <h5 class="text-muted">Aucune dépense trouvée</h5>
                    <p class="text-muted">Commencez par ajouter votre première dépense.</p>
                    @can('create_expenses')
                    <a href="{{ route('expenses.create') }}" class="btn btn-citron mt-3">
                        Ajouter une dépense
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

