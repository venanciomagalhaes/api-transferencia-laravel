<?php

namespace App\Modules\Transaction\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
            'payer' => ['required', 'uuid', 'exists:users,uuid'],
            'payee' => ['required', 'uuid', 'exists:users,uuid'],
            'value' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
