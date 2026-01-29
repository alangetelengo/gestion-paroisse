@extends('layouts.app')

@section('title', 'Événements')
@section('page-title', 'Détail de l\'événement')

@section('content')
@php
    $subtitleParts = [];
    if ($event->date_evenement) {
        $subtitleParts[] = $event->date_evenement->format('d/m/Y');
    }
    if ($event->heure_evenement) {
        $subtitleParts[] = $event->heure_evenement->format('H:i');
    }
    $subtitle = implode(' • ', $subtitleParts);
@endphp

<div class="user-details">
    <div class="row">
        <div class="col-12">
            <div class="card user-hero">
                <div class="card-body user-hero-body">
                    <div class="user-hero-left">
                        <div class="user-avatar" aria-hidden="true">
                            <i class="flaticon-381-calendar-1"></i>
                        </div>
                        <div class="user-hero-meta">
                            <div class="user-hero-name">
                                {{ $event->titre }}
                            </div>
                            <div class="user-hero-sub">
                                @if($subtitle !== '')
                                    <span class="user-hero-item">
                                        <span class="user-hero-label">Date</span>
                                        <span class="user-hero-value">{{ $subtitle }}</span>
                                    </span>
                                @endif
                                <span class="user-hero-sep" aria-hidden="true">•</span>
                                <span class="user-hero-item">
                                    <span class="user-hero-label">Type</span>
                                    <span class="user-hero-value">{{ $event->type }}</span>
                                </span>
                            </div>
                            <div class="user-hero-badges">
                                <span class="badge badge-secondary">
                                    {{ $event->paroisse?->nom ?? 'Paroisse : N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="user-hero-right">
                        @can('edit_events')
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-warning btn-sm btn-rounded">
                                Modifier
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informations</h4>
                </div>
                <div class="card-body">
                    <div class="kv">
                        <div class="kv-row">
                            <div class="kv-key">Titre</div>
                            <div class="kv-val">{{ $event->titre }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Type</div>
                            <div class="kv-val">{{ $event->type }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Date</div>
                            <div class="kv-val">{{ $event->date_evenement?->format('d/m/Y') ?? '—' }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Heure</div>
                            <div class="kv-val">{{ $event->heure_evenement?->format('H:i') ?? '—' }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Lieu</div>
                            <div class="kv-val">{{ $event->lieu ?? '—' }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Intention</div>
                            <div class="kv-val">{{ $event->intention ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Personnes</h4>
                </div>
                <div class="card-body">
                    <div class="kv">
                        <div class="kv-row">
                            <div class="kv-key">Célébré par</div>
                            <div class="kv-val">
                                @if($event->celebrePar)
                                    {{ $event->celebrePar->prenom }} {{ $event->celebrePar->nom }}
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Participants</div>
                            <div class="kv-val">
                                @if($event->participants->count() > 0)
                                    <ul class="mb-0" style="padding-left: 18px;">
                                        @foreach($event->participants as $participant)
                                            <li>{{ $participant->prenom }} {{ $participant->nom }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">Aucun participant renseigné</span>
                                @endif
                            </div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Créé le</div>
                            <div class="kv-val">{{ $event->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
                        </div>
                        <div class="kv-row">
                            <div class="kv-key">Modifié le</div>
                            <div class="kv-val">{{ $event->updated_at?->format('d/m/Y H:i') ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Description</h4>
                </div>
                <div class="card-body">
                    <div style="white-space: pre-wrap;">
                        {{ $event->description ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="text-end mt-3">
                <a href="{{ route('events.index') }}" class="btn btn-secondary btn-rounded">
                    Retour
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

