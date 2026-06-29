<x-layouts.admin title="Availability">
    <div class="mb-6">
        <h2 class="text-base font-semibold text-gray-800">Availability</h2>
        <p class="mt-1 text-sm text-gray-500">Open or block dates per room and apartment. Guest bookings automatically block dates too.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Rooms --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <h3 class="mb-4 text-sm font-semibold text-gray-700">Rooms</h3>
            <ul class="divide-y divide-gray-100">
                @forelse($rooms as $room)
                    <li class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $room->name }}</p>
                            <p class="text-xs text-gray-400">{{ $room->units }} unit{{ $room->units === 1 ? '' : 's' }}</p>
                        </div>
                        <a href="{{ route('admin.availability.show', ['type' => 'room', 'id' => $room->id]) }}"
                           class="rounded-lg bg-[#1D5C52] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#16463f]">
                            Manage dates
                        </a>
                    </li>
                @empty
                    <li class="py-3 text-sm text-gray-400">No rooms yet.</li>
                @endforelse
            </ul>
        </div>

        {{-- Apartments --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <h3 class="mb-4 text-sm font-semibold text-gray-700">Apartments</h3>
            <ul class="divide-y divide-gray-100">
                @forelse($apartments as $apartment)
                    <li class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $apartment->name }}</p>
                            <p class="text-xs text-gray-400">{{ $apartment->units }} unit{{ $apartment->units === 1 ? '' : 's' }}</p>
                        </div>
                        <a href="{{ route('admin.availability.show', ['type' => 'apartment', 'id' => $apartment->id]) }}"
                           class="rounded-lg bg-[#1D5C52] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#16463f]">
                            Manage dates
                        </a>
                    </li>
                @empty
                    <li class="py-3 text-sm text-gray-400">No apartments yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-layouts.admin>
