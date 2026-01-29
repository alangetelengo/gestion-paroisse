<?php

namespace App\Http\Requests;

use App\Helpers\ParoisseConfig;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreParoisseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage_paroisses');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $paroisseId = Auth::check() ? (Auth::user()->paroisse_id ?? null) : null;
        $phoneRegex = (string) ParoisseConfig::get($paroisseId, 'phone_regex', '/^(\+242|242|0)?[ \-]?[0-9]{9}$/');

        return [
            'nom' => ['required', 'string', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:255'],
            'pays' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:50', function (string $attribute, mixed $value, \Closure $fail) use ($phoneRegex) {
                if ($value === null || $value === '') {
                    return;
                }

                $value = (string) $value;
                if (@preg_match($phoneRegex, '') === false) {
                    $fail('Le format de téléphone configuré est invalide (regex).');
                    return;
                }

                if (! preg_match($phoneRegex, $value)) {
                    $fail('Le format du téléphone est invalide.');
                }
            }],
            'email' => ['nullable', 'email', 'max:255'],
            'code_paroisse' => ['nullable', 'string', 'max:255', 'unique:paroisses,code_paroisse'],
            'curé_id' => ['nullable', 'exists:members,id'],
            'diocèse' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la paroisse est obligatoire.',
            'code_paroisse.unique' => 'Ce code de paroisse est déjà utilisé.',
            'curé_id.exists' => 'Le membre sélectionné n\'existe pas.',
            'email.email' => 'L\'email doit être valide.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nom' => $this->toUpper($this->input('nom')),
            'ville' => $this->toTitleCase($this->input('ville')),
            'pays' => $this->toTitleCase($this->input('pays')),
            'diocèse' => $this->toTitleCase($this->input('diocèse')),
            'code_paroisse' => $this->toUpper($this->input('code_paroisse')),
            'email' => $this->toLower($this->input('email')),
            'telephone' => $this->normalizePhone($this->input('telephone')),
        ]);
    }

    private function toUpper(?string $value): ?string
    {
        $value = $this->trimOrNull($value);
        return $value !== null ? mb_strtoupper($value) : null;
    }

    private function toLower(?string $value): ?string
    {
        $value = $this->trimOrNull($value);
        return $value !== null ? mb_strtolower($value) : null;
    }

    private function toTitleCase(?string $value): ?string
    {
        $value = $this->trimOrNull($value);
        if ($value === null) {
            return null;
        }

        $value = mb_strtolower($value);
        return mb_convert_case($value, MB_CASE_TITLE);
    }

    private function normalizePhone(?string $value): ?string
    {
        $value = $this->trimOrNull($value);
        if ($value === null) {
            return null;
        }

        return preg_replace('/[()\s\-]/', '', $value) ?? $value;
    }

    private function trimOrNull(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        return $value === '' ? null : $value;
    }
}
