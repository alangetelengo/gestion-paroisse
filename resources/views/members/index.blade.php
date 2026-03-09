@extends('layouts.app')

@section('title', 'Membres')
@section('page-title', 'Gestion des membres')

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
.page-list .avatar-circle { width: 40px; height: 40px; border-radius: 50%; background: rgba(106, 27, 154, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary, #6A1B9A); font-weight: 700; font-size: 1rem; }
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
                    <i class="fas fa-user-friends me-3" style="font-size: 1.4rem; opacity: 0.9;"></i>
                    Liste des membres
                </h4>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('members.index') }}" class="btn btn-action btn-refresh">
                        <i class="fas fa-sync-alt"></i> Rafraîchir
                    </a>
                    @can('create_members')
                    <a href="{{ route('members.create') }}" class="btn btn-action btn-add">
                        <i class="fas fa-plus"></i> Ajouter un membre
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                {{-- Filtres --}}
                <div class="filters-card">
                    <form method="GET" action="{{ route('members.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-muted">Statut</label>
                                <select name="statut" class="form-control">
                                    <option value="">Tous</option>
                                    @foreach(['actif' => 'Actif', 'inactif' => 'Inactif', 'décédé' => 'Décédé'] as $value => $label)
                                        <option value="{{ $value }}" @selected(request('statut') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-muted">Sexe</label>
                                <select name="sexe" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="M" @selected(request('sexe') === 'M')>Masculin</option>
                                    <option value="F" @selected(request('sexe') === 'F')>Féminin</option>
                                </select>
                            </div>
                            @if(isset($paroisses) && $paroisses->count() > 0)
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
                        <input type="text" id="members-local-search" class="form-control" placeholder="Filtrer les membres visibles...">
                    </div>
                </div>

                @if($members->count() > 0)
                {{-- Tableau --}}
                <div class="table-responsive rounded overflow-hidden">
                    <table id="members-table" class="table table-list table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Sexe</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Statut</th>
                                <th>Paroisse</th>
                                <th class="text-center" style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            {{ strtoupper(substr($member->prenom, 0, 1)) }}
                                        </div>
                                        <strong>{{ $member->prenom }} {{ $member->nom }}</strong>
                                    </div>
                                </td>
                                <td>{{ $member->sexe === 'F' ? 'Féminin' : 'Masculin' }}</td>
                                <td>{{ $member->telephone ?? '—' }}</td>
                                <td>{{ $member->email ?? '—' }}</td>
                                <td>
                                    @php
                                        $badgeClass = match ($member->statut) {
                                            'actif' => 'badge-success',
                                            'inactif' => 'badge-warning',
                                            default => 'badge-danger',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($member->statut) }}</span>
                                </td>
                                <td><span class="badge badge-info">{{ $member->paroisse?->nom ?? 'N/A' }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('members.show', $member) }}" class="btn btn-view btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit_members')
                                        <a href="{{ route('members.edit', $member) }}" class="btn btn-edit btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        @endcan
                                        @can('delete_members')
                                        <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline" data-confirm="Êtes-vous sûr de vouloir supprimer ce membre ?">
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
                    {{ $members->withQueryString()->links() }}
                </div>
                @else
                <div class="empty-state text-center">
                    <i class="fas fa-user-friends empty-icon d-block"></i>
                    <h5 class="text-muted mb-2">Aucun membre trouvé</h5>
                    <p class="text-muted mb-4">Commencez par ajouter votre premier membre de la paroisse.</p>
                    @can('create_members')
                    <a href="{{ route('members.create') }}" class="btn btn-add btn-action">
                        <i class="fas fa-plus"></i> Ajouter un membre
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
        const table = document.getElementById('members-table');
        const searchInput = document.getElementById('members-local-search');
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
