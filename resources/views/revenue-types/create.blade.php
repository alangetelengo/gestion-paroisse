@extends('layouts.app')

@section('title', 'Ajouter un type de recette')
@section('page-title', 'Ajouter un type de recette')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Accueil</a></li>
    <li class="breadcrumb-item"><a href="{{ route('revenue-types.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Types de recettes</a></li>
    <li class="breadcrumb-item active text-slate-500 dark:text-slate-400" aria-current="page">Ajouter</li>
@endsection

@section('content-container-class', 'max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <div class="lg:col-span-9">
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0 flex items-center gap-2">
                    <i class="fas fa-tags text-emerald-600 dark:text-emerald-400" aria-hidden="true"></i>
                    Nouveau type de recette
                </h2>
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

                <form action="{{ route('revenue-types.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @if(auth()->user()->hasRole('super_admin') && $paroisses->count() > 0)
                    <div>
                        <label for="paroisse_id_rt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Paroisse <span class="text-rose-600 dark:text-rose-400">*</span></label>
                        <select name="paroisse_id" id="paroisse_id_rt" required class="w-full max-w-md rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                            @foreach($paroisses as $p)
                                <option value="{{ $p->id }}" @selected(old('paroisse_id', $paroisseId) == $p->id)>{{ $p->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="paroisse_id" value="{{ auth()->user()->paroisse_id }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="revenue_category_id_rt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Catégorie <span class="text-rose-600 dark:text-rose-400">*</span></label>
                            <select name="revenue_category_id" id="revenue_category_id_rt" required class="w-full max-w-md rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                                <option value="">— Choisir —</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" @selected(old('revenue_category_id') == $c->id)>{{ $c->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="code_rt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Code <span class="text-rose-600 dark:text-rose-400">*</span></label>
                            <input type="text" name="code" id="code_rt" value="{{ old('code') }}" required placeholder="ex: messe_dimanche" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                        </div>
                        <div>
                            <label for="nom_rt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nom <span class="text-rose-600 dark:text-rose-400">*</span></label>
                            <input type="text" name="nom" id="nom_rt" value="{{ old('nom') }}" required class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                        </div>
                        <div>
                            <label for="ordre_rt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Ordre</label>
                            <input type="number" name="ordre" id="ordre_rt" value="{{ old('ordre', 0) }}" min="0" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                        </div>
                    </div>
                    <div>
                        <label for="desc_rt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Description</label>
                        <textarea name="description" id="desc_rt" rows="2" class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">{{ old('description') }}</textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="actif" value="1" id="actif_rt" @checked(old('actif', true)) class="rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500/35">
                        <label for="actif_rt" class="text-sm font-medium text-slate-700 dark:text-slate-300">Actif</label>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2 pt-6 border-t border-slate-200 dark:border-slate-600">
                        <a href="{{ route('revenue-types.index', array_filter(['paroisse_id' => $paroisseId])) }}" class="adventiste-btn-secondary no-underline">Annuler</a>
                        <button type="submit" class="adventiste-btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="lg:col-span-3">
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                <i class="fas fa-info-circle text-emerald-600 dark:text-emerald-400" aria-hidden="true"></i>
                En bref
            </h3>
            <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2 m-0 pl-4 list-disc">
                <li>Choisir la <strong>catégorie</strong>, puis saisir <strong>code</strong> et <strong>nom</strong>.</li>
                <li>Ordre et description sont optionnels.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
