@php
    /** @var \App\Models\Event|null $event */
    $event = $event ?? null;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Titre de l'événement</label>
        <input type="text"
               name="titre"
               class="form-control"
               data-transform="upper"
               value="{{ old('titre', $event?->titre) }}"
               required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Type d'événement</label>
        @php
            $type = old('type', $event?->type ?? 'messe');
        @endphp
        <select name="type" class="form-control" id="event-type" required>
            <option value="messe" @selected($type === 'messe')>Messe</option>
            <option value="célébration" @selected($type === 'célébration')>Célébration</option>
            <option value="activité" @selected($type === 'activité')>Activité</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Paroisse</label>
        @if(auth()->check() && auth()->user()->hasRole('super_admin'))
            <select name="paroisse_id" class="form-control" required>
                <option value="">—</option>
                @foreach($paroisses as $paroisse)
                    <option value="{{ $paroisse->id }}" @selected((string) old('paroisse_id', $event?->paroisse_id) === (string) $paroisse->id)>
                        {{ $paroisse->nom }}
                    </option>
                @endforeach
            </select>
        @else
            <input type="text" class="form-control" value="{{ auth()->user()->paroisse?->nom }}" disabled>
        @endif
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Date</label>
        <input type="date" name="date_evenement" class="form-control"
               value="{{ old('date_evenement', $event?->date_evenement?->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Heure</label>
        <input type="time" name="heure_evenement" class="form-control"
               value="{{ old('heure_evenement', optional($event?->heure_evenement)->format('H:i')) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Lieu</label>
        <input type="text" name="lieu" class="form-control" value="{{ old('lieu', $event?->lieu) }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Célébré par</label>
        <select name="celebre_par_id" class="form-control">
            <option value="">—</option>
            @foreach($celebrants as $celebrant)
                <option value="{{ $celebrant->id }}" @selected((string) old('celebre_par_id', $event?->celebre_par_id) === (string) $celebrant->id)>
                    {{ $celebrant->prenom }} {{ $celebrant->nom }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">
            Intention de messe (optionnel)
            <small class="text-muted d-block" style="font-weight: 400;">
                Par exemple : Pour la famille X, pour les défunts Y, action de grâce, etc.
            </small>
        </label>
        <input type="text" name="intention" class="form-control" id="event-intention"
               placeholder="Laisser vide si non applicable"
               value="{{ old('intention', $event?->intention) }}">
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $event?->description) }}</textarea>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('event-type');
        const intentionInput = document.getElementById('event-intention');

        if (!typeSelect || !intentionInput) {
            return;
        }

        const toggleIntentionState = () => {
            const isMesse = typeSelect.value === 'messe';
            intentionInput.placeholder = isMesse
                ? 'Ex : Pour la famille X, pour les défunts Y, action de grâce...'
                : 'Laisser vide si non applicable';
        };

        typeSelect.addEventListener('change', toggleIntentionState);
        toggleIntentionState();
    });
</script>
@endpush

