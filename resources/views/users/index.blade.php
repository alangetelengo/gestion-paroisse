@extends('layouts.app')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

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
.page-list .avatar-circle { width: 40px; height: 40px; border-radius: 50%; background: rgba(106, 27, 154, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary, #6A1B9A); font-weight: 700; font-size: 1rem; }
.page-list .badge-role { padding: 5px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 500; margin-right: 4px; background: rgba(106, 27, 154, 0.12); color: var(--primary, #6A1B9A); }
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
                    <i class="fas fa-user-cog me-3" style="font-size: 1.4rem; opacity: 0.9;"></i>
                    Liste des utilisateurs
                </h4>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ route('users.index') }}" class="btn btn-action btn-refresh">
                        <i class="fas fa-sync-alt"></i> Rafraîchir
                    </a>
                    @can('manage_roles')
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-user-shield"></i> Rôles
                    </a>
                    @endcan
                    @can('manage_permissions')
                    <a href="{{ route('permissions.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-key"></i> Permissions
                    </a>
                    @endcan
                    @can('manage_users')
                    <a href="{{ route('users.create') }}" class="btn btn-action btn-add">
                        <i class="fas fa-plus"></i> Ajouter un utilisateur
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                {{-- Tableau --}}
                <div class="table-responsive rounded overflow-hidden">
                    <table class="table table-list table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Paroisse</th>
                                <th>Rôles</th>
                                <th class="text-center" style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-envelope me-2 text-muted"></i>
                                    {{ $user->email }}
                                </td>
                                <td><span class="badge badge-info">{{ $user->paroisse?->nom ?? 'N/A' }}</span></td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge badge-role">{{ $role->name }}</span>
                                    @endforeach
                                    @if($user->roles->isEmpty())
                                        <span class="text-muted">Aucun rôle</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-view btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('manage_users')
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-edit btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" data-confirm="Êtes-vous sûr de vouloir supprimer cet utilisateur ?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete btn-danger btn-sm" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
                @else
                <div class="empty-state text-center">
                    <i class="fas fa-user-cog empty-icon d-block"></i>
                    <h5 class="text-muted mb-2">Aucun utilisateur trouvé</h5>
                    <p class="text-muted mb-4">Commencez par ajouter votre premier utilisateur.</p>
                    @can('manage_users')
                    <a href="{{ route('users.create') }}" class="btn btn-add btn-action">
                        <i class="fas fa-plus"></i> Ajouter un utilisateur
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
