<x-layouts.admin title="Booking Inquiry">
    <div class="mx-auto max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2 text-sm">
            <div><dt class="text-xs uppercase text-gray-500">Name</dt><dd>{{ $inquiry->name }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Email</dt><dd>{{ $inquiry->email }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Phone</dt><dd>{{ $inquiry->phone ?: '—' }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Room</dt><dd>{{ $inquiry->room }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Check-in</dt><dd>{{ $inquiry->check_in }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Check-out</dt><dd>{{ $inquiry->check_out }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Guests</dt><dd>{{ $inquiry->guests }}</dd></div>
        </dl>
        <div class="mt-4"><dt class="text-xs uppercase text-gray-500">Message</dt><dd class="mt-1 text-sm">{{ $inquiry->message ?: '—' }}</dd></div>
        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.booking-inquiries.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Back</a>
            <form method="POST" action="{{ route('admin.booking-inquiries.destroy', $inquiry) }}" onsubmit="return confirm('Delete this inquiry?')">
                @csrf @method('DELETE')
                <button class="text-sm text-red-600 hover:underline">Delete</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
