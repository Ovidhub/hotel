<x-layouts.admin title="Messages">

    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-gray-800">Contact Messages</h2>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">From</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden md:table-cell">Subject</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden lg:table-cell">Received</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($messages as $message)
                <tr class="hover:bg-gray-50/50 transition-colors {{ is_null($message->read_at) ? 'bg-amber-50/30' : '' }}">
                    <td class="px-5 py-3.5">
                        <div class="font-medium text-gray-900 {{ is_null($message->read_at) ? 'font-semibold' : '' }}">
                            {{ $message->name }}
                        </div>
                        <div class="text-xs text-gray-400">{{ $message->email }}</div>
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell text-gray-700">{{ $message->subject }}</td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-gray-500 text-xs">
                        {{ $message->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if(is_null($message->read_at))
                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">Unread</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500">Read</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.messages.show', $message) }}"
                               class="inline-flex items-center rounded-lg bg-[#7C0E52]/10 px-3 py-1.5 text-xs font-medium text-[#7C0E52] hover:bg-[#7C0E52]/20 transition-colors">
                                View
                            </a>
                            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                                  onsubmit="return confirm('Delete this message?')">
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
                    <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-400">No messages yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>
