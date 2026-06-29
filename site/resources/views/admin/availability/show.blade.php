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

    {{-- Booking.com / OTA calendar sync --}}
    <div class="mt-6 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <h3 class="mb-1 text-sm font-semibold text-gray-700">Booking.com calendar sync (iCal)</h3>
        <p class="mb-5 text-xs text-gray-500">Two-way date-level sync. Give Booking.com the export link below, and paste Booking.com's calendar link into the import box. Imports refresh hourly (or use “Sync now”).</p>

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Export --}}
            <div>
                <h4 class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Export → paste into Booking.com</h4>
                <div class="flex items-center gap-2" x-data="{ copied: false }">
                    <input type="text" readonly value="{{ $bookable->icalUrl() }}" x-ref="exp"
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-xs text-gray-700">
                    <button type="button"
                            x-on:click="navigator.clipboard.writeText($refs.exp.value); copied = true; setTimeout(() => copied = false, 1500)"
                            class="shrink-0 rounded-lg bg-[#1D5C52] px-3 py-2 text-xs font-semibold text-white hover:bg-[#16463f]">
                        <span x-show="!copied">Copy</span><span x-show="copied" x-cloak>Copied!</span>
                    </button>
                </div>
                <p class="mt-2 text-xs text-gray-400">This calendar publishes this {{ $type }}'s booked &amp; blocked dates.</p>
            </div>

            {{-- Import --}}
            <div>
                <h4 class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Import ← Booking.com calendar link</h4>
                <form method="POST" action="{{ route('admin.availability.feeds.store', ['type' => $type, 'id' => $bookable->id]) }}" class="space-y-3">
                    @csrf
                    <input type="text" name="label" value="{{ old('label') }}" placeholder="Label (e.g. Booking.com)"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
                    <input type="url" name="url" value="{{ old('url') }}" placeholder="https://ical.booking.com/v1/export?..." required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('url') border-red-400 @enderror">
                    @error('url') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    <button type="submit" class="rounded-lg bg-[#1D5C52] px-4 py-2 text-sm font-semibold text-white hover:bg-[#16463f]">Add &amp; sync feed</button>
                </form>

                <ul class="mt-4 divide-y divide-gray-100">
                    @forelse($feeds as $feed)
                        <li class="py-2.5 text-sm">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-medium text-gray-800">{{ $feed->label }}</span>
                                <div class="flex items-center gap-3">
                                    <form method="POST" action="{{ route('admin.availability.feeds.sync', $feed) }}">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold text-[#1D5C52] hover:underline">Sync now</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.availability.feeds.destroy', $feed) }}" onsubmit='return confirm("Remove this feed and its imported blocks?")'>
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">Remove</button>
                                    </form>
                                </div>
                            </div>
                            <p class="mt-0.5 truncate text-xs text-gray-400">{{ $feed->url }}</p>
                            <p class="text-[11px] text-gray-400">
                                @if($feed->last_error)
                                    <span class="text-red-500">Last sync failed: {{ $feed->last_error }}</span>
                                @elseif($feed->last_synced_at)
                                    Last synced {{ $feed->last_synced_at->diffForHumans() }}
                                @else
                                    Not synced yet
                                @endif
                            </p>
                        </li>
                    @empty
                        <li class="py-2.5 text-xs text-gray-400">No import feeds yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-layouts.admin>
