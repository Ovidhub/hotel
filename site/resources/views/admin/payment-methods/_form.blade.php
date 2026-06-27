{{-- Shared form partial for payment method create & edit --}}

<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

    {{-- Name --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $paymentMethod->name ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('name') border-red-400 @enderror">
        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Type --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Type <span class="text-red-500">*</span></label>
        <input type="text" name="type" value="{{ old('type', $paymentMethod->type ?? '') }}"
               placeholder="e.g. bank_transfer, card, ussd"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('type') border-red-400 @enderror">
        @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Provider --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Provider <span class="text-red-500">*</span></label>
        <input type="text" name="provider" value="{{ old('provider', $paymentMethod->provider ?? '') }}"
               placeholder="e.g. Paystack, GTBank, Flutterwave"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('provider') border-red-400 @enderror">
        @error('provider') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Sort --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Sort order</label>
        <input type="number" name="sort" value="{{ old('sort', $paymentMethod->sort ?? 0) }}" min="0"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
    </div>

    {{-- Account Name --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Account Name</label>
        <input type="text" name="account_name" value="{{ old('account_name', $paymentMethod->account_name ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
    </div>

    {{-- Account Number --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Account Number</label>
        <input type="text" name="account_number" value="{{ old('account_number', $paymentMethod->account_number ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
    </div>

    {{-- Bank Name --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Bank Name</label>
        <input type="text" name="bank_name" value="{{ old('bank_name', $paymentMethod->bank_name ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
    </div>

</div>

{{-- Instructions --}}
<div class="mt-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Instructions <span class="text-red-500">*</span></label>
    <textarea name="instructions" rows="4"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('instructions') border-red-400 @enderror">{{ old('instructions', $paymentMethod->instructions ?? '') }}</textarea>
    @error('instructions') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Toggles --}}
<div class="mt-5 flex flex-wrap items-center gap-6">
    <div class="flex items-center gap-3">
        <input type="hidden" name="active" value="0">
        <input type="checkbox" id="active" name="active" value="1"
               {{ old('active', $paymentMethod->active ?? true) ? 'checked' : '' }}
               class="h-4 w-4 rounded border-gray-300 text-[#1D5C52] focus:ring-[#1D5C52]">
        <label for="active" class="text-sm font-medium text-gray-700">Active</label>
    </div>

    <div class="flex items-center gap-3">
        <input type="hidden" name="accepts_commitment_fee" value="0">
        <input type="checkbox" id="accepts_commitment_fee" name="accepts_commitment_fee" value="1"
               {{ old('accepts_commitment_fee', $paymentMethod->accepts_commitment_fee ?? false) ? 'checked' : '' }}
               class="h-4 w-4 rounded border-gray-300 text-[#1D5C52] focus:ring-[#1D5C52]">
        <label for="accepts_commitment_fee" class="text-sm font-medium text-gray-700">Accepts Commitment Fee</label>
    </div>
</div>
