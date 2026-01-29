@extends('layouts.app')

@section('title', 'Rôles')
@section('page-title', 'Gestion des rôles')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Liste des rôles</h4>
                <div class="card-action">
                    <a href="{{ route('roles.create') }}" class="btn btn-citron btn-sm">
                        Ajouter un rôle
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($roles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Libellé</th>
                                <th>Nom technique</th>
                                <th>Guard</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->libelle_role ?? ucfirst(str_replace('_', ' ', $role->name)) }}</td>
                                    <td><code>{{ $role->name }}</code></td>
                                    <td>{{ $role->guard_name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm me-1">
                                            Modifier
                                        </a>
                                        @if($role->name !== 'super_admin')
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Supprimer ce rôle ?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $roles->links() }}
                    </div>
                @else
                    <p class="text-muted mb-0">Aucun rôle défini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

