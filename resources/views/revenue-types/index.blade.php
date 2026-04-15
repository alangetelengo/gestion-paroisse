@extends('layouts.app')

@section('title', 'Types de recettes')
@section('page-title', 'Types de recettes')

@section('btn-create')
<div class="flex flex-wrap items-center gap-2">
    <a href="{{ route('revenue-types.index', request()->only(['paroisse_id', 'revenue_category_id'])) }}" class="adventiste-btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
        Rafraîchir
    </a>
    @can('create_revenues')
    <a href="{{ route('revenue-types.create', $paroisseId ? ['paroisse_id' => $paroisseId] : []) }}" class="adventiste-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Ajouter un type
    </a>
    @endcan
</div>
@endsection

@section('content-container-class', 'max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8')

@section('content')
<div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden mb-6">
    <form method="GET" action="{{ route('revenue-types.index') }}" class="px-6 py-4 flex flex-wrap items-end gap-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40">
        @if($paroisses->count() > 0)
        <div class="min-w-48">
            <label for="f_paroisse_rt" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Paroisse</label>
            <select name="paroisse_id" id="f_paroisse_rt" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                <option value="" @selected(empty($paroisseId))>Toutes les paroisses</option>
                @foreach($paroisses as $p)
                    <option value="{{ $p->id }}" @selected((int) $paroisseId === (int) $p->id)>{{ $p->nom }}</option>
                @endforeach
            </select>
        </div>
        @endif
        @if($categories->count() > 0)
        <div class="min-w-52">
            <label for="f_cat_rt" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Catégorie</label>
            <select name="revenue_category_id" id="f_cat_rt" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected(request('revenue_category_id') == $c->id)>{{ $c->nom }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </form>

    @if($types->count() > 0)
    <div class="px-6 py-3 border-b border-slate-100 dark:border-slate-700/80 flex justify-end">
        <input type="search" id="rt-search" placeholder="Filtrer les types…" class="w-full max-w-sm rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
    </div>
    <div class="overflow-x-auto">
        <table id="revenue-types-table" class="w-full text-sm">
            <thead>
                <tr class="bg-linear-to-r from-slate-50 to-slate-100/80 dark:from-slate-700/80 dark:to-slate-800/80 border-b-2 border-slate-200 dark:border-slate-600">
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Ordre</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Code</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Nom</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Catégorie</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Actif</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/80 text-slate-800 dark:text-slate-100">
                @foreach($types as $type)
                <tr class="group hover:bg-emerald-50/50 dark:hover:bg-slate-700/40 transition-colors duration-200 rt-row">
                    <td class="px-6 py-4 tabular-nums">{{ $type->ordre }}</td>
                    <td class="px-6 py-4"><code class="text-xs rounded-md bg-slate-100 dark:bg-slate-900 px-2 py-1">{{ $type->code }}</code></td>
                    <td class="px-6 py-4 font-medium">{{ $type->nom }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex rounded-lg bg-violet-500/10 text-violet-800 dark:text-violet-200 px-2 py-0.5 text-xs font-medium">{{ $type->category?->nom ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($type->actif)
                            <span class="inline-flex rounded-full bg-emerald-500/15 text-emerald-800 dark:text-emerald-200 px-2 py-0.5 text-xs font-semibold">Oui</span>
                        @else
                            <span class="inline-flex rounded-full bg-rose-500/15 text-rose-800 dark:text-rose-200 px-2 py-0.5 text-xs font-semibold">Non</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex flex-wrap items-center justify-end gap-1.5" role="group" aria-label="Actions">
                            @can('edit_revenues')
                            <x-action-button variant="edit" href="{{ route('revenue-types.edit', $type) }}" />
                            @endcan
                            @can('delete_revenues')
                            <x-action-button variant="delete" action="{{ route('revenue-types.destroy', $type) }}" method="DELETE" confirm-message="Supprimer ce type ?" />
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="px-6 py-16 text-center">
        <i class="fas fa-tags text-5xl text-slate-200 dark:text-slate-600 mb-4 block" aria-hidden="true"></i>
        <h3 class="text-slate-600 dark:text-slate-400 font-medium mb-2">Aucun type trouvé</h3>
        <p class="text-sm text-slate-500 dark:text-slate-500 mb-6">Choisissez une paroisse ou créez un nouveau type de recette.</p>
        @can('create_revenues')
        <a href="{{ route('revenue-types.create', $paroisseId ? ['paroisse_id' => $paroisseId] : []) }}" class="adventiste-btn-primary inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Ajouter un type
        </a>
        @endcan
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('rt-search');
    var table = document.getElementById('revenue-types-table');
    if (!input || !table) return;
    var rows = Array.from(table.querySelectorAll('tbody tr.rt-row'));
    input.addEventListener('input', function () {
        var q = this.value.toLowerCase();
        rows.forEach(function (row) {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
});
</script>
@endpush
