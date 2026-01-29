<?php

namespace App\Http\Requests;

use App\Helpers\ParoisseConfig;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->can('create_members');
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
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date'],
            'sexe' => ['required', 'in:M,F'],
            'adresse' => ['nullable', 'string', 'max:255'],
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
            'statut' => ['required', 'in:actif,inactif,décédé'],
            'notes' => ['nullable', 'string'],
            'paroisse_id' => Auth::check() && Auth::user()->hasRole('super_admin')
                ? ['required', 'integer', 'exists:paroisses,id']
                : ['nullable', 'integer', 'exists:paroisses,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'prenom' => $this->toTitleCase($this->input('prenom')),
            'nom' => $this->toUpper($this->input('nom')),
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

        // Garde +, supprime espaces/tirets/parenthèses
        $value = preg_replace('/[()\s\-]/', '', $value) ?? $value;
        return $value;
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
