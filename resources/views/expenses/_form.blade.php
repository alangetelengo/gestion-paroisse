@php
    /** @var \App\Models\Expense|null $expense */
    $expense = $expense ?? null;
    $currency = \App\Helpers\ParoisseConfig::get(null, 'monnaie', 'FCFA');
    $inputBase = 'w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35';
    $inputErr = 'border-rose-500 dark:border-rose-500 ring-rose-500/25';
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @if(isset($paroisses) && $paroisses->count() > 0)
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="paroisse_id_exp">Paroisse</label>
            <select name="paroisse_id" id="paroisse_id_exp" class="{{ $inputBase }} @error('paroisse_id') {{ $inputErr }} @enderror">
                @foreach($paroisses as $paroisse)
                    <option value="{{ $paroisse->id }}"
                        @selected((string) old('paroisse_id', $expense?->paroisse_id) === (string) $paroisse->id)>
                        {{ $paroisse->nom }}
                    </option>
                @endforeach
            </select>
            @error('paroisse_id')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
        </div>
    @endif

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="date_depense">Date de la dépense <span class="text-rose-500">*</span></label>
        <input type="date" name="date_depense" id="date_depense"
               class="{{ $inputBase }} @error('date_depense') {{ $inputErr }} @enderror"
               value="{{ old('date_depense', $expense?->date_depense?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
               required>
        @error('date_depense')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="categorie-charge">Catégorie de charge <span class="text-rose-500">*</span></label>
        @php
            $categorie = old('categorie_charge', $expense?->categorie_charge ?? 'charge_fixe');
            $categories = [
                'charge_fixe' => 'Charge fixe',
                'charge_variable' => 'Charge variable',
                'charge_exceptionnelle' => 'Charge exceptionnelle',
                'alimentation_popote' => 'Alimentation (Subvention Popote)',
            ];
        @endphp
        <select name="categorie_charge" id="categorie-charge" class="{{ $inputBase }} @error('categorie_charge') {{ $inputErr }} @enderror" required>
            @foreach($categories as $value => $label)
                <option value="{{ $value }}" @selected($categorie === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('categorie_charge')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div id="type-charge-container">
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="type-charge">Type de charge <span class="text-rose-500">*</span></label>
        @php
            $type = old('type_charge', $expense?->type_charge ?? 'autre');
            $types = [
                'carburant' => 'Carburant', 'hosties' => 'Hosties', 'internet' => 'Internet',
                'maintenance_materiel' => 'Maintenance matériel', 'gaz' => 'Gaz', 'eau' => 'Eau',
                'electricite' => 'Électricité', 'gardiennage' => 'Gardiennage', 'salaire_ouvrier' => 'Salaire ouvrier',
                'autre' => 'Autre', 'alimentation' => 'Alimentation',
            ];
        @endphp
        <select name="type_charge" id="type-charge" class="{{ $inputBase }} @error('type_charge') {{ $inputErr }} @enderror">
            @foreach($types as $value => $label)
                <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('type_charge')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div id="jour-depense-container" style="display: none;">
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="jour-depense-display">Jour <span class="text-rose-500">*</span></label>
        @php
            $jourDep = old('jour_semaine', $expense?->jour_semaine);
            $joursLabels = ['lundi'=>'Lundi','mardi'=>'Mardi','mercredi'=>'Mercredi','jeudi'=>'Jeudi','vendredi'=>'Vendredi','samedi'=>'Samedi','dimanche'=>'Dimanche'];
        @endphp
        <input type="text" id="jour-depense-display" class="{{ $inputBase }} bg-slate-50 dark:bg-slate-900/50" value="{{ $jourDep ? ($joursLabels[$jourDep] ?? ucfirst($jourDep)) : '' }}" readonly placeholder="Calculé à partir de la date">
        <input type="hidden" name="jour_semaine" id="jour-depense" value="{{ $jourDep }}">
    </div>

    <div class="md:col-span-2" id="libelle-container" style="display: none;">
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="libelle">Libellé de l'alimentation achetée <span class="text-rose-500">*</span></label>
        <input type="text" name="libelle" id="libelle" class="{{ $inputBase }} @error('libelle') {{ $inputErr }} @enderror" value="{{ old('libelle', $expense?->libelle) }}" placeholder="Ex : Riz, huile, légumes...">
        @error('libelle')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2" id="piece-recu-alimentation-container" style="display: none;">
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="piece_recu_alimentation">Reçu justificatif (alimentation achetée)</label>
        <input type="file" id="piece_recu_alimentation" class="{{ $inputBase }} @error('piece_recu') {{ $inputErr }} @enderror file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950 dark:file:text-emerald-300" accept=".pdf,image/*">
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">PDF ou image (JPG, PNG). Optionnel mais recommandé pour justifier la dépense.</p>
        @error('piece_recu')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
        @if($expense?->piece_recu_path && $expense?->categorie_charge === 'alimentation_popote')
            <p class="mt-1 text-xs text-slate-500">Reçu actuel : <a href="{{ asset('storage/'.$expense->piece_recu_path) }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 underline">Voir le fichier</a></p>
        @endif
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="montant_exp">Montant ({{ $currency }}) <span class="text-rose-500">*</span></label>
        <input type="text" name="montant" id="montant_exp"
               class="{{ $inputBase }} @error('montant') {{ $inputErr }} @enderror"
               inputmode="decimal"
               value="{{ old('montant', \App\Helpers\ParoisseConfig::formatMontantSaisie($expense?->montant)) }}"
               required>
        @error('montant')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div id="bloc-charges" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="facture_reference">Référence facture</label>
            <input type="text" name="facture_reference" id="facture_reference" class="{{ $inputBase }} @error('facture_reference') {{ $inputErr }} @enderror" value="{{ old('facture_reference', $expense?->facture_reference) }}">
            @error('facture_reference')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="piece_facture">Pièce jointe - Facture (PDF / image)</label>
            <input type="file" name="piece_facture" id="piece_facture" class="{{ $inputBase }} @error('piece_facture') {{ $inputErr }} @enderror file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 dark:file:bg-slate-700 dark:file:text-slate-200" accept=".pdf,image/*">
            @error('piece_facture')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
            @if($expense?->piece_facture_path)
                <p class="mt-1 text-xs text-slate-500">Facture actuelle : <a href="{{ asset('storage/'.$expense->piece_facture_path) }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 underline">Voir le fichier</a></p>
            @endif
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="fournisseur">Fournisseur</label>
            <input type="text" name="fournisseur" id="fournisseur" class="{{ $inputBase }} @error('fournisseur') {{ $inputErr }} @enderror" data-transform="title" value="{{ old('fournisseur', $expense?->fournisseur) }}">
            @error('fournisseur')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="piece_recu_charges">Pièce jointe - Reçu de paiement (PDF / image)</label>
            <input type="file" name="piece_recu" id="piece_recu_charges" class="{{ $inputBase }} @error('piece_recu') {{ $inputErr }} @enderror file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 dark:file:bg-slate-700 dark:file:text-slate-200" accept=".pdf,image/*">
            @error('piece_recu')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
            @if($expense?->piece_recu_path)
                <p class="mt-1 text-xs text-slate-500">Reçu actuel : <a href="{{ asset('storage/'.$expense->piece_recu_path) }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 underline">Voir le fichier</a></p>
            @endif
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="methode_paiement_exp">Méthode de paiement <span class="text-rose-500">*</span></label>
        @php
            $methode = old('methode_paiement', $expense?->methode_paiement ?? 'especes');
            $methodes = [
                'especes' => 'Espèces', 'cheque' => 'Chèque', 'virement' => 'Virement',
                'carte' => 'Carte', 'mobile_money' => 'Mobile money',
            ];
        @endphp
        <select name="methode_paiement" id="methode_paiement_exp" class="{{ $inputBase }} @error('methode_paiement') {{ $inputErr }} @enderror" required>
            @foreach($methodes as $value => $label)
                <option value="{{ $value }}" @selected($methode === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('methode_paiement')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="notes_exp">Notes</label>
        <textarea name="notes" id="notes_exp" rows="3" class="{{ $inputBase }} @error('notes') {{ $inputErr }} @enderror">{{ old('notes', $expense?->notes) }}</textarea>
        @error('notes')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorieSelect = document.getElementById('categorie-charge');
        const typeChargeContainer = document.getElementById('type-charge-container');
        const typeChargeSelect = document.getElementById('type-charge');
        const jourContainer = document.getElementById('jour-depense-container');
        const jourDisplay = document.getElementById('jour-depense-display');
        const jourHidden = document.getElementById('jour-depense');
        const libelleContainer = document.getElementById('libelle-container');
        const libelleInput = document.getElementById('libelle');
        const blocCharges = document.getElementById('bloc-charges');
        const pieceRecuAlimentationContainer = document.getElementById('piece-recu-alimentation-container');
        const pieceRecuAlimentation = document.getElementById('piece_recu_alimentation');
        const pieceRecuCharges = document.getElementById('piece_recu_charges');
        const dateInput = document.querySelector('input[name="date_depense"]');
        const montantInput = document.querySelector('input[name="montant"]');

        const joursLabels = { 'lundi':'Lundi','mardi':'Mardi','mercredi':'Mercredi','jeudi':'Jeudi','vendredi':'Vendredi','samedi':'Samedi','dimanche':'Dimanche' };
        const weekdayMap = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'];

        function toggleAlimentationPopote() {
            const isPopote = categorieSelect && categorieSelect.value === 'alimentation_popote';
            if (typeChargeContainer) typeChargeContainer.style.display = isPopote ? 'none' : '';
            if (jourContainer) jourContainer.style.display = isPopote ? '' : 'none';
            if (libelleContainer) libelleContainer.style.display = isPopote ? '' : 'none';
            if (pieceRecuAlimentationContainer) pieceRecuAlimentationContainer.style.display = isPopote ? '' : 'none';
            if (blocCharges) blocCharges.style.display = isPopote ? 'none' : '';
            if (typeChargeSelect) typeChargeSelect.value = isPopote ? 'alimentation' : (typeChargeSelect.dataset.prev || 'autre');
            if (typeChargeSelect && !isPopote) typeChargeSelect.dataset.prev = typeChargeSelect.value;
            if (pieceRecuAlimentation && pieceRecuCharges) {
                if (isPopote) {
                    pieceRecuAlimentation.setAttribute('name', 'piece_recu');
                    pieceRecuCharges.removeAttribute('name');
                    pieceRecuCharges.value = '';
                } else {
                    pieceRecuAlimentation.removeAttribute('name');
                    pieceRecuAlimentation.value = '';
                    pieceRecuCharges.setAttribute('name', 'piece_recu');
                }
            }
            if (isPopote) syncJourFromDate();
            else {
                if (jourHidden) jourHidden.value = '';
                if (jourDisplay) jourDisplay.value = '';
                if (libelleInput) libelleInput.removeAttribute('required');
            }
            if (isPopote && libelleInput) libelleInput.setAttribute('required', 'required');
        }

        function syncJourFromDate() {
            if (!dateInput || !jourHidden || !jourDisplay || !jourContainer || jourContainer.style.display === 'none') return;
            const v = dateInput.value;
            if (!v) return;
            const d = new Date(v);
            if (isNaN(d)) return;
            const jour = weekdayMap[d.getDay()];
            jourHidden.value = jour;
            jourDisplay.value = joursLabels[jour] || jour;
        }

        if (categorieSelect) {
            categorieSelect.addEventListener('change', toggleAlimentationPopote);
            toggleAlimentationPopote();
        }
        if (dateInput && jourContainer) dateInput.addEventListener('change', syncJourFromDate);

        if (montantInput) {
            const form = montantInput.form;
            function formatMontant() {
                let value = montantInput.value.replace(/[^\d]/g, '');
                if (!value) { montantInput.value = ''; return; }
                const parts = [];
                while (value.length > 3) { parts.unshift(value.slice(-3)); value = value.slice(0, -3); }
                if (value.length) parts.unshift(value);
                montantInput.value = parts.join(' ');
            }
            montantInput.addEventListener('input', formatMontant);
            if (form) form.addEventListener('submit', function () {
                if (montantInput.value) montantInput.value = montantInput.value.replace(/\s/g, '');
            });
        }
    });
</script>
@endpush
