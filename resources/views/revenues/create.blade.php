@extends('layouts.app')

@section('title', 'Ajouter une recette')
@section('page-title', 'Ajouter une recette')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Accueil</a></li>
    <li class="breadcrumb-item"><a href="{{ route('revenues.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Recettes</a></li>
    <li class="breadcrumb-item active text-slate-500 dark:text-slate-400" aria-current="page">Ajouter</li>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <div class="lg:col-span-9">
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-600/60 bg-slate-50/80 dark:bg-slate-900/40">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0 flex items-center gap-2">
                    <i class="fas fa-calculator text-emerald-600 dark:text-emerald-400" aria-hidden="true"></i>
                    Nouvelle recette
                </h2>
            </div>
            <div class="p-6">
                <div id="offline-create-notice" class="mb-6 rounded-xl border border-amber-200 dark:border-amber-800 bg-amber-50/80 dark:bg-amber-950/30 px-4 py-3 text-sm text-amber-900 dark:text-amber-100" style="display: none;">
                    <i class="fas fa-wifi mr-1" aria-hidden="true"></i> <strong>Mode hors ligne.</strong> La recette sera enregistrée localement et synchronisée automatiquement dès que la connexion sera rétablie.
                </div>
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

                <form action="{{ route('revenues.store') }}" method="POST" data-offline-sync="revenue">
                    @csrf
                    @include('revenues._form')
                    <div class="flex flex-wrap justify-end gap-2 mt-8 pt-6 border-t border-slate-200 dark:border-slate-600">
                        <a href="{{ route('revenues.index') }}" class="adventiste-btn-secondary no-underline">Annuler</a>
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
                <li>Choisir la <strong>catégorie</strong>, puis le <strong>type</strong>.</li>
                <li>Saisir la <strong>date</strong> et le <strong>montant</strong>.</li>
                <li>Les autres champs se remplissent ou sont optionnels.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
