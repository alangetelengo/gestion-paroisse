@extends('layouts.app')

@section('title', 'Permissions')
@section('page-title', 'Gestion des permissions')

@push('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style>
.page-list .card { border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: none; }
.page-list .card-header { background: linear-gradient(135deg, var(--primary, #6A1B9A) 0%, #552586 100%); color: #fff; border-radius: 12px 12px 0 0; padding: 1.25rem 1.5rem; }
.page-list .card-title { font-weight: 600; font-size: 1.2rem; }
.page-list .table-list { font-size: 0.95rem; }
.page-list .table-list thead th { background: var(--primary, #6A1B9A); color: #fff; font-weight: 600; padding: 14px 16px; border: none; }
.page-list .table-list thead th:first-child { border-radius: 8px 0 0 0; }
.page-list .table-list thead th:last-child { border-radius: 0 8px 0 0; }
.page-list .table-list tbody tr { transition: background 0.2s; }
.page-list .table-list tbody tr:hover { background: rgba(106, 27, 154, 0.04); }
.page-list .table-list td { padding: 14px 16px; vertical-align: middle; }
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
                    <i class="fas fa-key me-3" style="font-size: 1.4rem; opacity: 0.9;"></i>
                    Liste des permissions
                </h4>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('permissions.index') }}" class="btn btn-action btn-refresh">
                        <i class="fas fa-sync-alt"></i> Rafraîchir
                    </a>
                    <a href="{{ route('permissions.create') }}" class="btn btn-action btn-add">
                        <i class="fas fa-plus"></i> Ajouter une permission
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($permissions->count() > 0)
                {{-- Tableau --}}
                <div class="table-responsive rounded overflow-hidden">
                    <table class="table table-list table-hover mb-0" id="permissionsTable">
                        <thead>
                            <tr>
                                <th>Libellé</th>
                                <th>Nom technique</th>
                                <th>Guard</th>
                                <th class="text-center" style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $permission)
                            <tr>
                                <td><strong>{{ $permission->libelle_permission ?? ucfirst(str_replace('_', ' ', $permission->name)) }}</strong></td>
                                <td><code class="bg-light px-2 py-1 rounded">{{ $permission->name }}</code></td>
                                <td><span class="badge badge-info">{{ $permission->guard_name }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-edit btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-pen"></i> Modifier
                                        </a>
                                        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline" data-confirm="Supprimer cette permission ?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete btn-danger btn-sm" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state text-center">
                    <i class="fas fa-key empty-icon d-block"></i>
                    <h5 class="text-muted mb-2">Aucune permission définie</h5>
                    <p class="text-muted mb-4">Créez des permissions pour contrôler l'accès aux fonctionnalités.</p>
                    <a href="{{ route('permissions.create') }}" class="btn btn-add btn-action">
                        <i class="fas fa-plus"></i> Ajouter une permission
                    </a>
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
        if (window.jQuery && !jQuery.fn.dataTable.isDataTable('#permissionsTable')) {
            jQuery('#permissionsTable').DataTable({
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
