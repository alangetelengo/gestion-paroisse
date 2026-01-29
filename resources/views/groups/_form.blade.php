@php
    /** @var \App\Models\Group|null $group */
    $group = $group ?? null;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nom du groupe <span class="text-danger">*</span></label>
        <input type="text"
               name="nom"
               class="form-control @error('nom') is-invalid @enderror"
               data-transform="upper"
               value="{{ old('nom', $group?->nom) }}"
               required>
        @error('nom')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Type de groupe <span class="text-danger">*</span></label>
        <select name="type" class="form-control @error('type') is-invalid @enderror" required>
            @php
                $type = old('type', $group?->type ?? 'chorale');
                $types = [
                    'chorale' => 'Chorale',
                    'catéchisme' => 'Catéchisme',
                    'mouvement' => 'Mouvement',
                    'autre' => 'Autre',
                ];
            @endphp
            @foreach($types as $value => $label)
                <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('type')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if(isset($paroisses) && $paroisses->count() > 0)
        <div class="col-md-6 mb-3">
            <label class="form-label">Paroisse</label>
            <select name="paroisse_id" class="form-control @error('paroisse_id') is-invalid @enderror">
                @foreach($paroisses as $paroisse)
                    <option value="{{ $paroisse->id }}"
                        @selected((string) old('paroisse_id', $group?->paroisse_id) === (string) $paroisse->id)>
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
        <label class="form-label">Responsable du groupe</label>
        <select name="responsable_id" class="form-control @error('responsable_id') is-invalid @enderror">
            <option value="">— Aucun —</option>
            @foreach($responsables as $responsable)
                <option value="{{ $responsable->id }}"
                    @selected((string) old('responsable_id', $group?->responsable_id) === (string) $responsable->id)>
                    {{ $responsable->prenom }} {{ $responsable->nom }}
                </option>
            @endforeach
        </select>
        @error('responsable_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $group?->description) }}</textarea>
        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

