<x-layouts.admin :title="'Availability — ' . $bookable->name">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.availability.index') }}" class="text-xs font-semibold text-[#1D5C52] hover:underline">&larr; All availability</a>
            <h2 class="mt-1 text-base font-semibold text-gray-800">{{ $bookable->name }}</h2>
            <p class="text-sm text-gray-500">{{ $bookable->units }} unit{{ $bookable->units === 1 ? '' : 's' }} — a date is full when bookings reach this number, or when you block it below.</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Block dates --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <h3 class="mb-4 text-sm font-semibold text-gray-700">Block dates</h3>
            <form method="POST" action="{{ route('admin.availability.blocks.store', ['type' => $type, 'id' => $bookable->id]) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">From</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('start_date') border-red-400 @enderror">
                        @error('start_date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">To</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('end_date') border-red-400 @enderror">
                        @error('end_date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Reason <span class="text-gray-400">(optional)</span></label>
                    <input type="text" name="reason" value="{{ old('reason') }}" placeholder="e.g. Maintenance, Booked on Booking.com"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
                </div>
                <button type="submit" class="rounded-lg bg-[#1D5C52] px-4 py-2 text-sm font-semibold text-white hover:bg-[#16463f]">
                    Block these dates
                </button>
            </form>

            <h4 class="mt-6 mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Current blocks</h4>
            <ul class="divide-y divide-gray-100">
                @forelse($blocks as $block)
                    <li class="flex items-center justify-between py-2.5 text-sm">
                        <span>
                            <span class="font-medium text-gray-800">{{ $block->start_date->format('d M Y') }} – {{ $block->end_date->format('d M Y') }}</span>
                            @if($block->reason)<span class="text-gray-400"> · {{ $block->reason }}</span>@endif
                            <span class="ml-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] uppercase text-gray-500">{{ $block->source }}</span>
                        </span>
                        <form method="POST" action="{{ route('admin.availability.blocks.destroy', $block) }}"
                              onsubmit='return confirm("Remove this block?")'>
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">Remove</button>
                        </form>
                    </li>
                @empty
                    <li class="py-2.5 text-sm text-gray-400">No blocked dates.</li>
                @endforelse
            </ul>
        </div>

        {{-- Upcoming bookings (read-only) --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <h3 class="mb-4 text-sm font-semibold text-gray-700">Upcoming bookings</h3>
            <ul class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                    <li class="flex items-center justify-between py-2.5 text-sm">
                        <span>
                            <span class="font-medium text-gray-800">{{ $booking->check_in->format('d M Y') }} – {{ $booking->check_out->format('d M Y') }}</span>
                            <span class="text-gray-400"> · {{ $booking->guest_name }}</span>
                        </span>
                        <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold uppercase text-emerald-700">{{ $booking->status }}</span>
                    </li>
                @empty
                    <li class="py-2.5 text-sm text-gray-400">No upcoming bookings.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-layouts.admin>
