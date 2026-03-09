@extends('layouts.app')

@section('title', 'Ajouter une dépense')
@section('page-title', 'Ajouter une dépense')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Dépenses</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ajouter</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Nouvelle dépense
                </h4>
            </div>
            <div class="card-body">
                <div id="offline-create-notice" class="alert alert-warning mb-4" style="display: none;">
                    <i class="fas fa-wifi me-1"></i> <strong>Mode hors ligne.</strong> La dépense sera enregistrée localement. Les pièces jointes ne peuvent pas être ajoutées hors ligne. Synchronisation automatique à la reconnexion.
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

                <form data-offline-sync="expense" action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @include('expenses._form')

                    <div class="text-end mt-4 pt-4 border-top">
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Annuler</a>
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
                    <li class="mb-2">1. Choisir la <strong>catégorie</strong> (fixe, variable, exceptionnelle ou alimentation).</li>
                    <li class="mb-2">2. Renseigner le <strong>type</strong>, le <strong>montant</strong> et la pièce jointe si besoin.</li>
                    <li class="mb-0">3. Les autres champs sont optionnels.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

