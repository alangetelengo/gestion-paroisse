@php
    /** @var \App\Models\Revenue|null $revenue */
    $revenue = $revenue ?? null;
    $currency = \App\Helpers\ParoisseConfig::get(null, 'monnaie', 'FCFA');
@endphp

@php
    $inputBase = 'w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35';
    $inputErr = 'border-rose-500 dark:border-rose-500 ring-rose-500/25';
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @if(isset($paroisses) && $paroisses->count() > 0)
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="paroisse_id_rev">Paroisse</label>
            <select name="paroisse_id" id="paroisse_id_rev" class="{{ $inputBase }} @error('paroisse_id') {{ $inputErr }} @enderror">
                @foreach($paroisses as $paroisse)
                    <option value="{{ $paroisse->id }}"
                        @selected((string) old('paroisse_id', $revenue?->paroisse_id) === (string) $paroisse->id)>
                        {{ $paroisse->nom }}
                    </option>
                @endforeach
            </select>
            @error('paroisse_id')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
        </div>
    @endif

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="date_recette">Date de la recette <span class="text-rose-500">*</span></label>
        <input type="date" name="date_recette" id="date_recette"
               class="{{ $inputBase }} @error('date_recette') {{ $inputErr }} @enderror"
               value="{{ old('date_recette', $revenue?->date_recette?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
               required>
        @error('date_recette')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="revenue-category">Catégorie <span class="text-rose-500">*</span></label>
        <select name="revenue_category_id" id="revenue-category" class="{{ $inputBase }} @error('revenue_category_id') {{ $inputErr }} @enderror" required>
            <option value="">Sélectionner...</option>
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}"
                    data-category-code="{{ $categorie->code }}"
                    @selected((int) old('revenue_category_id', $revenue?->revenue_category_id) === $categorie->id)>
                    {{ $categorie->nom }}
                </option>
            @endforeach
        </select>
        @error('revenue_category_id')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="revenue-type">Type de recette <span class="text-rose-500">*</span></label>
        <select name="revenue_type_id" id="revenue-type" class="{{ $inputBase }} @error('revenue_type_id') {{ $inputErr }} @enderror" required>
            <option value="">Sélectionner...</option>
            @foreach($categories as $categorie)
                @foreach($categorie->types as $type)
                    <option value="{{ $type->id }}"
                        data-category="{{ $categorie->id }}"
                        data-category-code="{{ $categorie->code }}"
                        data-code="{{ $type->code }}"
                        @selected((int) old('revenue_type_id', $revenue?->revenue_type_id) === $type->id)>
                        {{ $categorie->nom }} — {{ $type->nom }}
                    </option>
                @endforeach
            @endforeach
        </select>
        @error('revenue_type_id')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2 hidden" id="donateur-container">
        <div class="rounded-2xl border border-emerald-200/80 dark:border-emerald-800/60 bg-emerald-50/40 dark:bg-emerald-950/20 overflow-hidden">
            <div class="px-4 py-3 border-b border-emerald-200/60 dark:border-emerald-800/50 bg-emerald-50/80 dark:bg-emerald-950/40 text-sm font-semibold text-emerald-900 dark:text-emerald-100">
                <i class="fas fa-user mr-1" aria-hidden="true"></i> Informations de la personne (dîme / don)
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="donateur_nom">Nom du donateur</label>
                    <input type="text" name="donateur_nom" id="donateur_nom"
                           class="{{ $inputBase }} uppercase @error('donateur_nom') {{ $inputErr }} @enderror"
                           style="text-transform: uppercase;"
                           value="{{ old('donateur_nom', $revenue?->donateur_nom) }}"
                           placeholder="Nom et prénom (en majuscules)">
                    @error('donateur_nom')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="donateur_telephone">Téléphone du donateur</label>
                    @php
                        $donateurPhone = old('donateur_telephone', $revenue?->donateur_telephone);
                        $donateurPhoneDigits = $donateurPhone ? preg_replace('/\D/', '', $donateurPhone) : '';
                        $donateurPhoneSuffix = ($donateurPhoneDigits !== '' && str_starts_with($donateurPhoneDigits, '242'))
                            ? substr($donateurPhoneDigits, 3) : $donateurPhoneDigits;
                    @endphp
                    <div class="flex rounded-lg border border-slate-200 dark:border-slate-600 overflow-hidden focus-within:ring-2 focus-within:ring-emerald-500/35">
                        <span class="shrink-0 inline-flex items-center px-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-medium border-r border-slate-200 dark:border-slate-600">242</span>
                        <input type="text" name="donateur_telephone" id="donateur_telephone"
                               class="flex-1 min-w-0 border-0 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:ring-0 @error('donateur_telephone') {{ $inputErr }} @enderror"
                               value="{{ $donateurPhoneSuffix }}"
                               placeholder="06 123 45 67"
                               inputmode="tel">
                    </div>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Le préfixe 242 (Congo) est ajouté automatiquement.</p>
                    @error('donateur_telephone')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <div id="jour-semaine-container" class="hidden">
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="jour-semaine-display">Jour de la semaine (quête ordinaire)</label>
        @php
            $jour = old('jour_semaine', $revenue?->jour_semaine);
            $jours = [
                'lundi' => 'Lundi', 'mardi' => 'Mardi', 'mercredi' => 'Mercredi', 'jeudi' => 'Jeudi',
                'vendredi' => 'Vendredi', 'samedi' => 'Samedi', 'dimanche' => 'Dimanche',
            ];
            $jourLabel = $jour ? ($jours[$jour] ?? ucfirst($jour)) : '';
        @endphp
        <input type="text" id="jour-semaine-display"
               class="{{ $inputBase }} bg-slate-50 dark:bg-slate-900/50 @error('jour_semaine') {{ $inputErr }} @enderror"
               value="{{ $jourLabel }}" readonly placeholder="Jour calculé automatiquement">
        <input type="hidden" name="jour_semaine" id="jour-semaine" value="{{ $jour }}">
        @error('jour_semaine')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div class="hidden" id="mois-location-container">
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Mois de paiement du loyer <span class="text-rose-500">*</span></label>
        @php
            $moisLocation = old('mois_location', $revenue?->mois_location);
            $moisLabels = [
                '01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril',
                '05' => 'Mai', '06' => 'Juin', '07' => 'Juillet', '08' => 'Août',
                '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre',
            ];
            $currentYear = now()->year;
            $currentMonth = now()->format('m');
        @endphp
        <div class="grid grid-cols-2 gap-3">
            <select name="mois_location_mois" id="mois-location-mois" class="{{ $inputBase }} @error('mois_location') {{ $inputErr }} @enderror">
                @foreach($moisLabels as $num => $label)
                    <option value="{{ $num }}" @selected($moisLocation ? substr($moisLocation, 5, 2) === $num : $num === $currentMonth)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="mois_location_annee" id="mois-location-annee" class="{{ $inputBase }}">
                @for($y = $currentYear - 1; $y <= $currentYear + 1; $y++)
                    <option value="{{ $y }}" @selected($moisLocation ? (int)substr($moisLocation, 0, 4) === $y : $y === $currentYear)>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <input type="hidden" name="mois_location" id="mois-location" value="{{ $moisLocation }}">
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Sélectionnez le mois pour lequel ce loyer est payé</p>
        @error('mois_location')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="montant_rev">Montant ({{ $currency }}) <span class="text-rose-500">*</span></label>
        <input type="text" name="montant" id="montant_rev"
               class="{{ $inputBase }} @error('montant') {{ $inputErr }} @enderror"
               inputmode="decimal"
               value="{{ old('montant', \App\Helpers\ParoisseConfig::formatMontantSaisie($revenue?->montant)) }}"
               required>
        @error('montant')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="methode_paiement">Méthode de paiement <span class="text-rose-500">*</span></label>
        @php
            $methode = old('methode_paiement', $revenue?->methode_paiement ?? 'especes');
            $methodes = [
                'especes' => 'Espèces', 'cheque' => 'Chèque', 'virement' => 'Virement',
                'carte' => 'Carte', 'mobile_money' => 'Mobile money',
            ];
        @endphp
        <select name="methode_paiement" id="methode_paiement" class="{{ $inputBase }} @error('methode_paiement') {{ $inputErr }} @enderror" required>
            @foreach($methodes as $value => $label)
                <option value="{{ $value }}" @selected($methode === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('methode_paiement')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5" for="notes_rev">Notes</label>
        <textarea name="notes" id="notes_rev" rows="3" class="{{ $inputBase }} @error('notes') {{ $inputErr }} @enderror">{{ old('notes', $revenue?->notes) }}</textarea>
        @error('notes')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('revenue-category');
        const typeSelect = document.getElementById('revenue-type');
        const jourContainer = document.getElementById('jour-semaine-container');
        const jourHidden = document.getElementById('jour-semaine');
        const jourDisplay = document.getElementById('jour-semaine-display');
        const dateInput = document.querySelector('input[name="date_recette"]');
        const montantInput = document.querySelector('input[name="montant"]');
        const moisLocationContainer = document.getElementById('mois-location-container');
        const moisLocationMois = document.getElementById('mois-location-mois');
        const moisLocationAnnee = document.getElementById('mois-location-annee');
        const moisLocationHidden = document.getElementById('mois-location');
        const donateurContainer = document.getElementById('donateur-container');

        function setHidden(el, hidden) {
            if (!el) return;
            el.classList.toggle('hidden', hidden);
        }

        if (categorySelect && typeSelect) {
            function filterTypes() {
                const categoryId = categorySelect.value;
                Array.from(typeSelect.options).forEach(function (opt) {
                    if (!opt.value) { opt.hidden = false; return; }
                    opt.hidden = opt.getAttribute('data-category') !== categoryId;
                });
                updateFieldsVisibility();
            }

            function updateFieldsVisibility() {
                var categoryOption = categorySelect.selectedOptions[0];
                var selectedCategoryCode = categoryOption ? categoryOption.getAttribute('data-category-code') : null;
                if (donateurContainer) {
                    setHidden(donateurContainer, selectedCategoryCode !== 'procure');
                }

                const selectedOption = typeSelect.options[typeSelect.selectedIndex];
                if (!selectedOption || !selectedOption.value) {
                    if (jourContainer) setHidden(jourContainer, true);
                    if (moisLocationContainer) setHidden(moisLocationContainer, true);
                    if (jourHidden) jourHidden.value = '';
                    if (jourDisplay) jourDisplay.value = '';
                    if (moisLocationHidden) moisLocationHidden.value = '';
                    return;
                }

                const categoryCode = selectedOption.getAttribute('data-category-code');
                const typeCode = selectedOption.getAttribute('data-code');

                if (jourContainer && jourHidden && jourDisplay) {
                    if (categoryCode === 'quete_ordinaire') {
                        setHidden(jourContainer, false);
                        syncJourWithDate(true);
                    } else {
                        setHidden(jourContainer, true);
                        jourHidden.value = '';
                        jourDisplay.value = '';
                    }
                }

                if (moisLocationContainer && moisLocationHidden) {
                    if (categoryCode === 'location' && typeCode === 'loyer_boutique') {
                        setHidden(moisLocationContainer, false);
                        syncMoisLocation();
                    } else {
                        setHidden(moisLocationContainer, true);
                        moisLocationHidden.value = '';
                    }
                }
            }

            function syncJourWithDate(force) {
                if (!dateInput || !jourHidden || !jourDisplay || !jourContainer || jourContainer.classList.contains('hidden')) return;
                const value = dateInput.value;
                if (!value) return;
                const d = new Date(value);
                if (isNaN(d)) return;
                const weekdayMap = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
                const expected = weekdayMap[d.getDay()];
                if (force || !jourHidden.value) {
                    jourHidden.value = expected;
                    const labels = { lundi: 'Lundi', mardi: 'Mardi', mercredi: 'Mercredi', jeudi: 'Jeudi', vendredi: 'Vendredi', samedi: 'Samedi', dimanche: 'Dimanche' };
                    jourDisplay.value = labels[expected] || expected;
                }
            }

            function syncMoisLocation() {
                if (!moisLocationMois || !moisLocationAnnee || !moisLocationHidden) return;
                const mois = moisLocationMois.value;
                const annee = moisLocationAnnee.value;
                if (mois && annee) moisLocationHidden.value = annee + '-' + mois;
            }

            categorySelect.addEventListener('change', filterTypes);
            typeSelect.addEventListener('change', updateFieldsVisibility);
            if (moisLocationMois) moisLocationMois.addEventListener('change', syncMoisLocation);
            if (moisLocationAnnee) moisLocationAnnee.addEventListener('change', syncMoisLocation);

            filterTypes();
            if (dateInput) {
                dateInput.addEventListener('change', function() { syncJourWithDate(true); });
                syncJourWithDate();
            }
            if (moisLocationContainer && !moisLocationContainer.classList.contains('hidden')) syncMoisLocation();
        }

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
            if (form) {
                form.addEventListener('submit', function () {
                    if (montantInput.value) montantInput.value = montantInput.value.replace(/\s/g, '');
                });
            }
        }
    });
</script>
@endpush
