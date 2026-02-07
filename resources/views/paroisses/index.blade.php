@extends('layouts.app')

@section('title', 'Paroisses')
@section('page-title', 'Gestion des paroisses')

@push('styles')
<style>
.page-list .card { border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: none; }
.page-list .card-header { background: linear-gradient(135deg, var(--primary, #6A1B9A) 0%, #552586 100%); color: #fff; border-radius: 12px 12px 0 0; padding: 1.25rem 1.5rem; }
.page-list .card-title { font-weight: 600; font-size: 1.2rem; }
.page-list .table-list { font-size: 0.95rem; }
.page-list .table-list thead th { background: var(--primary, #6A1B9A); color: #fff; font-weight: 600; padding: 14px 16px; border: none; }
.page-list .table-list thead th:first-child { border-radius: 8px 0 0 0; }
.page-list .table-list thead th:last-child { border-radius: 0 8px 0 0; }
.page-list .table-list tbody tr { transition: background 0.2s; }
.page-list .table-list tbody tr:hover { background: rgba(106, 27, 154, 0.04); }
.page-list .table-list td { padding: 14px 16px; vertical-align: middle; }
.page-list .avatar-icon { width: 40px; height: 40px; border-radius: 50%; background: rgba(106, 27, 154, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary, #6A1B9A); }
.page-list .badge-code { padding: 5px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 500; background: rgba(106, 27, 154, 0.12); color: var(--primary, #6A1B9A); }
.page-list .empty-state { padding: 4rem 2rem; }
.page-list .empty-state .empty-icon { font-size: 5rem; color: #dee2e6; margin-bottom: 1rem; }
.page-list .pagination { gap: 4px; }
.page-list .pagination .page-link { border-radius: 8px !important; }
</style>
@endpush

@section('content')
<div class="page-list">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h4 class="card-title mb-0 d-flex align-items-center">
                    <i class="fas fa-church me-3" style="font-size: 1.4rem; opacity: 0.9;"></i>
                    Liste des paroisses
                </h4>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('paroisses.index') }}" class="btn btn-action btn-refresh">
                        <i class="fas fa-sync-alt"></i> Rafraîchir
                    </a>
                    @can('manage_paroisses')
                    <a href="{{ route('paroisses.create') }}" class="btn btn-action btn-add">
                        <i class="fas fa-plus"></i> Ajouter une paroisse
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @if($paroisses->count() > 0)
                {{-- Tableau --}}
                <div class="table-responsive rounded overflow-hidden">
                    <table class="table table-list table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Code</th>
                                <th>Ville</th>
                                <th>Diocèse</th>
                                <th>Curé</th>
                                <th>Statut</th>
                                <th class="text-center" style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paroisses as $paroisse)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-icon me-3">
                                            <i class="fas fa-church"></i>
                                        </div>
                                        <strong>{{ $paroisse->nom }}</strong>
                                    </div>
                                </td>
                                <td><span class="badge badge-code">{{ $paroisse->code_paroisse ?? 'N/A' }}</span></td>
                                <td>
                                    <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                    {{ $paroisse->ville ?? 'N/A' }}
                                </td>
                                <td><span class="badge badge-primary">{{ $paroisse->diocèse ?? 'N/A' }}</span></td>
                                <td>
                                    @if($paroisse->curé)
                                        <i class="fas fa-user me-2 text-muted"></i>
                                        {{ $paroisse->curé->nom }} {{ $paroisse->curé->prenom }}
                                    @else
                                        <span class="text-muted">Non assigné</span>
                                    @endif
                                </td>
                                <td>
                                    @if($paroisse->actif)
                                        <span class="badge badge-success"><i class="fas fa-check me-1"></i>Actif</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times me-1"></i>Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('paroisses.show', $paroisse) }}" class="btn btn-view btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('manage_paroisses')
                                        <a href="{{ route('paroisses.edit', $paroisse) }}" class="btn btn-edit btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-pen"></i> Modifier
                                        </a>
                                        <form action="{{ route('paroisses.destroy', $paroisse) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir désactiver cette paroisse ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete btn-danger btn-sm" title="Désactiver">
                                                <i class="fas fa-trash-alt"></i>
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
                <div class="mt-4 d-flex justify-content-center">
                    {{ $paroisses->links() }}
                </div>
                @else
                <div class="empty-state text-center">
                    <i class="fas fa-church empty-icon d-block"></i>
                    <h5 class="text-muted mb-2">Aucune paroisse trouvée</h5>
                    <p class="text-muted mb-4">Commencez par ajouter votre première paroisse.</p>
                    @can('manage_paroisses')
                    <a href="{{ route('paroisses.create') }}" class="btn btn-add btn-action">
                        <i class="fas fa-plus"></i> Ajouter une paroisse
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection
