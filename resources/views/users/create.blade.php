@extends('layouts.app')

@section('title', 'Créer un utilisateur')
@section('page-title', 'Créer un utilisateur')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Nouvel utilisateur</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" data-transform="title"
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" data-transform="lower"
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Paroisse</label>
                        <select name="paroisse_id" class="form-control @error('paroisse_id') is-invalid @enderror">
                            <option value="">Sélectionner une paroisse</option>
                            @foreach($paroisses as $paroisse)
                                <option value="{{ $paroisse->id }}" {{ old('paroisse_id') == $paroisse->id ? 'selected' : '' }}>
                                    {{ $paroisse->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('paroisse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>Rôles <span class="text-danger">*</span></span>
                            <span>
                                <button type="button" class="btn btn-sm btn-primary me-1"
                                        data-check-toggle
                                        data-check-toggle-target="#user-roles">
                                    Tout cocher
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        data-uncheck-toggle
                                        data-check-toggle-target="#user-roles">
                                    Tout décocher
                                </button>
                            </span>
                        </label>
                        <div class="form-check-group" id="user-roles">
                            @foreach($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]"
                                           value="{{ $role->name }}" id="role_{{ $role->id }}"
                                           {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                        {{ $role->libelle_role ?? $role->name }}
                                        <small class="text-muted d-block"><code>{{ $role->name }}</code></small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
        <label class="form-label dècexb-flex justify-content-between align-items-center">
            <span>Permissions directes (optionnel)</span>
            <span>
                <button type="button" class="btn btn-sm btn-primary me-1"
                        data-check-toggle
                        data-check-toggle-target="#user-permissions">
                    Tout cocher
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary"
                        data-uncheck-toggle
                        data-check-toggle-target="#user-permissions">
                    Tout décocher
                </button>
            </span>
        </label>
                        <p class="text-muted small mb-2">
                            Utilisez ces permissions pour des cas particuliers. Le plus souvent, il suffit de passer par les rôles.
                        </p>
                        <div class="row" id="user-permissions">
                            @foreach($permissions as $permission)
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                               value="{{ $permission->id }}"
                                               id="perm_{{ $permission->id }}"
                                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->libelle_permission ?? ucfirst(str_replace('_', ' ', $permission->name)) }}
                                            <small class="text-muted d-block"><code>{{ $permission->name }}</code></small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('permissions')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
