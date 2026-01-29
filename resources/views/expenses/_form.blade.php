@php
    /** @var \App\Models\Expense|null $expense */
    $expense = $expense ?? null;
    $currency = \App\Helpers\ParoisseConfig::get(null, 'monnaie', 'FCFA');
@endphp

<div class="row">
    @if(isset($paroisses) && $paroisses->count() > 0)
        <div class="col-md-6 mb-3">
            <label class="form-label">Paroisse</label>
            <select name="paroisse_id" class="form-control @error('paroisse_id') is-invalid @enderror">
                @foreach($paroisses as $paroisse)
                    <option value="{{ $paroisse->id }}"
                        @selected((string) old('paroisse_id', $expense?->paroisse_id) === (string) $paroisse->id)>
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
        <label class="form-label">Date de la dépense <span class="text-danger">*</span></label>
        <input type="date"
               name="date_depense"
               class="form-control @error('date_depense') is-invalid @enderror"
               value="{{ old('date_depense', $expense?->date_depense?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
               required>
        @error('date_depense')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Catégorie de charge <span class="text-danger">*</span></label>
        @php
            $categorie = old('categorie_charge', $expense?->categorie_charge ?? 'charge_fixe');
            $categories = [
                'charge_fixe' => 'Charge fixe',
                'charge_variable' => 'Charge variable',
                'charge_exceptionnelle' => 'Charge exceptionnelle',
            ];
        @endphp
        <select name="categorie_charge" class="form-control @error('categorie_charge') is-invalid @enderror" required>
            @foreach($categories as $value => $label)
                <option value="{{ $value }}" @selected($categorie === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('categorie_charge')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Type de charge <span class="text-danger">*</span></label>
        @php
            $type = old('type_charge', $expense?->type_charge ?? 'autre');
            $types = [
                'carburant' => 'Carburant',
                'hosties' => 'Hosties',
                'internet' => 'Internet',
                'maintenance_materiel' => 'Maintenance matériel',
                'gaz' => 'Gaz',
                'eau' => 'Eau',
                'electricite' => 'Électricité',
                'jardinage' => 'Jardinage',
                'salaire_ouvrier' => 'Salaire ouvrier',
                'autre' => 'Autre',
            ];
        @endphp
        <select name="type_charge" class="form-control @error('type_charge') is-invalid @enderror" required>
            @foreach($types as $value => $label)
                <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('type_charge')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Montant ({{ $currency }}) <span class="text-danger">*</span></label>
        <input type="text"
               name="montant"
               class="form-control @error('montant') is-invalid @enderror"
               inputmode="decimal"
               value="{{ old('montant', $expense?->montant ? number_format($expense->montant, 0, ',', '.') : '') }}"
               required>
        @error('montant')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Référence facture</label>
        <input type="text"
               name="facture_reference"
               class="form-control @error('facture_reference') is-invalid @enderror"
               value="{{ old('facture_reference', $expense?->facture_reference) }}">
        @error('facture_reference')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Pièce jointe - Facture (PDF / image)</label>
        <input type="file"
               name="piece_facture"
               class="form-control @error('piece_facture') is-invalid @enderror"
               accept=".pdf,image/*">
        @error('piece_facture')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if($expense?->piece_facture_path)
            <small class="form-text text-muted">
                Facture actuelle :
                <a href="{{ asset('storage/'.$expense->piece_facture_path) }}" target="_blank">Voir le fichier</a>
            </small>
        @endif
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Fournisseur</label>
        <input type="text"
               name="fournisseur"
               class="form-control @error('fournisseur') is-invalid @enderror"
               data-transform="title"
               value="{{ old('fournisseur', $expense?->fournisseur) }}">
        @error('fournisseur')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Pièce jointe - Reçu de paiement (PDF / image)</label>
        <input type="file"
               name="piece_recu"
               class="form-control @error('piece_recu') is-invalid @enderror"
               accept=".pdf,image/*">
        @error('piece_recu')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if($expense?->piece_recu_path)
            <small class="form-text text-muted">
                Reçu actuel :
                <a href="{{ asset('storage/'.$expense->piece_recu_path) }}" target="_blank">Voir le fichier</a>
            </small>
        @endif
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Méthode de paiement <span class="text-danger">*</span></label>
        @php
            $methode = old('methode_paiement', $expense?->methode_paiement ?? 'especes');
            $methodes = [
                'especes' => 'Espèces',
                'cheque' => 'Chèque',
                'virement' => 'Virement',
                'carte' => 'Carte',
                'mobile_money' => 'Mobile money',
            ];
        @endphp
        <select name="methode_paiement" class="form-control @error('methode_paiement') is-invalid @enderror" required>
            @foreach($methodes as $value => $label)
                <option value="{{ $value }}" @selected($methode === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('methode_paiement')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $expense?->notes) }}</textarea>
        @error('notes')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const montantInput = document.querySelector('input[name="montant"]');
        if (montantInput) {
            const form = montantInput.form;

            function formatMontant() {
                let value = montantInput.value.replace(/[^\d]/g, '');
                if (!value) {
                    montantInput.value = '';
                    return;
                }
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
                    if (montantInput.value) {
                        montantInput.value = montantInput.value.replace(/\./g, '');
                    }
                });
            }
        }
    });
</script>
@endpush

