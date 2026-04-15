@extends('layouts.app')

@section('title', 'Modifier le type')
@section('page-title', 'Modifier le type')

@section('content-container-class', 'max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8')

@section('content')
<div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0 flex items-center gap-2">
            <i class="fas fa-tags text-emerald-600 dark:text-emerald-400" aria-hidden="true"></i>
            Modifier le type
        </h2>
        <button type="button" class="adventiste-btn-secondary text-xs py-1.5" onclick="document.getElementById('revenueTypeHelpModal').showModal()" title="Aide">
            <i class="fas fa-info-circle" aria-hidden="true"></i>
            Aide
        </button>
    </div>
    <div class="p-6">
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 dark:border-rose-800 bg-rose-50/80 dark:bg-rose-950/30 px-4 py-3 text-sm text-rose-800 dark:text-rose-200">
                <p class="font-semibold mb-2">Erreurs de validation</p>
                <ul class="list-disc list-inside m-0 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('revenue-types.update', $type) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="code_edit_rt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Code <span class="text-rose-600 dark:text-rose-400">*</span></label>
                    <input type="text" name="code" id="code_edit_rt" value="{{ old('code', $type->code) }}" required placeholder="ex: messe_dimanche" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                </div>
                <div>
                    <label for="cat_edit_rt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Catégorie <span class="text-rose-600 dark:text-rose-400">*</span></label>
                    <select name="revenue_category_id" id="cat_edit_rt" required class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" @selected((string) old('revenue_category_id', $type->revenue_category_id) === (string) $c->id)>{{ $c->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="nom_edit_rt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nom <span class="text-rose-600 dark:text-rose-400">*</span></label>
                    <input type="text" name="nom" id="nom_edit_rt" value="{{ old('nom', $type->nom) }}" required class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                </div>
                <div>
                    <label for="ordre_edit_rt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Ordre</label>
                    <input type="number" name="ordre" id="ordre_edit_rt" value="{{ old('ordre', $type->ordre) }}" min="0" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                </div>
            </div>
            <div>
                <label for="desc_edit_rt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Description</label>
                <textarea name="description" id="desc_edit_rt" rows="2" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">{{ old('description', $type->description) }}</textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="actif" value="1" id="actif_edit_rt" @checked(old('actif', $type->actif)) class="rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500/35">
                <label for="actif_edit_rt" class="text-sm font-medium text-slate-700 dark:text-slate-300">Actif</label>
            </div>

            <div class="flex flex-wrap justify-end gap-2 pt-6 border-t border-slate-200 dark:border-slate-600">
                <a href="{{ route('revenue-types.index', ['paroisse_id' => $type->paroisse_id]) }}" class="adventiste-btn-secondary no-underline">Annuler</a>
                <button type="submit" class="adventiste-btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@include('revenue-types._help_modal')
@endsection
