@php
    $sacrament = $sacrament ?? null;
    $isEdit = isset($sacrament) && $sacrament->exists;
@endphp
<input type="hidden" name="type" value="{{ $type }}">

<div class="row">
    @if(auth()->user()->hasRole('super_admin') && $paroisses->count() > 0)
        <div class="col-md-6 mb-3">
            <label class="form-label">Paroisse</label>
            <select name="paroisse_id" class="form-control" id="sacrament-paroisse-id" @if($isEdit) disabled @endif>
                @foreach($paroisses as $paroisse)
                    <option value="{{ $paroisse->id }}" @selected((string) old('paroisse_id', $sacrament?->paroisse_id ?? $paroisseId) === (string) $paroisse->id)>{{ $paroisse->nom }}</option>
                @endforeach
            </select>
            @if($isEdit)<input type="hidden" name="paroisse_id" value="{{ $sacrament->paroisse_id }}">@endif
        </div>
    @endif
    <div class="col-md-4 mb-3">
        <label class="form-label">Date de célébration</label>
        <input type="date" name="date_celebration" class="form-control" value="{{ old('date_celebration', $sacrament?->date_celebration?->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Lieu</label>
        <input type="text" name="lieu" class="form-control" value="{{ old('lieu', $sacrament?->lieu) }}" placeholder="Ex : Église Saint-X">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Célébrant</label>
        <select name="celebrant_id" class="form-control">
            <option value="">—</option>
            @foreach($celebrants as $c)
                <option value="{{ $c->id }}" @selected((string) old('celebrant_id', $sacrament?->celebrant_id) === (string) $c->id)>{{ $c->prenom }} {{ $c->nom }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nom du bénéficiaire (si non membre)</label>
        <input type="text" name="beneficiary_name" class="form-control" value="{{ old('beneficiary_name', $sacrament?->beneficiary_name) }}" placeholder="Nom complet">
    </div>
    @if(isset($members) && $members->count() > 0)
    <div class="col-md-6 mb-3">
        <label class="form-label">Membre (bénéficiaire)</label>
        <select name="beneficiary_id" class="form-control">
            <option value="">—</option>
            @foreach($members as $m)
                <option value="{{ $m->id }}" @selected((string) old('beneficiary_id', $sacrament?->beneficiary_id) === (string) $m->id)>{{ $m->prenom }} {{ $m->nom }}</option>
            @endforeach
        </select>
    </div>
    @endif
    <div class="col-12 mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $sacrament?->notes) }}</textarea>
    </div>
</div>
