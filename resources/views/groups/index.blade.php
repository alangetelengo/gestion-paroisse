@extends('layouts.app')

@section('title', 'Groupes')
@section('page-title', 'Gestion des groupes')

@section('btn-create')
<div class="flex flex-wrap items-center gap-2">
    <a href="{{ route('groups.index') }}" class="adventiste-btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
        Rafraîchir
    </a>
    @can('create_groups')
    <a href="{{ route('groups.create') }}" class="adventiste-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Ajouter un groupe
    </a>
    @endcan
</div>
@endsection

@section('content-container-class', 'max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8')

@section('content')
<div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden mb-6">
    <form method="GET" action="{{ route('groups.index') }}" class="px-6 py-4 flex flex-wrap items-end gap-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40">
        <div class="min-w-36">
            <label for="gr_type" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Type</label>
            <select name="type" id="gr_type" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                <option value="">Tous</option>
                @foreach(['chorale' => 'Chorale', 'catéchisme' => 'Catéchisme', 'mouvement' => 'Mouvement', 'autre' => 'Autre'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        @if(isset($paroisses) && $paroisses->count() > 0)
        <div class="min-w-48">
            <label for="gr_paroisse" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Paroisse</label>
            <select name="paroisse_id" id="gr_paroisse" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                <option value="">Toutes</option>
                @foreach($paroisses as $paroisse)
                    <option value="{{ $paroisse->id }}" @selected((string) request('paroisse_id') === (string) $paroisse->id)>{{ $paroisse->nom }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="min-w-48 flex-1 max-w-md">
            <label for="gr_q" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Recherche</label>
            <input type="text" name="q" id="gr_q" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35" value="{{ request('q') }}" placeholder="Nom, description...">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="adventiste-btn-primary">Filtrer</button>
        </div>
    </form>

    @if($groups->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-linear-to-r from-slate-50 to-slate-100/80 dark:from-slate-700/80 dark:to-slate-800/80 border-b-2 border-slate-200 dark:border-slate-600">
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Nom</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Paroisse</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Responsable</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/80 text-slate-800 dark:text-slate-100">
                @foreach($groups as $group)
                <tr class="hover:bg-emerald-50/50 dark:hover:bg-slate-700/40 transition-colors duration-200">
                    <td class="px-6 py-4 font-medium">{{ $group->nom }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex rounded-lg bg-emerald-500/10 text-emerald-800 dark:text-emerald-200 px-2 py-0.5 text-xs font-medium">{{ ucfirst($group->type) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex rounded-lg bg-sky-500/10 text-sky-800 dark:text-sky-200 px-2 py-0.5 text-xs font-medium">{{ $group->paroisse?->nom ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $group->responsable?->prenom }} {{ $group->responsable?->nom }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex flex-wrap items-center justify-end gap-1.5" role="group" aria-label="Actions">
                            @can('edit_groups')
                            <x-action-button variant="edit" href="{{ route('groups.edit', $group) }}" />
                            @endcan
                            @can('delete_groups')
                            <x-action-button variant="delete" action="{{ route('groups.destroy', $group) }}" method="DELETE" confirm-message="Êtes-vous sûr de vouloir supprimer ce groupe ?" />
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($groups->hasPages())
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900/40 flex justify-center">
        {{ $groups->withQueryString()->links() }}
    </div>
    @endif
    @else
    <div class="px-6 py-16 text-center">
        <i class="fas fa-users text-5xl text-slate-200 dark:text-slate-600 mb-4 block" aria-hidden="true"></i>
        <h3 class="text-slate-600 dark:text-slate-400 font-medium mb-2">Aucun groupe trouvé</h3>
        <p class="text-sm text-slate-500 dark:text-slate-500 mb-6">Commencez par ajouter votre premier groupe (chorale, mouvement, catéchisme...).</p>
        @can('create_groups')
        <a href="{{ route('groups.create') }}" class="adventiste-btn-primary inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Ajouter un groupe
        </a>
        @endcan
    </div>
    @endif
</div>
@endsection
