@extends('layouts.app')

@section('title', 'Paroisses')
@section('page-title', 'Gestion des paroisses')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-home me-2"></i>
                    Liste des paroisses
                </h4>
                <div class="card-action">
                    @can('manage_paroisses')
                    <a href="{{ route('paroisses.create') }}" class="btn btn-citron" style="font-weight: 600; padding: 10px 24px;">
                        Ajouter une paroisse
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @if($paroisses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Code</th>
                                <th>Ville</th>
                                <th>Diocèse</th>
                                <th>Curé</th>
                                <th>Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paroisses as $paroisse)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3" style="width: 40px; height: 40px; border-radius: 50%; background: var(--rgba-primary-1); display: flex; align-items: center; justify-content: center;">
                                                <i class="flaticon-381-home" style="color: var(--primary);"></i>
                                            </div>
                                            <strong>{{ $paroisse->nom }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $paroisse->code_paroisse ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <i class="flaticon-381-location me-2 text-muted"></i>
                                        {{ $paroisse->ville ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $paroisse->diocèse ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($paroisse->curé)
                                            <i class="flaticon-381-user me-2 text-muted"></i>
                                            {{ $paroisse->curé->nom }} {{ $paroisse->curé->prenom }}
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($paroisse->actif)
                                            <span class="badge badge-success">
                                                <i class="flaticon-381-check me-1"></i>Actif
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="flaticon-381-close me-1"></i>Inactif
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('paroisses.show', $paroisse) }}" class="btn btn-info btn-sm me-1" title="Voir">
                                                <i class="flaticon-381-view"></i>
                                            </a>
                                            @can('manage_paroisses')
                                            <a href="{{ route('paroisses.edit', $paroisse) }}" class="btn btn-warning btn-sm me-1" title="Modifier">
                                                Modifier
                                            </a>
                                            <form action="{{ route('paroisses.destroy', $paroisse) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir désactiver cette paroisse ?')"
                                                        title="Désactiver">
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
                    {{ $paroisses->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="flaticon-381-home" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                    <h5 class="text-muted">Aucune paroisse trouvée</h5>
                    <p class="text-muted">Commencez par ajouter votre première paroisse.</p>
                    @can('manage_paroisses')
                    <a href="{{ route('paroisses.create') }}" class="btn btn-citron mt-3">
                        Ajouter une paroisse
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
