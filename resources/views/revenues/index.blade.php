@extends('layouts.app')

@section('title', 'Recettes')
@section('page-title', 'Gestion des recettes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-calculator me-2"></i>
                    Liste des recettes
                </h4>
                <div class="card-action">
                    <a href="{{ route('revenues.index') }}" class="btn btn-secondary me-2">
                        Rafraîchir
                    </a>
                    @can('create_revenues')
                    <a href="{{ route('revenues.create') }}" class="btn btn-citron" style="font-weight:600;padding:10px 24px;">
                        Ajouter une recette
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                {{-- Filtre : une seule ligne alignée --}}
                <form method="GET" action="{{ route('revenues.index') }}" class="mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">Catégorie</label>
                            <select name="categorie" class="form-control">
                                <option value="">Toutes</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->code }}" @selected(request('categorie') === $categorie->code)>
                                        {{ $categorie->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date du</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Au</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        @if(isset($paroisses) && $paroisses->count() > 0)
                        <div class="col-md-2">
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
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" type="submit">Appliquer les filtres</button>
                        </div>
                    </div>
                </form>

                @if($revenues->count() > 0)
                {{-- Barre de recherche : en haut à droite du tableau --}}
                <div class="d-flex justify-content-end mb-3">
                    <form method="GET" action="{{ route('revenues.index') }}" class="d-flex gap-2" role="search">
                        <input type="hidden" name="categorie" value="{{ request('categorie') }}">
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                        @if(isset($paroisses) && $paroisses->count() > 0)
                        <input type="hidden" name="paroisse_id" value="{{ request('paroisse_id') }}">
                        @endif
                        <input type="search" name="q" class="form-control" style="max-width: 280px;" value="{{ request('q') }}" placeholder="Référence, notes...">
                        <button class="btn btn-outline-primary" type="submit">Rechercher</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Catégorie</th>
                                <th>Type</th>
                                <th class="text-end">Montant</th>
                                <th>Méthode</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revenues as $revenue)
                                <tr>
                                    <td>{{ $revenue->date_recette?->format('d/m/Y') ?? '—' }}</td>
                                    <td>{{ $revenue->category?->nom ?? '—' }}</td>
                                    <td>{{ $revenue->type?->nom ?? '—' }}</td>
                                    <td class="text-end">
                                        <strong>{{ number_format($revenue->montant, 2, ',', ' ') }}</strong>
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $revenue->methode_paiement)) }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            @can('edit_revenues')
                                            <a href="{{ route('revenues.edit', $revenue) }}" class="btn btn-warning btn-sm me-1" title="Modifier">
                                                Modifier
                                            </a>
                                            @endcan
                                            @can('delete_revenues')
                                            <form action="{{ route('revenues.destroy', $revenue) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?')"
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
                    {{ $revenues->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="flaticon-381-calculator" style="font-size:64px;color:#ccc;margin-bottom:20px;"></i>
                    <h5 class="text-muted">Aucune recette trouvée</h5>
                    <p class="text-muted">Commencez par ajouter votre première recette.</p>
                    @can('create_revenues')
                    <a href="{{ route('revenues.create') }}" class="btn btn-citron mt-3">
                        Ajouter une recette
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

