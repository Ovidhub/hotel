<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => ['required', 'string'],
            'answer'   => ['required', 'string'],
            'sort'     => ['nullable', 'integer', 'min:0'],
        ];
    }
}
