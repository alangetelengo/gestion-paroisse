@extends('layouts.app')

@section('title', 'Ajouter une recette')
@section('page-title', 'Ajouter une recette')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li class="breadcrumb-item"><a href="{{ route('revenues.index') }}">Recettes</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ajouter</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    Nouvelle recette
                </h4>
            </div>
            <div class="card-body">
                <div id="offline-create-notice" class="alert alert-warning mb-4" style="display: none;">
                    <i class="fas fa-wifi me-1"></i> <strong>Mode hors ligne.</strong> La recette sera enregistrée localement et synchronisée automatiquement dès que la connexion sera rétablie.
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <h6 class="alert-heading mb-2">Erreurs de validation</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('revenues.store') }}" method="POST" data-offline-sync="revenue">
                    @csrf

                    @include('revenues._form')

                    <div class="text-end mt-4 pt-4 border-top">
                        <a href="{{ route('revenues.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-3 mt-4 mt-lg-0">
        <div class="card border-0 shadow-sm create-help-panel">
            <div class="card-body p-4">
                <h6 class="mb-3 d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    En bref
                </h6>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2">1. Choisir la <strong>catégorie</strong>, puis le <strong>type</strong>.</li>
                    <li class="mb-2">2. Saisir la <strong>date</strong> et le <strong>montant</strong>.</li>
                    <li class="mb-0">3. Les autres champs se remplissent ou sont optionnels.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

