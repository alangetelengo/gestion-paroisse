@extends('layouts.app')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-user me-2"></i>
                    Liste des utilisateurs
                </h4>
                <div class="card-action">
                    @can('manage_users')
                    <a href="{{ route('users.create') }}" class="btn btn-citron" style="font-weight: 600; padding: 10px 24px;">
                        Ajouter un utilisateur
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Paroisse</th>
                                <th>Rôles</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3" style="width: 40px; height: 40px; border-radius: 50%; background: var(--rgba-primary-1); display: flex; align-items: center; justify-content: center;">
                                                <span style="color: var(--primary); font-weight: 600;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                            <strong>{{ $user->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="flaticon-381-email me-2 text-muted"></i>
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <i class="flaticon-381-home me-1"></i>
                                            {{ $user->paroisse?->nom ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-primary me-1">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                        @if($user->roles->isEmpty())
                                            <span class="text-muted">Aucun rôle</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm me-1" title="Voir">
                                                <i class="flaticon-381-view"></i>
                                            </a>
                                            @can('manage_users')
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm me-1" title="Modifier">
                                                Modifier
                                            </a>
                                            @if($user->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')"
                                                        title="Supprimer">
                                                    <i class="flaticon-381-trash"></i>
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
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="flaticon-381-user" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                    <h5 class="text-muted">Aucun utilisateur trouvé</h5>
                    <p class="text-muted">Commencez par ajouter votre premier utilisateur.</p>
                    @can('manage_users')
                    <a href="{{ route('users.create') }}" class="btn btn-citron mt-3">
                        Ajouter un utilisateur
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
