@extends('layouts.app')

@section('title', 'Catégories de recettes')
@section('page-title', 'Catégories de recettes')

@push('styles')
<style>
.page-list .card { border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: none; }
.page-list .card-header { background: linear-gradient(135deg, var(--primary, #6A1B9A) 0%, #552586 100%); color: #fff; border-radius: 12px 12px 0 0; padding: 1.25rem 1.5rem; }
.page-list .card-title { font-weight: 600; font-size: 1.2rem; }
.page-list .header-select { border: 1px solid rgba(255,255,255,0.4) !important; background-color: rgba(255,255,255,0.15) !important; color: #fff !important; border-radius: 8px; padding: 8px 12px; min-width: 160px; }
.page-list .header-select:focus { border-color: #FFD700 !important; box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25); }
.page-list .header-select option { color: #212529; background: #fff; }
.page-list .table-list { font-size: 0.95rem; }
.page-list .table-list thead th { background: var(--primary, #6A1B9A); color: #fff; font-weight: 600; padding: 14px 16px; border: none; }
.page-list .table-list thead th:first-child { border-radius: 8px 0 0 0; }
.page-list .table-list thead th:last-child { border-radius: 0 8px 0 0; }
.page-list .table-list tbody tr { transition: background 0.2s; }
.page-list .table-list tbody tr:hover { background: rgba(106, 27, 154, 0.04); }
.page-list .table-list td { padding: 14px 16px; vertical-align: middle; }
.page-list .empty-state { padding: 4rem 2rem; }
.page-list .empty-state .empty-icon { font-size: 5rem; color: #dee2e6; margin-bottom: 1rem; }
.page-list .pagination { gap: 4px; }
.page-list .pagination .page-link { border-radius: 8px !important; }
</style>
@endpush

@section('content')
<div class="page-list">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h4 class="card-title mb-0 d-flex align-items-center">
                    <i class="fas fa-folder-open me-3" style="font-size: 1.4rem; opacity: 0.9;"></i>
                    Catégories de recettes
                </h4>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('revenue-categories.index') }}" class="btn btn-action btn-refresh">
                        <i class="fas fa-sync-alt"></i> Rafraîchir
                    </a>
                    @if($paroisses->count() > 0)
                    <form method="GET" action="{{ route('revenue-categories.index') }}" class="d-inline">
                        <select name="paroisse_id" class="form-control form-control-sm header-select" onchange="this.form.submit()">
                            @foreach($paroisses as $p)
                                <option value="{{ $p->id }}" @selected($paroisseId == $p->id)>{{ $p->nom }}</option>
                            @endforeach
                        </select>
                    </form>
                    @endif
                    @can('create_revenues')
                    <a href="{{ route('revenue-categories.create', $paroisseId ? ['paroisse_id' => $paroisseId] : []) }}" class="btn btn-action btn-add">
                        <i class="fas fa-plus"></i> Ajouter une catégorie
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @if($categories->count() > 0)
                {{-- Tableau --}}
                <div class="table-responsive rounded overflow-hidden">
                    <table class="table table-list table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Ordre</th>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Paroisse</th>
                                <th>Actif</th>
                                <th class="text-center" style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                            <tr>
                                <td>{{ $cat->ordre }}</td>
                                <td><code class="bg-light px-2 py-1 rounded">{{ $cat->code }}</code></td>
                                <td><strong>{{ $cat->nom }}</strong></td>
                                <td>{{ Str::limit($cat->description, 50) }}</td>
                                <td><span class="badge badge-info">{{ $cat->paroisse?->nom ?? '—' }}</span></td>
                                <td>
                                    @if($cat->actif)
                                        <span class="badge badge-success"><i class="fas fa-check me-1"></i>Oui</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times me-1"></i>Non</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('edit_revenues')
                                        <a href="{{ route('revenue-categories.edit', $cat) }}" class="btn btn-edit btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        @endcan
                                        @can('delete_revenues')
                                        <form action="{{ route('revenue-categories.destroy', $cat) }}" method="POST" class="d-inline" data-confirm="Supprimer cette catégorie ?">
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
                    {{ $categories->withQueryString()->links() }}
                </div>
                @else
                <div class="empty-state text-center">
                    <i class="fas fa-folder-open empty-icon d-block"></i>
                    <h5 class="text-muted mb-2">Aucune catégorie trouvée</h5>
                    <p class="text-muted mb-4">Choisissez une paroisse ou créez une nouvelle catégorie de recette.</p>
                    @can('create_revenues')
                    <a href="{{ route('revenue-categories.create', $paroisseId ? ['paroisse_id' => $paroisseId] : []) }}" class="btn btn-add btn-action">
                        <i class="fas fa-plus"></i> Ajouter une catégorie
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
