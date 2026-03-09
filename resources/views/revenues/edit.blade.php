@extends('layouts.app')

@section('title', 'Modifier une recette')
@section('page-title', 'Modifier la recette')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h4 class="card-title mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    Modifier la recette
                </h4>
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#revenueHelpModal" title="Aide">
                    <i class="fas fa-info-circle me-1"></i> Aide
                </button>
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

                <form action="{{ route('revenues.update', $revenue) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('revenues._form')

                    <div class="text-end mt-4 pt-4 border-top">
                        <a href="{{ route('revenues.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('revenues._help_modal')
@endsection

