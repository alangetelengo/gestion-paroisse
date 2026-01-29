@extends('layouts.app')

@section('title', 'Types de recettes')
@section('page-title', 'Types de recettes')

@push('styles')
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <style>
        .revenue-type-header-select {
            border: 1px solid #6c757d !important;
            background-color: #fff !important;
            color: #212529 !important;
        }
        .revenue-type-header-select:focus {
            border-color: #6A1B9A !important;
            box-shadow: 0 0 0 0.2rem rgba(106, 27, 154, 0.25);
        }
    </style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-notepad me-2"></i>
                    Types de recettes
                </h4>
                <div class="card-action">
                    <a href="{{ route('revenue-types.index') }}" class="btn btn-secondary btn-sm me-2" title="Rafraîchir - Retour à la liste Types de recettes">
                        <i class="flaticon-381-refresh me-1"></i>Rafraîchir
                    </a>
                    @if($paroisses->count() > 0)
                        <form method="GET" action="{{ route('revenue-types.index') }}" class="d-inline me-2">
                            <input type="hidden" name="revenue_category_id" value="{{ request('revenue_category_id') }}">
                            <select name="paroisse_id" class="form-control form-control-sm d-inline-block revenue-type-header-select" onchange="this.form.submit()" style="min-width: 160px;">
                                @foreach($paroisses as $p)
                                    <option value="{{ $p->id }}" @selected($paroisseId == $p->id)>{{ $p->nom }}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                    @if($categories->count() > 0)
                        <form method="GET" action="{{ route('revenue-types.index') }}" class="d-inline me-2">
                            @if($paroisseId)<input type="hidden" name="paroisse_id" value="{{ $paroisseId }}">@endif
                            <select name="revenue_category_id" class="form-control form-control-sm d-inline-block revenue-type-header-select" onchange="this.form.submit()" style="min-width: 180px;">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" @selected(request('revenue_category_id') == $c->id)>{{ $c->nom }}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                    @can('create_revenues')
                    <a href="{{ route('revenue-types.create', $paroisseId ? ['paroisse_id' => $paroisseId] : []) }}" class="btn btn-citron">
                        <i class="flaticon-381-add-1 me-1"></i>
                        Ajouter un type
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @if($types->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="revenueTypesTable">
                        <thead>
                            <tr>
                                <th>Ordre</th>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Actif</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($types as $type)
                            <tr>
                                <td>{{ $type->ordre }}</td>
                                <td><code>{{ $type->code }}</code></td>
                                <td>{{ $type->nom }}</td>
                                <td>{{ $type->category?->nom ?? '—' }}</td>
                                <td>
                                    @if($type->actif)
                                        <span class="badge badge-success">Oui</span>
                                    @else
                                        <span class="badge badge-danger">Non</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @can('edit_revenues')
                                    <a href="{{ route('revenue-types.edit', $type) }}" class="btn btn-warning btn-sm">Modifier</a>
                                    @endcan
                                    @can('delete_revenues')
                                    <form action="{{ route('revenue-types.destroy', $type) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce type ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted mb-0">Aucun type. Choisissez une paroisse ou créez un type.</p>
                @endif
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
