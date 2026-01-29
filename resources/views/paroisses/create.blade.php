@extends('layouts.app')

@section('title', 'Créer une paroisse')
@section('page-title', 'Créer une paroisse')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Nouvelle paroisse</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('paroisses.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" data-transform="upper"
                               value="{{ old('nom') }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Code paroisse</label>
                            <input type="text" name="code_paroisse" class="form-control @error('code_paroisse') is-invalid @enderror" data-transform="upper"
                                   value="{{ old('code_paroisse') }}">
                            @error('code_paroisse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Diocèse</label>
                            <input type="text" name="diocèse" class="form-control" data-transform="title" value="{{ old('diocèse') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ville</label>
                            <input type="text" name="ville" class="form-control" data-transform="title" value="{{ old('ville') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pays</label>
                            <input type="text" name="pays" class="form-control" data-transform="title" value="{{ old('pays', 'République du Congo') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control" value="{{ old('adresse') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" data-input="phone" value="{{ old('telephone') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" data-transform="lower"
                                   value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Curé</label>
                        <select name="curé_id" class="form-control @error('curé_id') is-invalid @enderror">
                            <option value="">Sélectionner un curé</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('curé_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->prenom }} {{ $member->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('curé_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('paroisses.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
