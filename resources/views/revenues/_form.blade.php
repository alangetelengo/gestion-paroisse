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
                updateJourSemaineVisibility();
            }

            function updateJourSemaineVisibility() {
                if (!jourContainer || !jourHidden || !jourDisplay) return;
                const selectedOption = typeSelect.options[typeSelect.selectedIndex];
                if (!selectedOption || !selectedOption.value) {
                    jourContainer.style.display = 'none';
                    jourHidden.value = '';
                    jourDisplay.value = '';
                    return;
                }
                const categoryCode = selectedOption.getAttribute('data-category-code');
                const typeCode = selectedOption.getAttribute('data-code');

                if (categoryCode === 'quete_ordinaire') {
                    jourContainer.style.display = '';
                        // Les règles de cohérence sont gérées côté contrôleur,
                        // ici on laisse juste le champ en lecture seule.
                } else {
                    jourContainer.style.display = 'none';
                        jourHidden.value = '';
                        jourDisplay.value = '';
                }

                // Si le champ est visible, on synchronise automatiquement avec la date
                if (jourContainer.style.display !== 'none') {
                    syncJourWithDate(true);
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
                // Rien à réappliquer ici, la vraie validation se fait côté backend
            }

            categorySelect.addEventListener('change', filterTypes);
            typeSelect.addEventListener('change', updateJourSemaineVisibility);
            filterTypes();

            if (dateInput) {
                dateInput.addEventListener('change', syncJourWithDate);
                // Initialisation au chargement si on est sur une quête ordinaire
                syncJourWithDate();
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

