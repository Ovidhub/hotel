<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaymentMethodRequest;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('sort')->orderBy('name')->get();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(PaymentMethodRequest $request)
    {
        $data = $this->buildData($request->validated());
        PaymentMethod::create($data);

        return redirect()->route('admin.payment-methods.index')
                         ->with('status', 'Payment method created successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $data = $this->buildData($request->validated());
        $paymentMethod->update($data);

        return redirect()->route('admin.payment-methods.index')
                         ->with('status', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
                         ->with('status', 'Payment method deleted successfully.');
    }

    private function buildData(array $validated): array
    {
        return [
            'name'                   => $validated['name'],
            'type'                   => $validated['type'],
            'provider'               => $validated['provider'],
            'account_name'           => $validated['account_name'] ?? null,
            'account_number'         => $validated['account_number'] ?? null,
            'bank_name'              => $validated['bank_name'] ?? null,
            'instructions'           => $validated['instructions'],
            'active'                 => (bool) ($validated['active'] ?? false),
            'accepts_commitment_fee' => (bool) ($validated['accepts_commitment_fee'] ?? false),
            'sort'                   => (int) ($validated['sort'] ?? 0),
        ];
    }
}
