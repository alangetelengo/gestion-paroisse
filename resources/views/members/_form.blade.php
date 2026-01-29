@php
    /** @var \App\Models\Member|null $member */
    $member = $member ?? null;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Prénom</label>
        <input type="text" name="prenom" class="form-control" data-transform="title" value="{{ old('prenom', $member?->prenom) }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="nom" class="form-control" data-transform="upper" value="{{ old('nom', $member?->nom) }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Date de naissance</label>
        <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $member?->date_naissance?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Sexe</label>
        <select name="sexe" class="form-control" required>
            @php
                $sexe = old('sexe', $member?->sexe ?? 'M');
            @endphp
            <option value="M" @selected($sexe === 'M')>Masculin</option>
            <option value="F" @selected($sexe === 'F')>Féminin</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Téléphone</label>
        <input type="text" name="telephone" class="form-control" data-input="phone" value="{{ old('telephone', $member?->telephone) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" data-transform="lower" value="{{ old('email', $member?->email) }}">
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Adresse</label>
        <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $member?->adresse) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Statut</label>
        <select name="statut" class="form-control" required>
            @foreach(['actif' => 'Actif', 'inactif' => 'Inactif', 'décédé' => 'Décédé'] as $value => $label)
                <option value="{{ $value }}" @selected(old('statut', $member?->statut ?? 'actif') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    @if(auth()->check() && auth()->user()->hasRole('super_admin'))
        <div class="col-md-8 mb-3">
            <label class="form-label">Paroisse</label>
            <select name="paroisse_id" class="form-control">
                <option value="">—</option>
                @foreach($paroisses as $paroisse)
                    <option value="{{ $paroisse->id }}" @selected((string) old('paroisse_id', $member?->paroisse_id) === (string) $paroisse->id)>
                        {{ $paroisse->nom }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="col-12 mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="4">{{ old('notes', $member?->notes) }}</textarea>
    </div>
</div>

