@extends('layouts.app')

@section('title', 'Détails paroisse')
@section('page-title', 'Détails de la paroisse')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h4 class="card-title mb-0">{{ $paroisse->nom }}</h4>
                <div class="card-action d-flex align-items-center gap-2 flex-wrap">
                    @can('manage_paroisses')
                    <a href="{{ route('paroisses.edit', $paroisse) }}" class="btn btn-warning btn-sm">
                        Modifier
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Nom :</strong></div>
                    <div class="col-md-8">{{ $paroisse->nom }}</div>
                </div>
                @if($paroisse->code_paroisse)
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Code :</strong></div>
                    <div class="col-md-8">{{ $paroisse->code_paroisse }}</div>
                </div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Adresse :</strong></div>
                    <div class="col-md-8">{{ $paroisse->adresse ?? 'N/A' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Ville :</strong></div>
                    <div class="col-md-8">{{ $paroisse->ville ?? 'N/A' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Pays :</strong></div>
                    <div class="col-md-8">{{ $paroisse->pays ?? 'N/A' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Téléphone :</strong></div>
                    <div class="col-md-8">{{ $paroisse->telephone ?? 'N/A' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Email :</strong></div>
                    <div class="col-md-8">{{ $paroisse->email ?? 'N/A' }}</div>
                </div>
                @if($paroisse->diocèse)
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Diocèse :</strong></div>
                    <div class="col-md-8">{{ $paroisse->diocèse }}</div>
                </div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Curé :</strong></div>
                    <div class="col-md-8">
                        @if($paroisse->curé)
                            {{ $paroisse->curé->prenom }} {{ $paroisse->curé->nom }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                @if($paroisse->description)
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Description :</strong></div>
                    <div class="col-md-8">{{ $paroisse->description }}</div>
                </div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Statut :</strong></div>
                    <div class="col-md-8">
                        @if($paroisse->actif)
                            <span class="badge badge-success">Actif</span>
                        @else
                            <span class="badge badge-danger">Inactif</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"><strong>Créé le :</strong></div>
                    <div class="col-md-8">{{ $paroisse->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
