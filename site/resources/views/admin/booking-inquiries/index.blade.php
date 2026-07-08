<x-layouts.admin title="Booking Inquiries">
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wider text-gray-500">
                    <th class="px-3 py-2">Name</th><th class="px-3 py-2">Room</th>
                    <th class="px-3 py-2 hidden md:table-cell">Dates</th>
                    <th class="px-3 py-2">Status</th><th class="px-3 py-2 text-right">View</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries as $i)
                    <tr class="border-t border-gray-100">
                        <td class="px-3 py-2">{{ $i->name }}<div class="text-xs text-gray-400">{{ $i->email }}</div></td>
                        <td class="px-3 py-2">{{ $i->room }}</td>
                        <td class="px-3 py-2 hidden md:table-cell">{{ $i->check_in }} → {{ $i->check_out }}</td>
                        <td class="px-3 py-2"><span class="rounded-full px-2 py-0.5 text-xs {{ $i->status === 'new' ? 'bg-[#7C0E52]/10 text-[#7C0E52]' : 'bg-gray-100 text-gray-500' }}">{{ $i->status }}</span></td>
                        <td class="px-3 py-2 text-right"><a href="{{ route('admin.booking-inquiries.show', $i) }}" class="text-[#7C0E52] hover:underline">Open</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400">No inquiries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $inquiries->links() }}</div>
    </div>
</x-layouts.admin>
