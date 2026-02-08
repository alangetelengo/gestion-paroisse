@extends('layouts.app')

@section('title', 'Événements')
@section('page-title', 'Gestion des événements')

@push('styles')
<style>
.page-list .card { border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: none; }
.page-list .card-header { background: linear-gradient(135deg, var(--primary, #6A1B9A) 0%, #552586 100%); color: #fff; border-radius: 12px 12px 0 0; padding: 1.25rem 1.5rem; }
.page-list .card-title { font-weight: 600; font-size: 1.2rem; }
.page-list .filters-card { background: #f8f9fa; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem; }
.page-list .form-control { border-radius: 8px; border: 1px solid #dee2e6; }
.page-list .btn-filter { padding: 10px 24px; border-radius: 8px; font-weight: 600; }
.page-list .search-local { max-width: 300px; }
.page-list .table-list { font-size: 0.95rem; }
.page-list .table-list thead th { background: var(--primary, #6A1B9A); color: #fff; font-weight: 600; padding: 14px 16px; border: none; }
.page-list .table-list thead th:first-child { border-radius: 8px 0 0 0; }
.page-list .table-list thead th:last-child { border-radius: 0 8px 0 0; }
.page-list .table-list tbody tr { transition: background 0.2s; }
.page-list .table-list tbody tr:hover { background: rgba(106, 27, 154, 0.04); }
.page-list .table-list td { padding: 14px 16px; vertical-align: middle; }
.page-list .badge-type { padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 500; background: rgba(106, 27, 154, 0.12); color: var(--primary, #6A1B9A); }
.page-list .date-cell .date { font-weight: 600; }
.page-list .date-cell .time { font-size: 0.85rem; color: #6c757d; }
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
                    <i class="fas fa-calendar-alt me-3" style="font-size: 1.4rem; opacity: 0.9;"></i>
                    Liste des événements
                </h4>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('events.index') }}" class="btn btn-action btn-refresh">
                        <i class="fas fa-sync-alt"></i> Rafraîchir
                    </a>
                    @can('create_events')
                    <a href="{{ route('events.create') }}" class="btn btn-action btn-add">
                        <i class="fas fa-plus"></i> Ajouter un événement
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                {{-- Filtres --}}
                <div class="filters-card">
                    <form method="GET" action="{{ route('events.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-muted">Type</label>
                                <select name="type" class="form-control">
                                    <option value="">Tous</option>
                                    @foreach(['messe' => 'Messe', 'célébration' => 'Célébration', 'activité' => 'Activité'] as $value => $label)
                                        <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-muted">Date du</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-muted">Au</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
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
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-filter w-100" type="submit">
                                    <i class="fas fa-filter me-1"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Recherche locale --}}
                <div class="d-flex justify-content-end mb-4">
                    <div class="search-local">
                        <input type="text" id="events-local-search" class="form-control" placeholder="Titre, lieu, célébrant...">
                    </div>
                </div>

                @if($events->count() > 0)
                {{-- Tableau --}}
                <div class="table-responsive rounded overflow-hidden">
                    <table class="table table-list table-hover mb-0" id="events-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Célébré par</th>
                                <th class="text-center" style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                            <tr>
                                <td class="date-cell">
                                    <span class="date d-block">{{ $event->date_evenement?->format('d/m/Y') ?? '—' }}</span>
                                    <span class="time">{{ optional($event->heure_evenement)->format('H:i') }}</span>
                                </td>
                                <td><strong>{{ $event->titre }}</strong></td>
                                <td><span class="badge badge-type">{{ ucfirst($event->type) }}</span></td>
                                <td>{{ optional($event->celebrePar)->prenom }} {{ optional($event->celebrePar)->nom }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('events.show', $event) }}" class="btn btn-view btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit_events')
                                        <a href="{{ route('events.edit', $event) }}" class="btn btn-edit btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-pen"></i> Modifier
                                        </a>
                                        @endcan
                                        @can('delete_events')
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline" data-confirm="Êtes-vous sûr de vouloir supprimer cet événement ?">
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
                    {{ $events->withQueryString()->links() }}
                </div>
                @else
                <div class="empty-state text-center">
                    <i class="fas fa-calendar-alt empty-icon d-block"></i>
                    <h5 class="text-muted mb-2">Aucun événement trouvé</h5>
                    <p class="text-muted mb-4">Commencez par ajouter votre premier événement (messe, célébration, activité...).</p>
                    @can('create_events')
                    <a href="{{ route('events.create') }}" class="btn btn-add btn-action">
                        <i class="fas fa-plus"></i> Ajouter un événement
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('events-table');
        const searchInput = document.getElementById('events-local-search');
        if (!table || !searchInput) return;

        const rows = Array.from(table.querySelectorAll('tbody tr'));

        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    });
</script>
@endpush
