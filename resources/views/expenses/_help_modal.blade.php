{{-- Modal Aide formulaire Dépense (HTML dialog + Tailwind) --}}
<dialog id="expenseHelpModal" class="max-w-2xl w-[calc(100%-2rem)] rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-2xl p-0 backdrop:bg-slate-900/50">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-600 flex items-start justify-between gap-4">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white m-0 flex items-center gap-2" id="expenseHelpModalLabel">
            <i class="fas fa-info-circle text-emerald-600 dark:text-emerald-400" aria-hidden="true"></i>
            Aide : comment enregistrer une dépense ?
        </h2>
        <button type="button" class="shrink-0 rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700" onclick="document.getElementById('expenseHelpModal').close()" aria-label="Fermer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
    <div class="px-6 py-4 max-h-[min(70vh,28rem)] overflow-y-auto text-sm text-slate-600 dark:text-slate-300">
        <p class="text-slate-500 dark:text-slate-400 mb-3">Choisissez d’abord la <strong class="text-slate-800 dark:text-slate-100">catégorie de charge</strong> selon le type de dépense :</p>
        <ul class="list-disc list-outside pl-5 space-y-2 m-0">
            <li><strong class="text-slate-800 dark:text-slate-100">Charge fixe</strong> — Dépenses récurrentes (électricité, eau, gaz, internet, gardiennage, salaire ouvrier, carburant, hosties, maintenance, etc.). Ces dépenses ne sont pas déduites des recettes ; un rapport mensuel/annuel permet d’informer la hiérarchie.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Charge variable</strong> — Dépenses qui varient selon l’activité.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Charge exceptionnelle</strong> — Dépenses ponctuelles ou non récurrentes.</li>
            <li><strong class="text-slate-800 dark:text-slate-100">Alimentation (Subvention Popote)</strong> — Dépenses d’alimentation de la paroisse financées par la subvention popote. À choisir pour les achats de nourriture (riz, huile, légumes, etc.). Le formulaire affiche alors : date, jour, libellé de l’alimentation achetée et montant. Un rapport mensuel/annuel compare la subvention reçue à ces dépenses.</li>
        </ul>
        <p class="text-slate-500 dark:text-slate-400 mt-4 mb-0">
            <i class="fas fa-lightbulb text-amber-500 mr-1" aria-hidden="true"></i>
            Pour les charges fixes, variables ou exceptionnelles : renseignez le type de charge, la référence facture et le fournisseur si besoin. Pour l’alimentation popote : indiquez le libellé de l’article acheté.
        </p>
    </div>
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-600 flex justify-end">
        <button type="button" class="adventiste-btn-primary" onclick="document.getElementById('expenseHelpModal').close()">J’ai compris</button>
    </div>
</dialog>
