@extends('layouts.app')

@section('title', 'Types de recettes')
@section('page-title', 'Types de recettes')

@push('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
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
.page-list .badge-cat { padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 500; background: rgba(106, 27, 154, 0.12); color: var(--primary, #6A1B9A); }
.page-list .empty-state { padding: 4rem 2rem; }
.page-list .empty-state .empty-icon { font-size: 5rem; color: #dee2e6; margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<div class="page-list">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h4 class="card-title mb-0 d-flex align-items-center">
                    <i class="fas fa-tags me-3" style="font-size: 1.4rem; opacity: 0.9;"></i>
                    Types de recettes
                </h4>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('revenue-types.index') }}" class="btn btn-action btn-refresh">
                        <i class="fas fa-sync-alt"></i> Rafraîchir
                    </a>
                    @if($paroisses->count() > 0)
                    <form method="GET" action="{{ route('revenue-types.index') }}" class="d-inline">
                        <input type="hidden" name="revenue_category_id" value="{{ request('revenue_category_id') }}">
                        <select name="paroisse_id" class="form-control form-control-sm header-select" onchange="this.form.submit()">
                            @foreach($paroisses as $p)
                                <option value="{{ $p->id }}" @selected($paroisseId == $p->id)>{{ $p->nom }}</option>
                            @endforeach
                        </select>
                    </form>
                    @endif
                    @if($categories->count() > 0)
                    <form method="GET" action="{{ route('revenue-types.index') }}" class="d-inline">
                        @if($paroisseId)<input type="hidden" name="paroisse_id" value="{{ $paroisseId }}">@endif
                        <select name="revenue_category_id" class="form-control form-control-sm header-select" onchange="this.form.submit()" style="min-width: 180px;">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" @selected(request('revenue_category_id') == $c->id)>{{ $c->nom }}</option>
                            @endforeach
                        </select>
                    </form>
                    @endif
                    @can('create_revenues')
                    <a href="{{ route('revenue-types.create', $paroisseId ? ['paroisse_id' => $paroisseId] : []) }}" class="btn btn-action btn-add">
                        <i class="fas fa-plus"></i> Ajouter un type
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @if($types->count() > 0)
                {{-- Tableau --}}
                <div class="table-responsive rounded overflow-hidden">
                    <table class="table table-list table-hover mb-0" id="revenueTypesTable">
                        <thead>
                            <tr>
                                <th>Ordre</th>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Actif</th>
                                <th class="text-center" style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($types as $type)
                            <tr>
                                <td>{{ $type->ordre }}</td>
                                <td><code class="bg-light px-2 py-1 rounded">{{ $type->code }}</code></td>
                                <td><strong>{{ $type->nom }}</strong></td>
                                <td><span class="badge badge-cat">{{ $type->category?->nom ?? '—' }}</span></td>
                                <td>
                                    @if($type->actif)
                                        <span class="badge badge-success"><i class="fas fa-check me-1"></i>Oui</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times me-1"></i>Non</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('edit_revenues')
                                        <a href="{{ route('revenue-types.edit', $type) }}" class="btn btn-edit btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-pen"></i> Modifier
                                        </a>
                                        @endcan
                                        @can('delete_revenues')
                                        <form action="{{ route('revenue-types.destroy', $type) }}" method="POST" class="d-inline" data-confirm="Supprimer ce type ?">
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
                @else
                <div class="empty-state text-center">
                    <i class="fas fa-tags empty-icon d-block"></i>
                    <h5 class="text-muted mb-2">Aucun type trouvé</h5>
                    <p class="text-muted mb-4">Choisissez une paroisse ou créez un nouveau type de recette.</p>
                    @can('create_revenues')
                    <a href="{{ route('revenue-types.create', $paroisseId ? ['paroisse_id' => $paroisseId] : []) }}" class="btn btn-add btn-action">
                        <i class="fas fa-plus"></i> Ajouter un type
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

@push('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var table = document.getElementById('revenueTypesTable');
        if (table && window.jQuery && !jQuery.fn.dataTable.isDataTable('#revenueTypesTable')) {
            jQuery('#revenueTypesTable').DataTable({
                pageLength: 10,
                lengthChange: true,
                searching: true,
                ordering: true,
                order: [[0, 'asc']],
                language: {
                    processing:     'Traitement en cours...',
                    search:         'Rechercher :',
                    lengthMenu:     'Afficher _MENU_ éléments',
                    info:           'Affichage de _START_ à _END_ sur _TOTAL_ éléments',
                    infoEmpty:      'Affichage de 0 à 0 sur 0 élément',
                    infoFiltered:   '(filtré de _MAX_ éléments au total)',
                    loadingRecords: 'Chargement...',
                    zeroRecords:    'Aucun élément à afficher',
                    emptyTable:     'Aucune donnée disponible',
                    paginate: {
                        first:      'Premier',
                        previous:   'Précédent',
                        next:       'Suivant',
                        last:       'Dernier'
                    },
                    aria: {
                        sortAscending:  ': tri croissant',
                        sortDescending: ': tri décroissant'
                    }
                }
            });
        }
    });
</script>
@endpush
