@extends('layouts.app')

@section('title', 'Événements')
@section('page-title', 'Gestion des événements')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-calendar-1 me-2"></i>
                    Liste des événements
                </h4>
                <div class="card-action">
                    <a href="{{ route('events.index') }}" class="btn btn-secondary me-2">
                       Rafraîchir la liste
                    </a>
                    @can('create_events')
                    <a href="{{ route('events.create') }}" class="btn btn-citron" style="font-weight: 600; padding: 10px 24px;">
                        Ajouter un événement
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('events.index') }}" class="mb-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control">
                                <option value="">Tous</option>
                                @foreach(['messe' => 'Messe', 'célébration' => 'Célébration', 'activité' => 'Activité'] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date du</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Au</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        @if(auth()->user()->hasRole('super_admin') && $paroisses->count() > 0)
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
                        <div class="col-md-3 ms-auto">
                            <button class="btn btn-primary w-100" type="submit" style="margin-top: 24px;">Appliquer les filtres</button>
                        </div>
                    </div>
                </form>

                {{-- Recherche locale dans la liste --}}
                <div class="row mb-3">
                    <div class="col-md-4 ms-auto">
                        <label class="form-label">Recherche dans la liste</label>
                        <input type="text" id="events-local-search" class="form-control" placeholder="Titre, lieu, célébrant...">
                    </div>
                </div>
                @if($events->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="events-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Célébré par</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td>
                                        <strong>{{ $event->date_evenement?->format('d/m/Y') ?? '—' }}</strong><br>
                                        <small class="text-muted">{{ optional($event->heure_evenement)->format('H:i') }}</small>
                                    </td>
                                    <td>{{ $event->titre }}</td>
                                    <td>{{ $event->type }}</td>
                                    <td>
                                        {{ optional($event->celebrePar)->prenom }} {{ optional($event->celebrePar)->nom }}
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('events.show', $event) }}" class="btn btn-info btn-sm me-1" title="Voir">
                                                <i class="flaticon-381-view"></i>
                                            </a>
                                            @can('edit_events')
                                            <a href="{{ route('events.edit', $event) }}" class="btn btn-warning btn-sm me-1" title="Modifier">
                                                Modifier
                                            </a>
                                            @endcan
                                            @can('delete_events')
                                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')"
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
                    {{ $events->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="flaticon-381-calendar-1" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                    <h5 class="text-muted">Aucun événement trouvé</h5>
                    <p class="text-muted">Commencez par ajouter votre premier événement.</p>
                    @can('create_events')
                    <a href="{{ route('events.create') }}" class="btn btn-citron mt-3">
                        Ajouter un événement
                    </a>
                    @endcan
                </div>
                @endif
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

