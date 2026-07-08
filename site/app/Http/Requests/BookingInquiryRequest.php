<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class BookingInquiryRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:40'],
            'room'      => ['required', 'string', 'max:255'],
            'check_in'  => ['required', 'string', 'max:40'],
            'check_out' => ['required', 'string', 'max:40'],
            'guests'    => ['required', 'string', 'max:20'],
            'message'   => ['nullable', 'string', 'max:2000'],
        ];
    }
}
