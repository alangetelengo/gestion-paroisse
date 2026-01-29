<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage_users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'paroisse_id' => ['nullable', 'exists:paroisses,id'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $name = $this->input('name');
        $email = $this->input('email');

        $this->merge([
            'name' => $this->toTitleCase(is_string($name) ? $name : null),
            'email' => $this->toLower(is_string($email) ? $email : null),
        ]);
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

    private function trimOrNull(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        return $value === '' ? null : $value;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'paroisse_id.exists' => 'La paroisse sélectionnée n\'existe pas.',
            'roles.required' => 'Au moins un rôle doit être sélectionné.',
            'roles.*.exists' => 'Un des rôles sélectionnés n\'existe pas.',
        ];
    }
}
