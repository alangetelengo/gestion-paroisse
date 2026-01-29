@extends('layouts.app')

@section('title', 'Membres')
@section('page-title', 'Gestion des membres')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-user me-2"></i>
                    Liste des membres
                </h4>
                <div class="card-action">
                    <a href="{{ route('members.index') }}" class="btn btn-secondary me-2">
                        Rafraîchir
                    </a>
                    @can('create_members')
                    <a href="{{ route('members.create') }}" class="btn btn-citron" style="font-weight: 600; padding: 10px 24px;">
                        Ajouter un membre
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                {{-- Filtres (requête BD) --}}
                <form method="GET" action="{{ route('members.index') }}" class="mb-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-control">
                                <option value="">Tous</option>
                                @foreach(['actif' => 'Actif', 'inactif' => 'Inactif', 'décédé' => 'Décédé'] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('statut') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sexe</label>
                            <select name="sexe" class="form-control">
                                <option value="">Tous</option>
                                <option value="M" @selected(request('sexe') === 'M')>Masculin</option>
                                <option value="F" @selected(request('sexe') === 'F')>Féminin</option>
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
                            <button class="btn btn-primary w-100" type="submit" style="margin-top: 24px;">Appliquer les filtres</button>
                        </div>
                    </div>
                </form>

                {{-- Recherche locale (sur les lignes visibles) --}}
                <div class="row mb-3">
                    <div class="col-md-4 ms-auto">
                        <label class="form-label">Recherche dans la liste</label>
                        <input type="text" id="members-local-search" class="form-control" placeholder="Filtrer les membres visibles...">
                    </div>
                </div>

                @if($members->count() > 0)
                <div class="table-responsive">
                    <table id="members-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Sexe</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Statut</th>
                                <th>Paroisse</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3" style="width: 40px; height: 40px; border-radius: 50%; background: var(--rgba-primary-1); display: flex; align-items: center; justify-content: center;">
                                                <span style="color: var(--primary); font-weight: 600;">{{ strtoupper(substr($member->prenom, 0, 1)) }}</span>
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
                                        <span class="badge {{ $badgeClass }}">{{ $member->statut }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $member->paroisse?->nom ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('members.show', $member) }}" class="btn btn-info btn-sm me-1" title="Voir">
                                                <i class="flaticon-381-view"></i>
                                            </a>
                                            @can('edit_members')
                                            <a href="{{ route('members.edit', $member) }}" class="btn btn-warning btn-sm me-1" title="Modifier">
                                                Modifier
                                            </a>
                                            @endcan
                                            @can('delete_members')
                                            <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')"
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
                    {{ $members->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="flaticon-381-user" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                    <h5 class="text-muted">Aucun membre trouvé</h5>
                    <p class="text-muted">Commencez par ajouter votre premier membre.</p>
                    @can('create_members')
                    <a href="{{ route('members.create') }}" class="btn btn-citron mt-3">
                        Ajouter un membre
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
