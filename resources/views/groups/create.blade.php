@extends('layouts.app')

@section('title', 'Créer un groupe')
@section('page-title', 'Créer un groupe')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">Groupes</a></li>
    <li class="breadcrumb-item active" aria-current="page">Créer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    Nouveau groupe
                </h4>
            </div>
            <div class="card-body">
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

                <form action="{{ route('groups.store') }}" method="POST">
                    @csrf

                    @include('groups._form')

                    <div class="text-end mt-4 pt-4 border-top">
                        <a href="{{ route('groups.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Créer</button>
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
                    <li class="mb-2">1. Saisir le <strong>nom</strong> du groupe.</li>
                    <li class="mb-0">2. Description et membres sont optionnels.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

