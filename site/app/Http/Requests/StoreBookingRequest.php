<?php

namespace App\Http\Requests;

use App\Models\Apartment;
use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = $this->input('type');

        return [
            'type' => ['required', 'in:room,apartment'],

            'slug' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail) use ($type) {
                    if ($type === 'room' && ! Room::where('slug', $value)->exists()) {
                        $fail('The selected room does not exist.');
                    }
                    if ($type === 'apartment' && ! Apartment::where('slug', $value)->exists()) {
                        $fail('The selected apartment does not exist.');
                    }
                },
            ],

            'check_in' => ['required', 'date', 'after_or_equal:today'],

            'check_out' => ['required', 'date', 'after:check_in'],

            'guests' => ['required', 'integer', 'min:1'],

            'guest_name'  => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['required', 'string', 'max:50'],

            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in'              => 'Bookable type must be room or apartment.',
            'check_in.after_or_equal' => 'Check-in must be today or a future date.',
            'check_out.after'      => 'Check-out must be after the check-in date.',
            'guests.min'           => 'At least 1 guest is required.',
            'guest_name.required'  => 'Please enter the guest name.',
            'guest_email.email'    => 'Please enter a valid email address.',
            'guest_phone.required' => 'Please enter a contact phone number.',
            'proof.mimes'          => 'Proof of payment must be an image (jpg, png, webp) or PDF.',
            'proof.max'            => 'Proof of payment must be under 4 MB.',
        ];
    }
}
