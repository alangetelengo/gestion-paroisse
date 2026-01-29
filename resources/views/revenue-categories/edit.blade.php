@extends('layouts.app')

@section('title', 'Modifier la catégorie')
@section('page-title', 'Modifier la catégorie')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-folder me-2"></i>
                    Modifier la catégorie
                </h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('revenue-categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Code</label>
                            <input type="text" class="form-control" value="{{ $category->code }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" value="{{ old('nom', $category->nom) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="ordre" class="form-control" value="{{ old('ordre', $category->ordre) }}" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description', $category->description) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="actif" value="1" class="form-check-input" id="actif" @checked(old('actif', $category->actif))>
                                <label class="form-check-label" for="actif">Actif</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('revenue-categories.index', ['paroisse_id' => $category->paroisse_id]) }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
