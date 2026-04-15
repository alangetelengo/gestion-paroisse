{{-- Modal Aide formulaire Type de recette (HTML dialog + Tailwind) --}}
<dialog id="revenueTypeHelpModal" class="max-w-2xl w-[calc(100%-2rem)] rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-2xl p-0 backdrop:bg-slate-900/50">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-600 flex items-start justify-between gap-4">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0 flex items-center gap-2" id="revenueTypeHelpModalLabel">
            <i class="fas fa-info-circle text-emerald-600 dark:text-emerald-400" aria-hidden="true"></i>
            Aide : type de recette
        </h2>
        <button type="button" class="shrink-0 rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700" onclick="document.getElementById('revenueTypeHelpModal').close()" aria-label="Fermer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
    <div class="px-6 py-4 max-h-[min(70vh,28rem)] overflow-y-auto text-sm text-slate-600 dark:text-slate-300">
        <ul class="list-none m-0 p-0 space-y-2">
            <li><strong class="text-slate-800 dark:text-slate-100">Catégorie</strong> — La catégorie à laquelle ce type appartient (ex. Quête ordinaire, Location). Les types sont regroupés par catégorie dans le formulaire de saisie des recettes.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Code</strong> — Identifiant technique unique (ex. messe_dimanche, boutique_1). Sert aux rapports et au calcul automatique (ex. jour de la quête).</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Nom</strong> — Libellé affiché (ex. Messe dimanche, Boutique 1).</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Ordre</strong> — Numéro pour afficher les types dans l’ordre souhaité au sein de la catégorie.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Description</strong> — Optionnel : précisions sur ce type de recette.</li>
            <li class="mb-0"><strong class="text-slate-800 dark:text-slate-100">Actif</strong> — Désactivez pour masquer le type dans les listes sans supprimer l’historique.</li>
        </ul>
    </div>
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-600 flex justify-end">
        <button type="button" class="adventiste-btn-primary" onclick="document.getElementById('revenueTypeHelpModal').close()">J’ai compris</button>
    </div>
</dialog>
