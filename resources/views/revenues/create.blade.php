@extends('layouts.app')

@section('title', 'Ajouter une recette')
@section('page-title', 'Ajouter une recette')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="fas fa-calculator me-2"></i>
                    Nouvelle recette
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

                <form action="{{ route('revenues.store') }}" method="POST">
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
</div>
@endsection

