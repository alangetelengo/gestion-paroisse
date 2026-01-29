@extends('layouts.app')

@section('title', 'Ajouter un type de recette')
@section('page-title', 'Ajouter un type')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-notepad me-2"></i>
                    Nouveau type de recette
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

                <form action="{{ route('revenue-types.store') }}" method="POST">
                    @csrf
                    @if(auth()->user()->hasRole('super_admin') && $paroisses->count() > 0)
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Paroisse <span class="text-danger">*</span></label>
                            <select name="paroisse_id" id="paroisse_id" class="form-control" required>
                                @foreach($paroisses as $p)
                                    <option value="{{ $p->id }}" @selected(old('paroisse_id', $paroisseId) == $p->id)>{{ $p->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                        <input type="hidden" name="paroisse_id" value="{{ auth()->user()->paroisse_id }}">
                    @endif
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select name="revenue_category_id" class="form-control" required>
                                <option value="">— Choisir —</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" @selected(old('revenue_category_id') == $c->id)>{{ $c->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}" required placeholder="ex: messe_dimanche">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="ordre" class="form-control" value="{{ old('ordre', 0) }}" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="actif" value="1" class="form-check-input" id="actif" @checked(old('actif', true))>
                                <label class="form-check-label" for="actif">Actif</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('revenue-types.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
