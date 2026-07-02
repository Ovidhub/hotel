<x-layouts.admin title="Bookings">

    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-gray-800">All Bookings</h2>
    </div>

    {{-- Status filter --}}
    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.bookings.index') }}"
           class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium transition-colors
                  {{ !$currentStatus ? 'bg-[#7C0E52] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            All
        </a>
        @foreach($statuses as $s)
        <a href="{{ route('admin.bookings.index', ['status' => $s]) }}"
           class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium transition-colors
                  {{ $currentStatus === $s ? 'bg-[#7C0E52] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            {{ $s }}
        </a>
        @endforeach
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Ref</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Guest</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden md:table-cell">Property</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden lg:table-cell">Check-in / Out</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                @php
                    $statusClasses = match($booking->status) {
                        'Confirmed'       => 'bg-emerald-100 text-emerald-700',
                        'Checked In'      => 'bg-blue-100 text-blue-700',
                        'Cancelled'       => 'bg-red-100 text-red-600',
                        'Pending Payment' => 'bg-amber-100 text-amber-700',
                        default           => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3.5">
                        <span class="font-mono text-xs font-medium text-gray-700">{{ $booking->ref }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="font-medium text-gray-900">{{ $booking->guest_name }}</div>
                        <div class="text-xs text-gray-400">{{ $booking->guest_email }}</div>
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell">
                        <div class="text-gray-700">{{ $booking->bookable?->name ?? '—' }}</div>
                        <div class="text-xs text-gray-400">{{ class_basename($booking->bookable_type) }}</div>
                    </td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-gray-600 text-xs">
                        {{ $booking->check_in?->format('d M Y') }} →
                        {{ $booking->check_out?->format('d M Y') }}
                    </td>
                    <td class="px-5 py-3.5 text-right font-medium text-gray-900">
                        ₦{{ number_format($booking->total) }}
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClasses }}">
                            {{ $booking->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <a href="{{ route('admin.bookings.show', $booking) }}"
                           class="inline-flex items-center rounded-lg bg-[#7C0E52]/10 px-3 py-1.5 text-xs font-medium text-[#7C0E52] hover:bg-[#7C0E52]/20 transition-colors">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-sm text-gray-400">
                        No bookings{{ $currentStatus ? ' with status "' . $currentStatus . '"' : '' }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>
