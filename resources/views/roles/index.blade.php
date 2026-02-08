@extends('layouts.app')

@section('title', 'Rôles')
@section('page-title', 'Gestion des rôles')

@push('styles')
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
                    <i class="fas fa-user-shield me-3" style="font-size: 1.4rem; opacity: 0.9;"></i>
                    Liste des rôles
                </h4>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ route('roles.index') }}" class="btn btn-action btn-refresh">
                        <i class="fas fa-sync-alt"></i> Rafraîchir
                    </a>
                    @can('manage_users')
                    <a href="{{ route('users.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-users"></i> Utilisateurs
                    </a>
                    @endcan
                    @can('manage_permissions')
                    <a href="{{ route('permissions.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-key"></i> Permissions
                    </a>
                    @endcan
                    <a href="{{ route('roles.create') }}" class="btn btn-action btn-add">
                        <i class="fas fa-plus"></i> Ajouter un rôle
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($roles->count() > 0)
                {{-- Tableau --}}
                <div class="table-responsive rounded overflow-hidden">
                    <table class="table table-list table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Libellé</th>
                                <th>Nom technique</th>
                                <th>Guard</th>
                                <th class="text-center" style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td><strong>{{ $role->libelle_role ?? ucfirst(str_replace('_', ' ', $role->name)) }}</strong></td>
                                <td><code class="bg-light px-2 py-1 rounded">{{ $role->name }}</code></td>
                                <td><span class="badge badge-info">{{ $role->guard_name }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-edit btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-pen"></i> Modifier
                                        </a>
                                        @if($role->name !== 'super_admin')
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" data-confirm="Supprimer ce rôle ?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete btn-danger btn-sm" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $roles->links() }}
                </div>
                @else
                <div class="empty-state text-center">
                    <i class="fas fa-user-shield empty-icon d-block"></i>
                    <h5 class="text-muted mb-2">Aucun rôle défini</h5>
                    <p class="text-muted mb-4">Créez des rôles pour gérer les permissions des utilisateurs.</p>
                    <a href="{{ route('roles.create') }}" class="btn btn-add btn-action">
                        <i class="fas fa-plus"></i> Ajouter un rôle
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection
