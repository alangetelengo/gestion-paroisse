@extends('layouts.app')

@section('title', 'Paroisses')
@section('page-title', 'Gestion des paroisses')

@section('btn-create')
<div class="flex flex-wrap items-center gap-2">
    <a href="{{ route('paroisses.index') }}" class="adventiste-btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
        Rafraîchir
    </a>
    @can('manage_paroisses')
    <a href="{{ route('paroisses.create') }}" class="adventiste-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Ajouter une paroisse
    </a>
    @endcan
</div>
@endsection

@section('content-container-class', 'max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8')

@section('content')
<div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
    @if($paroisses->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-linear-to-r from-slate-50 to-slate-100/80 dark:from-slate-700/80 dark:to-slate-800/80 border-b-2 border-slate-200 dark:border-slate-600">
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Nom</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest hidden sm:table-cell">Code</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest hidden md:table-cell">Ville</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest hidden lg:table-cell">Diocèse</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest hidden xl:table-cell">Curé</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Statut</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/80 text-slate-800 dark:text-slate-100">
                @foreach($paroisses as $paroisse)
                <tr class="group hover:bg-emerald-50/50 dark:hover:bg-slate-700/40 transition-colors duration-200">
                    <td class="px-6 py-4 font-medium">
                        <div class="flex items-center gap-3">
                            <span class="shrink-0 w-10 h-10 rounded-full bg-violet-500/15 text-violet-700 dark:text-violet-300 flex items-center justify-center" aria-hidden="true"><i class="fas fa-church"></i></span>
                            {{ $paroisse->nom }}
                        </div>
                    </td>
                    <td class="px-6 py-4 hidden sm:table-cell"><code class="text-xs rounded-md bg-slate-100 dark:bg-slate-900 px-2 py-1">{{ $paroisse->code_paroisse ?? 'N/A' }}</code></td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 hidden md:table-cell">{{ $paroisse->ville ?? 'N/A' }}</td>
                    <td class="px-6 py-4 hidden lg:table-cell">
                        <span class="inline-flex rounded-lg bg-indigo-500/10 text-indigo-800 dark:text-indigo-200 px-2 py-0.5 text-xs font-medium">{{ $paroisse->diocèse ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 hidden xl:table-cell">
                        @if($paroisse->curé)
                            {{ $paroisse->curé->nom }} {{ $paroisse->curé->prenom }}
                        @else
                            <span class="text-slate-400">Non assigné</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($paroisse->actif)
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/15 text-emerald-800 dark:text-emerald-200 px-2.5 py-0.5 text-xs font-semibold ring-1 ring-emerald-500/25">Actif</span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-500/15 text-rose-800 dark:text-rose-200 px-2.5 py-0.5 text-xs font-semibold ring-1 ring-rose-500/25">Inactif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex flex-wrap items-center justify-end gap-1.5" role="group" aria-label="Actions">
                            <x-action-button variant="view" href="{{ route('paroisses.show', $paroisse) }}" />
                            @can('manage_paroisses')
                            <x-action-button variant="edit" href="{{ route('paroisses.edit', $paroisse) }}" />
                            <x-action-button variant="delete" action="{{ route('paroisses.destroy', $paroisse) }}" method="DELETE" confirm-message="Êtes-vous sûr de vouloir désactiver cette paroisse ?" confirm-text="Confirmer" />
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($paroisses->hasPages())
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900/40 flex justify-center">
        {{ $paroisses->links() }}
    </div>
    @endif
    @else
    <div class="px-6 py-16 text-center">
        <i class="fas fa-church text-5xl text-slate-200 dark:text-slate-600 mb-4 block" aria-hidden="true"></i>
        <h3 class="text-slate-600 dark:text-slate-400 font-medium mb-2">Aucune paroisse trouvée</h3>
        <p class="text-sm text-slate-500 dark:text-slate-500 mb-6">Commencez par ajouter votre première paroisse.</p>
        @can('manage_paroisses')
        <a href="{{ route('paroisses.create') }}" class="adventiste-btn-primary inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Ajouter une paroisse
        </a>
        @endcan
    </div>
    @endif
</div>
@endsection
