@extends('layouts.app')

@section('title', 'Mon profil')
@section('page-title', 'Mon profil')

@push('styles')
<style>
    .profile-hero {
        background: linear-gradient(135deg, var(--titre-page, var(--primary)) 0%, var(--titre-page-dark, var(--primary-dark)) 100%);
        color: white;
        border-radius: 0;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }
    .profile-hero::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -20%;
        width: 60%;
        height: 160%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 60%);
        pointer-events: none;
    }
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: 3px solid rgba(255, 255, 255, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--secondary);
        letter-spacing: 0.5px;
        flex-shrink: 0;
    }
    .profile-hero-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .profile-hero-meta {
        font-size: 0.9375rem;
        opacity: 0.95;
    }
    .profile-hero-meta span:not(:last-child)::after {
        content: ' · ';
        opacity: 0.7;
    }
    .profile-card {
        border: none;
        border-radius: 0;
        box-shadow: var(--shadow-md);
        transition: var(--transition-base);
    }
    .profile-card:hover {
        box-shadow: var(--shadow-lg);
    }
    .profile-card .card-header {
        background: linear-gradient(135deg, var(--rgba-primary-1) 0%, var(--rgba-primary-2) 100%);
        color: var(--primary);
        border-bottom: 2px solid var(--primary);
        font-weight: 700;
        padding: 1rem 1.5rem;
    }
    .profile-card .card-header i {
        color: var(--primary);
    }
    .profile-info-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        font-size: 0.8125rem;
        font-weight: 600;
        background: var(--rgba-primary-1);
        color: var(--primary);
        border: 1px solid var(--rgba-primary-2);
    }
    .profile-section-title {
        font-weight: 700;
        color: var(--primary);
        font-size: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--rgba-primary-1);
    }
</style>
@endpush

@section('content')
{{-- Hero : avatar + nom + infos --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card profile-hero">
            <div class="card-body position-relative" style="padding: 2rem 2rem 1.5rem;">
                <div class="d-flex flex-wrap align-items-center gap-4">
                    <div class="profile-avatar">
                        @php
                            $initials = collect(explode(' ', trim($user->name ?? 'U')))->filter()->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
                        @endphp
                        {{ $initials ?: 'U' }}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <h1 class="profile-hero-name mb-1">{{ $user->name }}</h1>
                        <div class="profile-hero-meta">
                            <span>{{ $user->email }}</span>
                            @if($user->paroisse?->nom)
                                <span>{{ $user->paroisse->nom }}</span>
                            @endif
                            @php($roles = $user->getRoleNames())
                            @if($roles->count())
                                <span>{{ $roles->implode(', ') }}</span>
                            @endif
                        </div>
                        @if($user->username)
                            <div class="mt-2">
                                <span class="profile-info-badge">{{ $user->username }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Carte Informations du compte --}}
    <div class="col-lg-8 mb-4">
        <div class="card profile-card">
            <div class="card-header d-flex align-items-center">
                <i class="flaticon-381-user me-2"></i>
                Informations du compte
            </div>
            <div class="card-body" style="padding: 1.5rem 2rem;">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nom d'utilisateur</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username', $user->username) }}" placeholder="Optionnel">
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top border-2" style="border-color: var(--rgba-primary-1) !important;">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="flaticon-381-save me-1"></i>
                            Enregistrer le profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Carte Sécurité (mot de passe) --}}
    <div class="col-lg-4 mb-4">
        <div class="card profile-card">
            <div class="card-header d-flex align-items-center">
                <i class="flaticon-381-lock me-2"></i>
                Sécurité
            </div>
            <div class="card-body" style="padding: 1.5rem 2rem;">
                <p class="text-muted small mb-3">
                    Modifiez votre mot de passe pour renforcer la sécurité de votre compte.
                </p>
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required placeholder="••••••••">
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="••••••••">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirmer le nouveau <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="flaticon-381-lock me-1"></i>
                        Mettre à jour le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
