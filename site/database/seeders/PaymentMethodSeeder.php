<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name'                  => 'Hotel Benizia Bank Transfer',
                'type'                  => 'Bank Transfer',
                'provider'              => 'Zenith Bank',
                'account_name'          => 'Hotel Benizia Limited',
                'account_number'        => '0000000000',
                'bank_name'             => 'Zenith Bank',
                'instructions'          => 'Transfer the commitment fee to the account above, then upload your proof of payment below. Your booking is confirmed once our team verifies the transfer — you will receive an approval email.',
                'active'                => true,
                'accepts_commitment_fee' => true,
                'sort'                  => 1,
            ],
            [
                'name'                  => 'Paystack Card Payment',
                'type'                  => 'Card Gateway',
                'provider'              => 'Paystack',
                'account_name'          => null,
                'account_number'        => null,
                'bank_name'             => null,
                'instructions'          => 'Pay securely with debit card. This demo is ready to connect to a Laravel payment controller.',
                'active'                => true,
                'accepts_commitment_fee' => true,
                'sort'                  => 2,
            ],
            [
                'name'                  => 'Front Desk POS',
                'type'                  => 'POS',
                'provider'              => 'Hotel Front Desk',
                'account_name'          => null,
                'account_number'        => null,
                'bank_name'             => null,
                'instructions'          => 'Use POS at the front desk for walk-in or phone-confirmed reservations.',
                'active'                => false,
                'accepts_commitment_fee' => true,
                'sort'                  => 3,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}
