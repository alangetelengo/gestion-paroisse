@extends('layouts.app')

@section('title', 'Permissions')
@section('page-title', 'Gestion des permissions')

@push('styles')
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Liste des permissions</h4>
                <div class="card-action">
                    <a href="{{ route('permissions.create') }}" class="btn btn-citron btn-sm">
                        Ajouter une permission
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($permissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="example5">
                            <thead>
                            <tr>
                                <th>Libellé</th>
                                <th>Nom technique</th>
                                <th>Guard</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->libelle_permission ?? ucfirst(str_replace('_', ' ', $permission->name)) }}</td>
                                    <td><code>{{ $permission->name }}</code></td>
                                    <td>{{ $permission->guard_name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-warning btn-sm me-1">
                                            Modifier
                                        </a>
                                        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Supprimer cette permission ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Aucune permission définie.</p>
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
            if (window.jQuery && !jQuery.fn.dataTable.isDataTable('#example5')) {
                jQuery('#example5').DataTable({
                    pageLength: 10,
                    lengthChange: false,
                    searching: true,
                    ordering: true,
                    language: {
                        processing:     'Traitement en cours...',
                        search:         'Rechercher :',
                        lengthMenu:     'Afficher _MENU_ éléments',
                        info:           'Affichage de l\'élément _START_ à _END_ sur _TOTAL_ éléments',
                        infoEmpty:      'Affichage de l\'élément 0 à 0 sur 0 élément',
                        infoFiltered:   '(filtré de _MAX_ éléments au total)',
                        loadingRecords: 'Chargement en cours...',
                        zeroRecords:    'Aucun élément à afficher',
                        emptyTable:     'Aucune donnée disponible dans le tableau',
                        paginate: {
                            first:      'Premier',
                            previous:   'Précédent',
                            next:       'Suivant',
                            last:       'Dernier'
                        },
                        aria: {
                            sortAscending:  ': activer pour trier la colonne par ordre croissant',
                            sortDescending: ': activer pour trier la colonne par ordre décroissant'
                        }
                    }
                });
            }
        });
    </script>
@endpush

