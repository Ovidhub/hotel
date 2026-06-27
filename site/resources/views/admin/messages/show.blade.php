<x-layouts.admin title="Message from {{ $message->name }}">

    <div class="mb-5 flex items-center gap-3">
        <a href="{{ route('admin.messages.index') }}" class="text-sm text-[#1D5C52] hover:underline">&larr; All Messages</a>
    </div>

    <div class="max-w-2xl">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-6">

            <div class="flex items-start justify-between mb-5">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">{{ $message->subject }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $message->created_at->format('d M Y \a\t H:i') }}</p>
                </div>
                @if ($message->read_at)
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700">
                        Read {{ $message->read_at->format('d M Y') }}
                    </span>
                @else
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">
                        Unread
                    </span>
                @endif
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5 text-sm border-b border-gray-100 pb-5">
                <div>
                    <dt class="text-xs text-gray-500 uppercase tracking-wider">Name</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $message->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase tracking-wider">Email</dt>
                    <dd class="mt-1 text-gray-700">
                        <a href="mailto:{{ $message->email }}" class="text-[#1D5C52] hover:underline">{{ $message->email }}</a>
                    </dd>
                </div>
                @if($message->phone)
                <div>
                    <dt class="text-xs text-gray-500 uppercase tracking-wider">Phone</dt>
                    <dd class="mt-1 text-gray-700">{{ $message->phone }}</dd>
                </div>
                @endif
            </dl>

            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-2">Message</dt>
                <dd class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $message->message }}</dd>
            </div>

            <div class="mt-6 flex items-center justify-between pt-5 border-t border-gray-100">
                <a href="{{ route('admin.messages.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 hover:underline">&larr; Back to Messages</a>

                <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                      onsubmit="return confirm('Delete this message permanently?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-100 transition-colors">
                        Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-layouts.admin>
