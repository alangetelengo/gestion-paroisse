@extends('layouts.app')

@section('title', 'Membres')
@section('page-title', 'Détail du membre')

@section('content')
@php
    $fullName = trim(($member->prenom ?? '') . ' ' . ($member->nom ?? ''));
    $initials = collect(explode(' ', $fullName))
        ->filter()
        ->map(fn (string $part) => mb_substr($part, 0, 1))
        ->take(2)
        ->implode('');

    $statusLabel = [
        'actif' => 'Actif',
        'inactif' => 'Inactif',
        'décédé' => 'Décédé',
    ][$member->statut] ?? ucfirst($member->statut ?? 'Inconnu');

    $statusClass = match ($member->statut) {
        'actif' => 'badge-success',
        'inactif' => 'badge-warning',
        'décédé' => 'badge-danger',
        default => 'badge-secondary',
    };
@endphp

<div class="user-details">
    <div class="row">
        <div class="col-12">
            <div class="card user-hero">
                <div class="card-body user-hero-body">
                    <div class="user-hero-left">
                        <div class="user-avatar" aria-hidden="true">
                            {{ $initials !== '' ? $initials : 'M' }}
                        </div>
                        <div class="user-hero-meta">
                            <div class="user-hero-name">
                                <i class="flaticon-381-user me-2"></i>
                                {{ $fullName !== '' ? $fullName : 'Membre sans nom' }}
                            </div>
                            <div class="user-hero-sub">
                                <span class="user-hero-item">
                                    <span class="user-hero-label">Paroisse</span>
                                    <span class="user-hero-value">
                                        {{ $member->paroisse?->nom ?? 'N/A' }}
                                    </span>
                                </span>
                                <span class="user-hero-sep" aria-hidden="true">•</span>
                                <span class="user-hero-item">
                                    <span class="user-hero-label">Statut</span>
                                    <span class="user-hero-value">
                                        <span class="badge {{ $statusClass }}">{{ strtoupper($statusLabel) }}</span>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="user-hero-right">
                        @can('edit_members')
                            <a href="{{ route('members.edit', $member) }}" class="btn btn-warning btn-sm btn-rounded">
                                Modifier
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Coordonnées</h4>
                </div>
                <div class="card-body">
                    <div class="kv">
                        <div class="kv-row">
                            <div class="kv-key">Téléphone</div>
                            <div class="kv-val">{{ $member->telephone ?? '—' }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Email</div>
                            <div class="kv-val">{{ $member->email ?? '—' }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Adresse</div>
                            <div class="kv-val">{{ $member->adresse ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informations personnelles</h4>
                </div>
                <div class="card-body">
                    <div class="kv">
                        <div class="kv-row">
                            <div class="kv-key">Date de naissance</div>
                            <div class="kv-val">{{ $member->date_naissance?->format('d/m/Y') ?? '—' }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Sexe</div>
                            <div class="kv-val">
                                @if($member->sexe === 'M')
                                    Masculin
                                @elseif($member->sexe === 'F')
                                    Féminin
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Créé le</div>
                            <div class="kv-val">{{ $member->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Modifié le</div>
                            <div class="kv-val">{{ $member->updated_at?->format('d/m/Y H:i') ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Notes</h4>
                </div>
                <div class="card-body">
                    <div style="white-space: pre-wrap;">
                        {{ $member->notes ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="text-end mt-3">
                <a href="{{ route('members.index') }}" class="btn btn-secondary btn-rounded">
                    Retour
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

