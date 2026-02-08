@extends('layouts.app')

@section('title', \App\Models\Sacrament::TYPES[$type] ?? 'Sacrements')
@section('page-title', \App\Models\Sacrament::TYPES[$type] ?? 'Sacrements')

@push('styles')
<style>
.page-list .card { border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: none; }
.page-list .card-header { background: linear-gradient(135deg, var(--primary, #6A1B9A) 0%, #552586 100%); color: #fff; border-radius: 12px 12px 0 0; padding: 1.25rem 1.5rem; }
.page-list .card-title { font-weight: 600; font-size: 1.2rem; }
.page-list .filters-card { background: #f8f9fa; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem; }
.page-list .form-control { border-radius: 8px; border: 1px solid #dee2e6; }
.page-list .btn-filter { padding: 10px 24px; border-radius: 8px; font-weight: 600; }
.page-list .table-list { font-size: 0.95rem; }
.page-list .table-list thead th { background: var(--primary, #6A1B9A); color: #fff; font-weight: 600; padding: 14px 16px; border: none; }
.page-list .table-list thead th:first-child { border-radius: 8px 0 0 0; }
.page-list .table-list thead th:last-child { border-radius: 0 8px 0 0; }
.page-list .table-list tbody tr { transition: background 0.2s; }
.page-list .table-list tbody tr:hover { background: rgba(106, 27, 154, 0.04); }
.page-list .table-list td { padding: 14px 16px; vertical-align: middle; }
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
                    <i class="fas fa-heart me-3" style="font-size: 1.4rem; opacity: 0.9;"></i>
                    Liste des {{ \App\Models\Sacrament::TYPES[$type] ?? $type }}
                </h4>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('sacraments.index', ['type' => $type]) }}" class="btn btn-action btn-refresh">
                        <i class="fas fa-sync-alt"></i> Rafraîchir
                    </a>
                    @can(\App\Http\Controllers\SacramentController::TYPE_PERMISSIONS[$type]['create'] ?? 'view_baptisms')
                    <a href="{{ route('sacraments.create', ['type' => $type]) }}" class="btn btn-action btn-add">
                        <i class="fas fa-plus"></i> Ajouter
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                {{-- Filtres --}}
                <div class="filters-card">
                    <form method="GET" action="{{ route('sacraments.index') }}">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <div class="row g-3 align-items-end">
                            @if(auth()->user()->hasRole('super_admin') && $paroisses->count() > 0)
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-muted">Paroisse</label>
                                <select name="paroisse_id" class="form-control">
                                    <option value="">Toutes</option>
                                    @foreach($paroisses as $paroisse)
                                        <option value="{{ $paroisse->id }}" @selected((string) request('paroisse_id') === (string) $paroisse->id)>{{ $paroisse->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-muted">Date du</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-muted">Au</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary btn-filter w-100" type="submit">
                                    <i class="fas fa-filter me-1"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @if($sacraments->count() > 0)
                {{-- Tableau --}}
                <div class="table-responsive rounded overflow-hidden">
                    <table class="table table-list table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Bénéficiaire / Nom</th>
                                <th>Lieu</th>
                                <th>Célébrant</th>
                                <th class="text-center" style="width: 220px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sacraments as $sacrament)
                            <tr>
                                <td><span class="text-nowrap fw-semibold">{{ $sacrament->date_celebration?->format('d/m/Y') }}</span></td>
                                <td><strong>{{ $sacrament->beneficiary_name ?: ($sacrament->beneficiary ? $sacrament->beneficiary->prenom . ' ' . $sacrament->beneficiary->nom : '—') }}</strong></td>
                                <td>{{ $sacrament->lieu ?? '—' }}</td>
                                <td>{{ $sacrament->celebrant ? $sacrament->celebrant->prenom . ' ' . $sacrament->celebrant->nom : '—' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        @can(\App\Http\Controllers\SacramentController::TYPE_PERMISSIONS[$type]['view'] ?? 'view_baptisms')
                                        <a href="{{ route('sacraments.show', $sacrament) }}" class="btn btn-view btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        @endcan
                                        @can(\App\Http\Controllers\SacramentController::TYPE_PERMISSIONS[$type]['edit'] ?? 'edit_baptisms')
                                        <a href="{{ route('sacraments.edit', $sacrament) }}" class="btn btn-edit btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-pen"></i> Modifier
                                        </a>
                                        @endcan
                                        @can(\App\Http\Controllers\SacramentController::TYPE_PERMISSIONS[$type]['delete'] ?? 'delete_baptisms')
                                        <form action="{{ route('sacraments.destroy', $sacrament) }}" method="POST" class="d-inline" data-confirm="Supprimer cet enregistrement ?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete btn-danger btn-sm" title="Supprimer">
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
                    {{ $sacraments->withQueryString()->links() }}
                </div>
                @else
                <div class="empty-state text-center">
                    <i class="fas fa-heart empty-icon d-block"></i>
                    <h5 class="text-muted mb-2">Aucun enregistrement trouvé</h5>
                    <p class="text-muted mb-4">Aucun sacrement de type "{{ \App\Models\Sacrament::TYPES[$type] ?? $type }}" n'a été enregistré pour cette période.</p>
                    @can(\App\Http\Controllers\SacramentController::TYPE_PERMISSIONS[$type]['create'] ?? 'view_baptisms')
                    <a href="{{ route('sacraments.create', ['type' => $type]) }}" class="btn btn-add btn-action">
                        <i class="fas fa-plus"></i> Ajouter
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
