<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $apartmentId = $this->route('apartment')?->id;

        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('apartments', 'slug')->ignore($apartmentId)],
            'type'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'integer', 'min:0'],
            'status'      => ['required', Rule::in(['Available', 'Occupied', 'Maintenance'])],
            'image'       => ['required', 'string', 'max:1000'],
            'gallery'     => ['nullable', 'string'],
            'bedrooms'    => ['required', 'integer', 'min:0'],
            'bathrooms'   => ['required', 'integer', 'min:0'],
            'occupancy'   => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string'],
            'amenities'   => ['nullable', 'string'],
            'is_active'   => ['boolean'],
            'sort'        => ['nullable', 'integer', 'min:0'],
        ];
    }
}
