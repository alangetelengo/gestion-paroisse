@extends('layouts.app')

@section('title', \App\Models\Sacrament::TYPES[$type] ?? 'Sacrements')
@section('page-title', \App\Models\Sacrament::TYPES[$type] ?? 'Sacrements')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-heart me-2"></i>
                    Liste des {{ \App\Models\Sacrament::TYPES[$type] ?? $type }}
                </h4>
                <div class="card-action">
                    <a href="{{ route('sacraments.index', ['type' => $type]) }}" class="btn btn-secondary me-2">Rafraîchir</a>
                    @can(\App\Http\Controllers\SacramentController::TYPE_PERMISSIONS[$type]['create'] ?? 'view_baptisms')
                    <a href="{{ route('sacraments.create', ['type' => $type]) }}" class="btn btn-citron">
                        <i class="flaticon-381-add-1 me-1"></i>Ajouter
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('sacraments.index') }}" class="mb-3">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="row g-3 align-items-end">
                        @if(auth()->user()->hasRole('super_admin') && $paroisses->count() > 0)
                            <div class="col-md-3">
                                <label class="form-label">Paroisse</label>
                                <select name="paroisse_id" class="form-control">
                                    <option value="">Toutes</option>
                                    @foreach($paroisses as $paroisse)
                                        <option value="{{ $paroisse->id }}" @selected((string) request('paroisse_id') === (string) $paroisse->id)>{{ $paroisse->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label class="form-label">Date du</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Au</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" type="submit">Filtrer</button>
                        </div>
                    </div>
                </form>

                @if($sacraments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Bénéficiaire / Nom</th>
                                <th>Lieu</th>
                                <th>Célébrant</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sacraments as $sacrament)
                            <tr>
                                <td>{{ $sacrament->date_celebration?->format('d/m/Y') }}</td>
                                <td>{{ $sacrament->beneficiary_name ?: ($sacrament->beneficiary ? $sacrament->beneficiary->prenom . ' ' . $sacrament->beneficiary->nom : '—') }}</td>
                                <td>{{ $sacrament->lieu ?? '—' }}</td>
                                <td>{{ $sacrament->celebrant ? $sacrament->celebrant->prenom . ' ' . $sacrament->celebrant->nom : '—' }}</td>
                                <td class="text-center">
                                    @can(\App\Http\Controllers\SacramentController::TYPE_PERMISSIONS[$type]['view'] ?? 'view_baptisms')
                                    <a href="{{ route('sacraments.show', $sacrament) }}" class="btn btn-info btn-sm me-1">Voir</a>
                                    @endcan
                                    @can(\App\Http\Controllers\SacramentController::TYPE_PERMISSIONS[$type]['edit'] ?? 'edit_baptisms')
                                    <a href="{{ route('sacraments.edit', $sacrament) }}" class="btn btn-warning btn-sm me-1">Modifier</a>
                                    @endcan
                                    @can(\App\Http\Controllers\SacramentController::TYPE_PERMISSIONS[$type]['delete'] ?? 'delete_baptisms')
                                    <form action="{{ route('sacraments.destroy', $sacrament) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet enregistrement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $sacraments->withQueryString()->links() }}</div>
                @else
                <p class="text-muted mb-0">Aucun enregistrement pour cette catégorie.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
