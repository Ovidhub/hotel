<x-layouts.admin title="Rooms">

    {{-- Flash --}}
    @if(session('status'))
        <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700 ring-1 ring-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-gray-800">All Rooms</h2>
        <a href="{{ route('admin.rooms.create') }}"
           class="inline-flex items-center gap-1.5 rounded-lg bg-[#1D5C52] px-4 py-2 text-sm font-medium text-white hover:bg-[#164840] transition-colors">
            + New Room
        </a>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden md:table-cell">Category</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden lg:table-cell">Price</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Active</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rooms as $room)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="font-medium text-gray-900">{{ $room->name }}</div>
                        <div class="text-xs text-gray-400">{{ $room->slug }}</div>
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell text-gray-600">{{ $room->category }}</td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-gray-700 font-medium">{{ $room->price_label }}</td>
                    <td class="px-5 py-3.5 text-center">
                        @if($room->is_active)
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700">Active</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.rooms.edit', $room) }}"
                               class="inline-flex items-center rounded-lg bg-[#C8922A]/10 px-3 py-1.5 text-xs font-medium text-[#C8922A] hover:bg-[#C8922A]/20 transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($room->name) }}? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-400">No rooms yet. <a href="{{ route('admin.rooms.create') }}" class="text-[#1D5C52] underline">Add one.</a></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>
