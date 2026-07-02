<x-layouts.admin title="Booking {{ $booking->ref }}">

    <div class="mb-5 flex items-center gap-3">
        <a href="{{ route('admin.bookings.index') }}"
           class="text-sm text-[#7C0E52] hover:underline">&larr; All Bookings</a>
        <span class="text-gray-300">/</span>
        <span class="font-mono text-sm font-medium text-gray-700">{{ $booking->ref }}</span>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- Guest & booking info --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Guest details --}}
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Guest Information</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Name</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $booking->guest_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Email</dt>
                        <dd class="mt-1 text-gray-700">{{ $booking->guest_email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Phone</dt>
                        <dd class="mt-1 text-gray-700">{{ $booking->guest_phone }}</dd>
                    </div>
                    @if($booking->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Notes</dt>
                        <dd class="mt-1 text-gray-700 whitespace-pre-line">{{ $booking->notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Booking details --}}
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Booking Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Property</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $booking->bookable?->name ?? '—' }}</dd>
                        <dd class="text-xs text-gray-400">{{ class_basename($booking->bookable_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Guests</dt>
                        <dd class="mt-1 text-gray-700">{{ $booking->guests }} guest(s)</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Check-in</dt>
                        <dd class="mt-1 text-gray-700">{{ $booking->check_in?->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Check-out</dt>
                        <dd class="mt-1 text-gray-700">{{ $booking->check_out?->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Nights</dt>
                        <dd class="mt-1 text-gray-700">{{ $booking->nights }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Payment Method</dt>
                        <dd class="mt-1 text-gray-700">{{ $booking->paymentMethod?->name ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Financials --}}
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Financial Summary</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Total</dt>
                        <dd class="mt-1 text-xl font-bold text-gray-900">₦{{ number_format($booking->total) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Commitment Fee ({{ $booking->commitment_percent }}%)</dt>
                        <dd class="mt-1 text-lg font-semibold text-amber-700">₦{{ number_format($booking->commitment_fee) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Balance Due</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-700">₦{{ number_format($booking->balance_due) }}</dd>
                    </div>
                </dl>

                @if($proofUrl)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <dt class="text-xs text-gray-500 uppercase tracking-wider mb-2">Payment Proof</dt>
                    <a href="{{ $proofUrl }}" target="_blank"
                       class="inline-flex items-center gap-2 rounded-lg bg-[#7C0E52]/10 px-4 py-2 text-sm font-medium text-[#7C0E52] hover:bg-[#7C0E52]/20 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"/>
                        </svg>
                        View Payment Proof
                    </a>
                </div>
                @endif
            </div>

        </div>

        {{-- Status update panel --}}
        <div class="space-y-5">
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Update Status</h3>

                @php
                    $statusClasses = match($booking->status) {
                        'Confirmed'       => 'bg-emerald-100 text-emerald-700',
                        'Checked In'      => 'bg-blue-100 text-blue-700',
                        'Cancelled'       => 'bg-red-100 text-red-600',
                        'Pending Payment' => 'bg-amber-100 text-amber-700',
                        default           => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <div class="mb-4">
                    <span class="text-xs text-gray-500">Current:</span>
                    <span class="ml-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClasses }}">
                        {{ $booking->status }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">New Status</label>
                        <select name="status"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52] @error('status') border-red-400 @enderror">
                            @foreach(['Pending Payment','Confirmed','Checked In','Cancelled'] as $s)
                            <option value="{{ $s }}" {{ $booking->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                            class="w-full rounded-lg bg-[#7C0E52] px-4 py-2 text-sm font-medium text-white hover:bg-[#560A3A] transition-colors">
                        Save Status
                    </button>
                </form>
            </div>
        </div>

    </div>

</x-layouts.admin>
