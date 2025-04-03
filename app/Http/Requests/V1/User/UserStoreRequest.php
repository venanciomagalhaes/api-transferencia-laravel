<?php

namespace App\Http\Requests\V1\User;

use App\Enums\RolesEnum;
use App\Rules\CpfCnpj;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'role' => [
                'required',
                'string',
                Rule::in([
                    RolesEnum::CUSTOMER->value,
                    RolesEnum::MERCHANT->value,
                ])
            ],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'cpf_cnpj' => [
                'required',
                'string',
                'unique:users,cpf_cnpj',
                new CpfCnpj()
            ],
            'password' => [
                'required',
                'string',
                'max:50',
                Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols(),
                'confirmed'],
        ];
    }
}
