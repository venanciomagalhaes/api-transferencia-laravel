<?php

namespace App\Http\Requests\V1\User;

use App\Enums\RolesEnum;
use App\Rules\CpfCnpj;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
                ]),
            ],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'cpf_cnpj' => [
                'required',
                'string',
                'min:11',
                'max:14',
                new CpfCnpj,
                'unique:users,cpf_cnpj',
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
