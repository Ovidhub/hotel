<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roomId = $this->route('room')?->id;

        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('rooms', 'slug')->ignore($roomId)],
            'category'    => ['required', 'string', 'max:255'],
            'price'       => ['required', 'integer', 'min:0'],
            'size'        => ['nullable', 'string', 'max:255'],
            'guests'      => ['required', 'integer', 'min:1'],
            'beds'        => ['required', 'integer', 'min:1'],
            'baths'       => ['nullable', 'integer', 'min:0'],
            'sqm'         => ['nullable', 'string', 'max:100'],
            'rating'      => ['nullable', 'numeric', 'min:0', 'max:5'],
            'reviews'     => ['nullable', 'integer', 'min:0'],
            'excerpt'     => ['required', 'string'],
            'description' => ['required', 'string'],
            'image'       => ['required', 'string', 'max:1000'],
            'gallery'     => ['nullable', 'string'],
            'amenities'   => ['nullable', 'string'],
            'includes'    => ['nullable', 'string'],
            'policies'    => ['nullable', 'string'],
            'best_for'    => ['nullable', 'string'],
            'is_active'   => ['boolean'],
            'sort'        => ['nullable', 'integer', 'min:0'],
        ];
    }
}
