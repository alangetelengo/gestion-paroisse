@extends('layouts.app')

@section('title', 'Créer une configuration')
@section('page-title', 'Créer une configuration')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Nouvelle configuration</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('configurations.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Clé <span class="text-danger">*</span></label>
                        <input type="text" name="cle" class="form-control @error('cle') is-invalid @enderror"
                               value="{{ old('cle') }}" required>
                        @error('cle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Identifiant unique de la configuration (ex: nom_paroisse, couleur_primaire)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Valeur <span class="text-danger">*</span></label>
                        <input type="text" name="valeur" class="form-control @error('valeur') is-invalid @enderror"
                               value="{{ old('valeur') }}" required>
                        @error('valeur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                            <option value="string" {{ old('type') == 'string' ? 'selected' : '' }}>String</option>
                            <option value="integer" {{ old('type') == 'integer' ? 'selected' : '' }}>Integer</option>
                            <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Boolean</option>
                            <option value="float" {{ old('type') == 'float' ? 'selected' : '' }}>Float</option>
                            <option value="json" {{ old('type') == 'json' ? 'selected' : '' }}>JSON</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('configurations.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
