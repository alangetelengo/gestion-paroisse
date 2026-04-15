@extends('layouts.app')

@section('title', 'Recettes')
@section('page-title', 'Gestion des recettes')

@section('btn-create')
<div class="flex flex-wrap items-center gap-2">
    <a href="{{ route('revenues.index') }}" class="adventiste-btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
        Rafraîchir
    </a>
    @can('create_revenues')
    <a href="{{ route('revenues.create') }}" class="adventiste-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Ajouter une recette
    </a>
    @endcan
</div>
@endsection

@section('content-container-class', 'max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8')

@section('content')
<div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden mb-6">
    <form method="GET" action="{{ route('revenues.index') }}" class="px-6 py-4 flex flex-wrap items-end gap-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40">
        <div class="min-w-36">
            <label for="f_cat" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Catégorie</label>
            <select name="categorie" id="f_cat" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                <option value="">Toutes</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->code }}" @selected(request('categorie') === $categorie->code)>{{ $categorie->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="min-w-36">
            <label for="f_df" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Date du</label>
            <input type="date" name="date_from" id="f_df" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35" value="{{ request('date_from') }}">
        </div>
        <div class="min-w-36">
            <label for="f_dt" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Au</label>
            <input type="date" name="date_to" id="f_dt" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35" value="{{ request('date_to') }}">
        </div>
        @if(isset($paroisses) && $paroisses->count() > 0)
        <div class="min-w-44">
            <label for="f_p" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Paroisse</label>
            <select name="paroisse_id" id="f_p" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                <option value="">Toutes</option>
                @foreach($paroisses as $paroisse)
                    <option value="{{ $paroisse->id }}" @selected((string) request('paroisse_id') === (string) $paroisse->id)>{{ $paroisse->nom }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="flex gap-2">
            <button type="submit" class="adventiste-btn-primary">Filtrer</button>
            @if (request()->hasAny(['categorie', 'date_from', 'date_to', 'paroisse_id', 'q']))
            <a href="{{ route('revenues.index') }}" class="adventiste-btn-secondary">Réinitialiser</a>
            @endif
        </div>
    </form>

    @if($revenues->count() > 0)
    <div class="px-6 py-3 border-b border-slate-100 dark:border-slate-700/80 flex justify-end">
        <form method="GET" action="{{ route('revenues.index') }}" class="flex w-full max-w-md gap-2" role="search">
            <input type="hidden" name="categorie" value="{{ request('categorie') }}">
            <input type="hidden" name="date_from" value="{{ request('date_from') }}">
            <input type="hidden" name="date_to" value="{{ request('date_to') }}">
            @if(isset($paroisses) && $paroisses->count() > 0)
            <input type="hidden" name="paroisse_id" value="{{ request('paroisse_id') }}">
            @endif
            <input type="search" name="q" class="flex-1 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/35" value="{{ request('q') }}" placeholder="Référence, notes…">
            <button type="submit" class="adventiste-btn-primary shrink-0" aria-label="Rechercher">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-linear-to-r from-slate-50 to-slate-100/80 dark:from-slate-700/80 dark:to-slate-800/80 border-b-2 border-slate-200 dark:border-slate-600">
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Catégorie</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Type</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Montant</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest hidden md:table-cell">Méthode</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/80 text-slate-800 dark:text-slate-100">
                @foreach($revenues as $revenue)
                <tr class="group hover:bg-emerald-50/50 dark:hover:bg-slate-700/40 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $revenue->date_recette?->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex rounded-lg px-2.5 py-0.5 text-xs font-medium bg-violet-500/10 text-violet-800 dark:text-violet-200 ring-1 ring-violet-500/20">{{ $revenue->category?->nom ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $revenue->type?->nom ?? '—' }}
                        @if($revenue->mois_location)
                            @php
                                $moisLabels = ['01'=>'Jan','02'=>'Fév','03'=>'Mars','04'=>'Avr','05'=>'Mai','06'=>'Juin','07'=>'Juil','08'=>'Août','09'=>'Sept','10'=>'Oct','11'=>'Nov','12'=>'Déc'];
                                $moisNum = substr($revenue->mois_location, 5, 2);
                                $annee = substr($revenue->mois_location, 0, 4);
                            @endphp
                            <span class="block text-xs text-slate-500 dark:text-slate-400 mt-0.5"><i class="fas fa-calendar-check mr-1" aria-hidden="true"></i>{{ $moisLabels[$moisNum] ?? $moisNum }} {{ $annee }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-emerald-700 dark:text-emerald-300 tabular-nums">{{ \App\Helpers\ParoisseConfig::formatMontant($revenue->montant) }}</td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        <span class="inline-flex rounded-md bg-slate-100 dark:bg-slate-700/80 px-2 py-0.5 text-xs text-slate-600 dark:text-slate-300">{{ ucfirst(str_replace('_', ' ', $revenue->methode_paiement)) }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex flex-wrap justify-end gap-1.5">
                            @can('edit_revenues')
                            <x-action-button variant="edit" href="{{ route('revenues.edit', $revenue) }}" />
                            @endcan
                            @can('delete_revenues')
                            <x-action-button variant="delete" action="{{ route('revenues.destroy', $revenue) }}" method="DELETE" confirm-message="Supprimer cette recette ?" />
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($revenues->hasPages())
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900/40 flex justify-center">
        {{ $revenues->withQueryString()->links() }}
    </div>
    @endif
    @else
    <div class="px-6 py-16 text-center">
        <i class="fas fa-calculator text-5xl text-slate-200 dark:text-slate-600 mb-4 block" aria-hidden="true"></i>
        <h3 class="text-slate-600 dark:text-slate-400 font-medium mb-2">Aucune recette trouvée</h3>
        <p class="text-sm text-slate-500 mb-6">Commencez par ajouter votre première recette pour enregistrer les quêtes et autres revenus.</p>
        @can('create_revenues')
        <a href="{{ route('revenues.create') }}" class="adventiste-btn-primary inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Ajouter une recette
        </a>
        @endcan
    </div>
    @endif
</div>
@endsection
