@extends('layouts.app')

@section('title', 'Modifier le type')
@section('page-title', 'Modifier le type')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="fas fa-sticky-note me-2"></i>
                    Modifier le type
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

                <form action="{{ route('revenue-types.update', $type) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" value="{{ old('code', $type->code) }}" required placeholder="ex: messe_dimanche">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select name="revenue_category_id" class="form-control" required>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" @selected((string) old('revenue_category_id', $type->revenue_category_id) === (string) $c->id)>{{ $c->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" value="{{ old('nom', $type->nom) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="ordre" class="form-control" value="{{ old('ordre', $type->ordre) }}" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description', $type->description) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="actif" value="1" class="form-check-input" id="actif" @checked(old('actif', $type->actif))>
                                <label class="form-check-label" for="actif">Actif</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('revenue-types.index', ['paroisse_id' => $type->paroisse_id]) }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
