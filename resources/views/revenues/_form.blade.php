@php
    /** @var \App\Models\Revenue|null $revenue */
    $revenue = $revenue ?? null;
    // Par défaut, on ne force pas la paroisse ici : on récupère la monnaie globale (ou celle de la paroisse si tu ajustes plus tard)
    $currency = \App\Helpers\ParoisseConfig::get(null, 'monnaie', 'FCFA');
@endphp

<div class="row">
    @if(isset($paroisses) && $paroisses->count() > 0)
        <div class="col-md-6 mb-3">
            <label class="form-label">Paroisse</label>
            <select name="paroisse_id" class="form-control @error('paroisse_id') is-invalid @enderror">
                @foreach($paroisses as $paroisse)
                    <option value="{{ $paroisse->id }}"
                        @selected((string) old('paroisse_id', $revenue?->paroisse_id) === (string) $paroisse->id)>
                        {{ $paroisse->nom }}
                    </option>
                @endforeach
            </select>
            @error('paroisse_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif

    <div class="col-md-6 mb-3">
        <label class="form-label">Date de la recette <span class="text-danger">*</span></label>
        <input type="date"
               name="date_recette"
               class="form-control @error('date_recette') is-invalid @enderror"
               value="{{ old('date_recette', $revenue?->date_recette?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
               required>
        @error('date_recette')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Catégorie <span class="text-danger">*</span></label>
        <select name="revenue_category_id" id="revenue-category" class="form-control @error('revenue_category_id') is-invalid @enderror" required>
            <option value="">Sélectionner...</option>
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}"
                    data-category-code="{{ $categorie->code }}"
                    @selected((int) old('revenue_category_id', $revenue?->revenue_category_id) === $categorie->id)>
                    {{ $categorie->nom }}
                </option>
            @endforeach
        </select>
        @error('revenue_category_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Type de recette <span class="text-danger">*</span></label>
        <select name="revenue_type_id" id="revenue-type" class="form-control @error('revenue_type_id') is-invalid @enderror" required>
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
        @error('revenue_type_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3" id="jour-semaine-container">
        <label class="form-label">Jour de la semaine (quête ordinaire)</label>
        @php
            $jour = old('jour_semaine', $revenue?->jour_semaine);
            $jours = [
                'lundi' => 'Lundi',
                'mardi' => 'Mardi',
                'mercredi' => 'Mercredi',
                'jeudi' => 'Jeudi',
                'vendredi' => 'Vendredi',
                'samedi' => 'Samedi',
                'dimanche' => 'Dimanche',
            ];
            $jourLabel = $jour ? ($jours[$jour] ?? ucfirst($jour)) : '';
        @endphp
        <input type="text"
               id="jour-semaine-display"
               class="form-control @error('jour_semaine') is-invalid @enderror"
               value="{{ $jourLabel }}"
               readonly
               placeholder="Jour calculé automatiquement">
        <input type="hidden" name="jour_semaine" id="jour-semaine" value="{{ $jour }}">
        @error('jour_semaine')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Mois de location (pour loyer boutique) --}}
    <div class="col-md-6 mb-3" id="mois-location-container" style="display: none;">
        <label class="form-label">Mois de paiement du loyer <span class="text-danger">*</span></label>
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
        <div class="row">
            <div class="col-6">
                <select name="mois_location_mois" id="mois-location-mois" class="form-control @error('mois_location') is-invalid @enderror">
                    @foreach($moisLabels as $num => $label)
                        <option value="{{ $num }}" @selected($moisLocation ? substr($moisLocation, 5, 2) === $num : $num === $currentMonth)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <select name="mois_location_annee" id="mois-location-annee" class="form-control">
                    @for($y = $currentYear - 1; $y <= $currentYear + 1; $y++)
                        <option value="{{ $y }}" @selected($moisLocation ? (int)substr($moisLocation, 0, 4) === $y : $y === $currentYear)>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <input type="hidden" name="mois_location" id="mois-location" value="{{ $moisLocation }}">
        <small class="text-muted">Sélectionnez le mois pour lequel ce loyer est payé</small>
        @error('mois_location')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Montant ({{ $currency }}) <span class="text-danger">*</span></label>
        <input type="text"
               name="montant"
               class="form-control @error('montant') is-invalid @enderror"
               inputmode="decimal"
               value="{{ old('montant', $revenue?->montant ? number_format($revenue->montant, 0, ',', '.') : '') }}"
               required>
        @error('montant')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Méthode de paiement <span class="text-danger">*</span></label>
        <select name="methode_paiement" class="form-control @error('methode_paiement') is-invalid @enderror" required>
            @php
                $methode = old('methode_paiement', $revenue?->methode_paiement ?? 'especes');
                $methodes = [
                    'especes' => 'Espèces',
                    'cheque' => 'Chèque',
                    'virement' => 'Virement',
                    'carte' => 'Carte',
                    'mobile_money' => 'Mobile money',
                ];
            @endphp
            @foreach($methodes as $value => $label)
                <option value="{{ $value }}" @selected($methode === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('methode_paiement')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Référence paiement</label>
        <input type="text"
               name="reference_paiement"
               class="form-control @error('reference_paiement') is-invalid @enderror"
               value="{{ old('reference_paiement', $revenue?->reference_paiement) }}"
               readonly
               placeholder="Générée automatiquement">
        @error('reference_paiement')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $revenue?->notes) }}</textarea>
        @error('notes')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
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

        // Éléments pour mois_location
        const moisLocationContainer = document.getElementById('mois-location-container');
        const moisLocationMois = document.getElementById('mois-location-mois');
        const moisLocationAnnee = document.getElementById('mois-location-annee');
        const moisLocationHidden = document.getElementById('mois-location');

        if (categorySelect && typeSelect) {
            function filterTypes() {
                const categoryId = categorySelect.value;
                Array.from(typeSelect.options).forEach(function (opt) {
                    if (!opt.value) {
                        opt.hidden = false;
                        return;
                    }
                    const belongs = opt.getAttribute('data-category') === categoryId;
                    opt.hidden = !belongs;
                });
                updateFieldsVisibility();
            }

            function updateFieldsVisibility() {
                const selectedOption = typeSelect.options[typeSelect.selectedIndex];
                if (!selectedOption || !selectedOption.value) {
                    // Cacher tous les champs conditionnels
                    if (jourContainer) jourContainer.style.display = 'none';
                    if (moisLocationContainer) moisLocationContainer.style.display = 'none';
                    if (jourHidden) jourHidden.value = '';
                    if (jourDisplay) jourDisplay.value = '';
                    if (moisLocationHidden) moisLocationHidden.value = '';
                    return;
                }

                const categoryCode = selectedOption.getAttribute('data-category-code');
                const typeCode = selectedOption.getAttribute('data-code');

                // Jour semaine : visible uniquement pour quête ordinaire
                if (jourContainer && jourHidden && jourDisplay) {
                    if (categoryCode === 'quete_ordinaire') {
                        jourContainer.style.display = '';
                        syncJourWithDate(true);
                    } else {
                        jourContainer.style.display = 'none';
                        jourHidden.value = '';
                        jourDisplay.value = '';
                    }
                }

                // Mois location : visible pour catégorie "location" et type "loyer_boutique"
                if (moisLocationContainer && moisLocationHidden) {
                    if (categoryCode === 'location' && typeCode === 'loyer_boutique') {
                        moisLocationContainer.style.display = '';
                        syncMoisLocation();
                    } else {
                        moisLocationContainer.style.display = 'none';
                        moisLocationHidden.value = '';
                    }
                }
            }

            function syncJourWithDate(force = false) {
                if (!dateInput || !jourHidden || !jourDisplay || !jourContainer) return;
                if (jourContainer.style.display === 'none') {
                    return;
                }
                const value = dateInput.value;
                if (!value) return;
                const d = new Date(value);
                if (isNaN(d)) return;
                const weekdayMap = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
                const expected = weekdayMap[d.getDay()];
                if (force || !jourHidden.value) {
                    jourHidden.value = expected;
                    const labels = {
                        'lundi': 'Lundi',
                        'mardi': 'Mardi',
                        'mercredi': 'Mercredi',
                        'jeudi': 'Jeudi',
                        'vendredi': 'Vendredi',
                        'samedi': 'Samedi',
                        'dimanche': 'Dimanche',
                    };
                    jourDisplay.value = labels[expected] || expected;
                }
            }

            function syncMoisLocation() {
                if (!moisLocationMois || !moisLocationAnnee || !moisLocationHidden) return;
                const mois = moisLocationMois.value;
                const annee = moisLocationAnnee.value;
                if (mois && annee) {
                    moisLocationHidden.value = annee + '-' + mois;
                }
            }

            categorySelect.addEventListener('change', filterTypes);
            typeSelect.addEventListener('change', updateFieldsVisibility);

            if (moisLocationMois) {
                moisLocationMois.addEventListener('change', syncMoisLocation);
            }
            if (moisLocationAnnee) {
                moisLocationAnnee.addEventListener('change', syncMoisLocation);
            }

            filterTypes();

            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    syncJourWithDate(true);
                });
                syncJourWithDate();
            }

            // Sync initial mois_location si déjà visible
            if (moisLocationContainer && moisLocationContainer.style.display !== 'none') {
                syncMoisLocation();
            }
        }

        // Formatage du montant (empêche les lettres et ajoute les séparateurs de milliers)
        if (montantInput) {
            const form = montantInput.form;

            function formatMontant() {
                let value = montantInput.value.replace(/[^\d]/g, '');
                if (!value) {
                    montantInput.value = '';
                    return;
                }
                // Format 100.000 avec des points comme séparateurs de milliers
                const parts = [];
                while (value.length > 3) {
                    parts.unshift(value.slice(-3));
                    value = value.slice(0, -3);
                }
                if (value.length) {
                    parts.unshift(value);
                }
                montantInput.value = parts.join('.');
            }

            montantInput.addEventListener('input', formatMontant);

            if (form) {
                form.addEventListener('submit', function () {
                    // enlever les points avant envoi au serveur
                    if (montantInput.value) {
                        montantInput.value = montantInput.value.replace(/\./g, '');
                    }
                });
            }
        }
    });
</script>
@endpush

