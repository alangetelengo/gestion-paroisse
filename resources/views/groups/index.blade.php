@extends('layouts.app')

@section('title', 'Groupes')
@section('page-title', 'Gestion des groupes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-user-3 me-2"></i>
                    Liste des groupes
                </h4>
                <div class="card-action">
                    <a href="{{ route('groups.index') }}" class="btn btn-secondary me-2">
                        Rafraîchir
                    </a>
                    @can('create_groups')
                    <a href="{{ route('groups.create') }}" class="btn btn-citron" style="font-weight: 600; padding: 10px 24px;">
                        Ajouter un groupe
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('groups.index') }}" class="mb-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control">
                                <option value="">Tous</option>
                                @foreach(['chorale' => 'Chorale', 'catéchisme' => 'Catéchisme', 'mouvement' => 'Mouvement', 'autre' => 'Autre'] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(isset($paroisses) && $paroisses->count() > 0)
                            <div class="col-md-3">
                                <label class="form-label">Paroisse</label>
                                <select name="paroisse_id" class="form-control">
                                    <option value="">Toutes</option>
                                    @foreach($paroisses as $paroisse)
                                        <option value="{{ $paroisse->id }}" @selected((string) request('paroisse_id') === (string) $paroisse->id)>
                                            {{ $paroisse->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label class="form-label">Recherche</label>
                            <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Nom, description...">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" type="submit" style="margin-top: 24px;">Appliquer les filtres</button>
                        </div>
                    </div>
                </form>

                @if($groups->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Paroisse</th>
                                <th>Responsable</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <td><strong>{{ $group->nom }}</strong></td>
                                    <td>{{ ucfirst($group->type) }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $group->paroisse?->nom ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $group->responsable?->prenom }} {{ $group->responsable?->nom }}
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            @can('edit_groups')
                                            <a href="{{ route('groups.edit', $group) }}" class="btn btn-warning btn-sm me-1" title="Modifier">
                                                Modifier
                                            </a>
                                            @endcan
                                            @can('delete_groups')
                                            <form action="{{ route('groups.destroy', $group) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?')"
                                                        title="Supprimer">
                                                    <i class="flaticon-381-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $groups->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="flaticon-381-user-3" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                    <h5 class="text-muted">Aucun groupe trouvé</h5>
                    <p class="text-muted">Commencez par ajouter votre premier groupe.</p>
                    @can('create_groups')
                    <a href="{{ route('groups.create') }}" class="btn btn-citron mt-3">
                        Ajouter un groupe
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

