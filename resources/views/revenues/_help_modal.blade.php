{{-- Modal Aide formulaire Recette (HTML dialog + Tailwind) --}}
<dialog id="revenueHelpModal" class="max-w-2xl w-[calc(100%-2rem)] rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-2xl p-0 backdrop:bg-slate-900/50">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-600 flex items-start justify-between gap-4">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0 flex items-center gap-2" id="revenueHelpModalLabel">
            <i class="fas fa-info-circle text-emerald-600 dark:text-emerald-400" aria-hidden="true"></i>
            Aide : comment enregistrer une recette ?
        </h2>
        <button type="button" class="shrink-0 rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700" onclick="document.getElementById('revenueHelpModal').close()" aria-label="Fermer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
    <div class="px-6 py-4 max-h-[min(70vh,28rem)] overflow-y-auto text-sm text-slate-600 dark:text-slate-300">
        <ul class="list-none m-0 p-0 space-y-2">
            <li><strong class="text-slate-800 dark:text-slate-100">Date de la recette</strong> — Date à laquelle la recette a été perçue.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Catégorie / Type</strong> — Choisissez d’abord la catégorie (ex. Quête ordinaire, Location, Dons…), puis le type de recette. Les types proposés dépendent de la catégorie.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Jour de la semaine</strong> — Affiché automatiquement pour la quête ordinaire selon la date (ex. dimanche). Lecture seule.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Mois de paiement du loyer</strong> — Pour les loyers de boutique : indiquez le mois et l’année concernés par ce paiement.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Montant</strong> — Saisissez le montant en {{ \App\Helpers\ParoisseConfig::get(null, 'monnaie', 'FCFA') }}. Les séparateurs de milliers sont ajoutés automatiquement.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Méthode de paiement</strong> — Espèces, chèque, virement, carte, mobile money.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Référence paiement</strong> — Générée automatiquement ; vous pouvez la laisser telle quelle sauf besoin particulier.</li>
            <li class="mb-0"><strong class="text-slate-800 dark:text-slate-100">Notes</strong> — Optionnel : précisions (origine du don, nom du donateur, etc.).</li>
        </ul>
    </div>
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-600 flex justify-end">
        <button type="button" class="adventiste-btn-primary" onclick="document.getElementById('revenueHelpModal').close()">J’ai compris</button>
    </div>
</dialog>
