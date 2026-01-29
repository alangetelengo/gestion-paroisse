@extends('layouts.app')

@section('title', 'Catégories de recettes')
@section('page-title', 'Catégories de recettes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-folder me-2"></i>
                    Catégories de recettes
                </h4>
                <div class="card-action">
                    @if($paroisses->count() > 0)
                        <form method="GET" action="{{ route('revenue-categories.index') }}" class="d-inline me-2">
                            <select name="paroisse_id" class="form-control form-control-sm d-inline-block w-auto" onchange="this.form.submit()">
                                @foreach($paroisses as $p)
                                    <option value="{{ $p->id }}" @selected($paroisseId == $p->id)>{{ $p->nom }}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                    @can('create_revenues')
                    <a href="{{ route('revenue-categories.create', $paroisseId ? ['paroisse_id' => $paroisseId] : []) }}" class="btn btn-citron">
                        <i class="flaticon-381-add-1 me-1"></i>
                        Ajouter une catégorie
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Ordre</th>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Paroisse</th>
                                <th>Actif</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                            <tr>
                                <td>{{ $cat->ordre }}</td>
                                <td><code>{{ $cat->code }}</code></td>
                                <td>{{ $cat->nom }}</td>
                                <td>{{ Str::limit($cat->description, 50) }}</td>
                                <td>{{ $cat->paroisse?->nom ?? '—' }}</td>
                                <td>
                                    @if($cat->actif)
                                        <span class="badge badge-success">Oui</span>
                                    @else
                                        <span class="badge badge-danger">Non</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @can('edit_revenues')
                                    <a href="{{ route('revenue-categories.edit', $cat) }}" class="btn btn-warning btn-sm">Modifier</a>
                                    @endcan
                                    @can('delete_revenues')
                                    <form action="{{ route('revenue-categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette catégorie ?');">
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
                <div class="mt-3">{{ $categories->withQueryString()->links() }}</div>
                @else
                <p class="text-muted mb-0">Aucune catégorie. Choisissez une paroisse ou créez une catégorie.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
