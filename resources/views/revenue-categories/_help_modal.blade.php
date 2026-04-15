{{-- Modal Aide formulaire Catégorie de recettes (HTML dialog + Tailwind) --}}
<dialog id="revenueCategoryHelpModal" class="max-w-2xl w-[calc(100%-2rem)] rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-2xl p-0 backdrop:bg-slate-900/50">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-600 flex items-start justify-between gap-4">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0 flex items-center gap-2" id="revenueCategoryHelpModalLabel">
            <i class="fas fa-info-circle text-emerald-600 dark:text-emerald-400" aria-hidden="true"></i>
            Aide : catégorie de recettes
        </h2>
        <button type="button" class="shrink-0 rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700" onclick="document.getElementById('revenueCategoryHelpModal').close()" aria-label="Fermer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
    <div class="px-6 py-4 max-h-[min(70vh,28rem)] overflow-y-auto text-sm text-slate-600 dark:text-slate-300">
        <ul class="list-none m-0 p-0 space-y-2">
            <li><strong class="text-slate-800 dark:text-slate-100">Code</strong> — Identifiant technique unique (ex. quete_ordinaire, location, dons). Sert au tri et aux rapports.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Nom</strong> — Libellé affiché dans les listes et formulaires (ex. Quête ordinaire, Location, Dons).</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Ordre</strong> — Numéro pour afficher les catégories dans l’ordre souhaité (0, 1, 2…). Plus le nombre est petit, plus la catégorie apparaît en haut.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Description</strong> — Optionnel : précisions sur l’usage de cette catégorie.</li>
            <li class="mb-0"><strong class="text-slate-800 dark:text-slate-100">Actif</strong> — Désactivez pour masquer la catégorie dans les listes sans supprimer les données déjà enregistrées.</li>
        </ul>
    </div>
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-600 flex justify-end">
        <button type="button" class="adventiste-btn-primary" onclick="document.getElementById('revenueCategoryHelpModal').close()">J’ai compris</button>
    </div>
</dialog>
