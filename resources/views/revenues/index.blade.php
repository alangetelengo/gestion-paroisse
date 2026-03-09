@extends('layouts.app')

@section('title', 'Recettes')
@section('page-title', 'Gestion des recettes')

@push('styles')
<style>
.revenue-page .card { border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: none; }
.revenue-page .card-header { background: linear-gradient(135deg, var(--primary, #6A1B9A) 0%, #552586 100%); color: #fff; border-radius: 12px 12px 0 0; padding: 1.25rem 1.5rem; }
.revenue-page .card-title { font-weight: 600; font-size: 1.2rem; }
.revenue-page .filters-card { background: #f8f9fa; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem; }
.revenue-page .form-control, .revenue-page .form-select { border-radius: 8px; border: 1px solid #dee2e6; }
.revenue-page .search-wrap { display: flex; gap: 8px; max-width: 360px; }
.revenue-page .search-wrap .form-control { border-radius: 8px 0 0 8px; }
.revenue-page .btn-search { border-radius: 0 8px 8px 0; padding: 10px 20px; font-weight: 600; }
.revenue-page .table-revenue { font-size: 0.95rem; }
.revenue-page .table-revenue thead th { background: var(--primary, #6A1B9A); color: #fff; font-weight: 600; padding: 14px 16px; border: none; }
.revenue-page .table-revenue thead th:first-child { border-radius: 8px 0 0 0; }
.revenue-page .table-revenue thead th:last-child { border-radius: 0 8px 0 0; }
.revenue-page .table-revenue tbody tr { transition: background 0.2s; }
.revenue-page .table-revenue tbody tr:hover { background: rgba(106, 27, 154, 0.04); }
.revenue-page .table-revenue td { padding: 14px 16px; vertical-align: middle; }
.revenue-page .badge-cat { padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 500; }
.revenue-page .badge-methode { padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; background: #e9ecef; color: #495057; }
.revenue-page .montant-cell { font-weight: 700; color: var(--primary, #6A1B9A); font-size: 1rem; }
.revenue-page .empty-state { padding: 4rem 2rem; }
.revenue-page .empty-state .empty-icon { font-size: 5rem; color: #dee2e6; margin-bottom: 1rem; }
.revenue-page .pagination { gap: 4px; }
.revenue-page .pagination .page-link { border-radius: 8px !important; }
</style>
@endpush

@section('content')
<div class="revenue-page">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h4 class="card-title mb-0 d-flex align-items-center">
                    <i class="fas fa-calculator me-3" style="font-size: 1.4rem; opacity: 0.9;"></i>
                    Liste des recettes
                </h4>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('revenues.index') }}" class="btn btn-action btn-refresh">
                        <i class="fas fa-sync-alt"></i> Rafraîchir
                    </a>
                    @can('create_revenues')
                    <a href="{{ route('revenues.create') }}" class="btn btn-action btn-add">
                        <i class="fas fa-plus"></i> Ajouter une recette
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                {{-- Filtres --}}
                <div class="filters-card">
                    <form method="GET" action="{{ route('revenues.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-muted">Catégorie</label>
                                <select name="categorie" class="form-control">
                                    <option value="">Toutes</option>
                                    @foreach($categories as $categorie)
                                        <option value="{{ $categorie->code }}" @selected(request('categorie') === $categorie->code)>{{ $categorie->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-muted">Date du</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-muted">Au</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            @if(isset($paroisses) && $paroisses->count() > 0)
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-muted">Paroisse</label>
                                <select name="paroisse_id" class="form-select">
                                    <option value="">Toutes</option>
                                    @foreach($paroisses as $paroisse)
                                        <option value="{{ $paroisse->id }}" @selected((string) request('paroisse_id') === (string) $paroisse->id)>{{ $paroisse->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-filter w-100" type="submit">
                                    <i class="fas fa-filter me-1"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @if($revenues->count() > 0)
                {{-- Recherche --}}
                <div class="d-flex justify-content-end mb-4">
                    <form method="GET" action="{{ route('revenues.index') }}" class="search-wrap" role="search">
                        <input type="hidden" name="categorie" value="{{ request('categorie') }}">
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                        @if(isset($paroisses) && $paroisses->count() > 0)
                        <input type="hidden" name="paroisse_id" value="{{ request('paroisse_id') }}">
                        @endif
                        <input type="search" name="q" class="form-control" value="{{ request('q') }}" placeholder="Référence, notes...">
                        <button class="btn btn-primary btn-search" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                {{-- Tableau --}}
                <div class="table-responsive rounded overflow-hidden">
                    <table class="table table-revenue table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Catégorie</th>
                                <th>Type</th>
                                <th class="text-end">Montant</th>
                                <th>Méthode</th>
                                <th class="text-center" style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revenues as $revenue)
                            <tr>
                                <td><span class="text-nowrap">{{ $revenue->date_recette?->format('d/m/Y') ?? '—' }}</span></td>
                                <td>
                                    <span class="badge badge-cat" style="background: rgba(106, 27, 154, 0.12); color: var(--primary, #6A1B9A);">
                                        {{ $revenue->category?->nom ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $revenue->type?->nom ?? '—' }}
                                    @if($revenue->mois_location)
                                        @php
                                            $moisLabels = ['01'=>'Jan','02'=>'Fév','03'=>'Mars','04'=>'Avr','05'=>'Mai','06'=>'Juin','07'=>'Juil','08'=>'Août','09'=>'Sept','10'=>'Oct','11'=>'Nov','12'=>'Déc'];
                                            $moisNum = substr($revenue->mois_location, 5, 2);
                                            $annee = substr($revenue->mois_location, 0, 4);
                                        @endphp
                                        <br><small class="text-muted"><i class="fas fa-calendar-check me-1"></i>{{ $moisLabels[$moisNum] ?? $moisNum }} {{ $annee }}</small>
                                    @endif
                                </td>
                                <td class="text-end montant-cell">{{ \App\Helpers\ParoisseConfig::formatMontant($revenue->montant) }}</td>
                                <td><span class="badge badge-methode">{{ ucfirst(str_replace('_', ' ', $revenue->methode_paiement)) }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('edit_revenues')
                                        <a href="{{ route('revenues.edit', $revenue) }}" class="btn btn-edit btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-pen"></i> 
                                        </a>
                                        @endcan
                                        @can('delete_revenues')
                                        <form action="{{ route('revenues.destroy', $revenue) }}" method="POST" class="d-inline" data-confirm="Êtes-vous sûr de vouloir supprimer cette recette ?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete btn-danger btn-sm" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
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
                <div class="mt-4 d-flex justify-content-center">
                    {{ $revenues->withQueryString()->links() }}
                </div>
                @else
                <div class="empty-state text-center">
                    <i class="fas fa-calculator empty-icon d-block"></i>
                    <h5 class="text-muted mb-2">Aucune recette trouvée</h5>
                    <p class="text-muted mb-4">Commencez par ajouter votre première recette pour enregistrer les quêtes et autres revenus.</p>
                    @can('create_revenues')
                    <a href="{{ route('revenues.create') }}" class="btn btn-add btn-action">
                        <i class="fas fa-plus"></i> Ajouter une recette
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection
