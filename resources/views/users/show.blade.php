@extends('layouts.app')

@section('title', 'Détails utilisateur')
@section('page-title', 'Détails de l\'utilisateur')

@section('content')
@php
    $roles = $user->roles;
    $permissions = $user->getAllPermissions();

    $permissionGroups = $permissions
        ->pluck('name')
        ->map(fn (string $name) => strtoupper($name))
        ->sort()
        ->groupBy(function (string $name): string {
            $prefix = explode('_', $name, 2)[0] ?? 'AUTRE';
            return in_array($prefix, ['VIEW', 'CREATE', 'EDIT', 'DELETE', 'MANAGE', 'EXPORT', 'IMPORT', 'VALIDATE', 'GENERATE'], true)
                ? $prefix
                : 'AUTRE';
        });

    $permissionGroupOrder = ['MANAGE', 'VIEW', 'CREATE', 'EDIT', 'DELETE', 'IMPORT', 'EXPORT', 'VALIDATE', 'GENERATE', 'AUTRE'];

    $initials = collect(explode(' ', trim($user->name)))
        ->filter()
        ->map(fn (string $part) => mb_substr($part, 0, 1))
        ->take(2)
        ->implode('');
@endphp

<div class="user-details">
    <div class="row">
        <div class="col-12">
            <div class="card user-hero">
                <div class="card-body user-hero-body">
                    <div class="user-hero-left">
                        <div class="user-avatar" aria-hidden="true">
                            {{ $initials !== '' ? $initials : 'U' }}
                        </div>
                        <div class="user-hero-meta">
                            <div class="user-hero-name">{{ $user->name }}</div>
                            <div class="user-hero-sub">
                                <span class="user-hero-item">
                                    <span class="user-hero-label">Email</span>
                                    <span class="user-hero-value">{{ $user->email }}</span>
                                </span>
                                @if(!empty($user->username))
                                    <span class="user-hero-sep" aria-hidden="true">•</span>
                                    <span class="user-hero-item">
                                        <span class="user-hero-label">Identifiant</span>
                                        <span class="user-hero-value">{{ $user->username }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="user-hero-badges">
                                <span class="badge badge-secondary">
                                    {{ $user->paroisse?->nom ?? 'Paroisse : N/A' }}
                                </span>
                                <span class="badge badge-primary">
                                    {{ $roles->count() }} rôle(s)
                                </span>
                                <span class="badge badge-info">
                                    {{ $permissions->count() }} permission(s)
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="user-hero-right">
                        @can('manage_users')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm btn-rounded">
                                Modifier
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informations</h4>
                </div>
                <div class="card-body">
                    <div class="kv">
                        <div class="kv-row">
                            <div class="kv-key">Nom</div>
                            <div class="kv-val">{{ $user->name }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Email</div>
                            <div class="kv-val">{{ $user->email }}</div>
                        </div>
                        @if(!empty($user->username))
                            <div class="kv-row">
                                <div class="kv-key">Identifiant</div>
                                <div class="kv-val">{{ $user->username }}</div>
                            </div>
                        @endif
                        <div class="kv-row">
                            <div class="kv-key">Paroisse</div>
                            <div class="kv-val">{{ $user->paroisse?->nom ?? 'N/A' }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Créé le</div>
                            <div class="kv-val">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Modifié le</div>
                            <div class="kv-val">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Accès</h4>
                </div>
                <div class="card-body">
                    <div class="section-block">
                        <div class="section-title">Rôles</div>
                        <div class="chip-wrap">
                            @forelse($roles as $role)
                                <span class="chip chip-primary">{{ strtoupper($role->name) }}</span>
                            @empty
                                <span class="text-muted">Aucun rôle</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="section-block mt-4">
                        <div class="section-title d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <span>Permissions</span>
                            <span class="text-muted small">{{ $permissions->count() }} au total</span>
                        </div>

                        <div class="permission-groups">
                            @foreach($permissionGroupOrder as $groupKey)
                                @php($group = $permissionGroups->get($groupKey))
                                @if($group && $group->count() > 0)
                                    <div class="permission-group">
                                        <div class="permission-group-head">
                                            <span class="permission-group-title">{{ $groupKey }}</span>
                                            <span class="permission-group-count">{{ $group->count() }}</span>
                                        </div>
                                        <div class="chip-wrap">
                                            @foreach($group as $permName)
                                                <span class="chip chip-info">{{ $permName }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if($permissions->count() === 0)
                                <span class="text-muted">Aucune permission</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
