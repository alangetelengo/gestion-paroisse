@php
    /** @var \App\Models\Group|null $group */
    $group = $group ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="group-nom" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Nom du groupe <span class="text-rose-600 dark:text-rose-400">*</span></label>
        <input type="text"
               id="group-nom"
               name="nom"
               class="w-full rounded-lg border {{ $errors->has('nom') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-600' }} bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35"
               data-transform="upper"
               value="{{ old('nom', $group?->nom) }}"
               required>
        @error('nom')
        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="group-type" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Type de groupe <span class="text-rose-600 dark:text-rose-400">*</span></label>
        <select name="type" id="group-type" class="w-full rounded-lg border {{ $errors->has('type') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-600' }} bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35" required>
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
        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>

    @if(isset($paroisses) && $paroisses->count() > 0)
        <div>
            <label for="group-paroisse" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Paroisse</label>
            <select name="paroisse_id" id="group-paroisse" class="w-full rounded-lg border {{ $errors->has('paroisse_id') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-600' }} bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                @foreach($paroisses as $paroisse)
                    <option value="{{ $paroisse->id }}"
                        @selected((string) old('paroisse_id', $group?->paroisse_id) === (string) $paroisse->id)>
                        {{ $paroisse->nom }}
                    </option>
                @endforeach
            </select>
            @error('paroisse_id')
            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <div>
        <label for="group-resp" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Responsable du groupe</label>
        <select name="responsable_id" id="group-resp" class="w-full rounded-lg border {{ $errors->has('responsable_id') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-600' }} bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
            <option value="">— Aucun —</option>
            @foreach($responsables as $responsable)
                <option value="{{ $responsable->id }}"
                    @selected((string) old('responsable_id', $group?->responsable_id) === (string) $responsable->id)>
                    {{ $responsable->prenom }} {{ $responsable->nom }}
                </option>
            @endforeach
        </select>
        @error('responsable_id')
        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="group-desc" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Description</label>
        <textarea name="description" id="group-desc" class="w-full rounded-lg border {{ $errors->has('description') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-600' }} bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/35" rows="3">{{ old('description', $group?->description) }}</textarea>
        @error('description')
        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>
</div>
