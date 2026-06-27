<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                   => ['required', 'string', 'max:255'],
            'type'                   => ['required', 'string', 'max:255'],
            'provider'               => ['required', 'string', 'max:255'],
            'account_name'           => ['nullable', 'string', 'max:255'],
            'account_number'         => ['nullable', 'string', 'max:255'],
            'bank_name'              => ['nullable', 'string', 'max:255'],
            'instructions'           => ['required', 'string'],
            'active'                 => ['boolean'],
            'accepts_commitment_fee' => ['boolean'],
            'sort'                   => ['nullable', 'integer', 'min:0'],
        ];
    }
}
