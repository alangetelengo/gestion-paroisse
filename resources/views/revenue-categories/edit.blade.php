@extends('layouts.app')

@section('title', 'Modifier la catégorie')
@section('page-title', 'Modifier la catégorie')

@section('content-container-class', 'max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8')

@section('content')
<div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0 flex items-center gap-2">
            <i class="fas fa-folder text-emerald-600 dark:text-emerald-400" aria-hidden="true"></i>
            Modifier la catégorie
        </h2>
        <button type="button" class="adventiste-btn-secondary text-xs py-1.5" onclick="document.getElementById('revenueCategoryHelpModal').showModal()" title="Aide">
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

        <form action="{{ route('revenue-categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Code</label>
                    <input type="text" value="{{ $category->code }}" disabled class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-900/50 px-3 py-2 text-sm text-slate-600 dark:text-slate-400 cursor-not-allowed">
                </div>
                <div>
                    <label for="nom_edit_cat" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nom <span class="text-rose-600 dark:text-rose-400">*</span></label>
                    <input type="text" name="nom" id="nom_edit_cat" value="{{ old('nom', $category->nom) }}" required class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                </div>
                <div>
                    <label for="ordre_edit_cat" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Ordre</label>
                    <input type="number" name="ordre" id="ordre_edit_cat" value="{{ old('ordre', $category->ordre) }}" min="0" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                </div>
            </div>
            <div>
                <label for="desc_edit_cat" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Description</label>
                <textarea name="description" id="desc_edit_cat" rows="2" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="actif" value="1" id="actif_edit_cat" @checked(old('actif', $category->actif)) class="rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500/35">
                <label for="actif_edit_cat" class="text-sm font-medium text-slate-700 dark:text-slate-300">Actif</label>
            </div>

            <div class="flex flex-wrap justify-end gap-2 pt-6 border-t border-slate-200 dark:border-slate-600">
                <a href="{{ route('revenue-categories.index', ['paroisse_id' => $category->paroisse_id]) }}" class="adventiste-btn-secondary no-underline">Annuler</a>
                <button type="submit" class="adventiste-btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@include('revenue-categories._help_modal')
@endsection
