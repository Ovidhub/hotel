<x-layouts.admin title="Settings">

    <div class="mx-auto max-w-2xl space-y-6">

        {{-- WhatsApp chat button --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#25D366]">
                    <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm4.52 11.99c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.25-.64.81-.79.98-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.51.11-.11.25-.29.37-.43.13-.14.17-.25.25-.41.08-.16.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.85-.2-.48-.41-.42-.56-.43-.14-.01-.31-.01-.48-.01-.16 0-.43.06-.66.31-.23.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.16 1.75 2.67 4.24 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.11-.22-.17-.47-.29z"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-base font-semibold text-gray-800">WhatsApp Chat Button</h2>
                    <p class="text-sm text-gray-500">The floating green button guests tap to message you.</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-200">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" class="mt-5 space-y-5">
                @csrf
                @method('PUT')

                {{-- Enabled toggle --}}
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="whatsapp_enabled" value="1" @checked($whatsappEnabled)
                           class="h-4 w-4 rounded border-gray-300 text-[#25D366] focus:ring-[#25D366]">
                    <span class="text-sm font-medium text-gray-700">Show the WhatsApp button on the website</span>
                </label>

                <div>
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700">WhatsApp number</label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number"
                           value="{{ old('whatsapp_number', $whatsappNumber) }}"
                           placeholder="+234 813 406 2487"
                           class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#7C0E52] focus:ring-[#7C0E52]">
                    <p class="mt-1 text-xs text-gray-400">
                        Include the country code (e.g. <span class="font-mono">+234</span> for Nigeria). Leave empty to hide the button.
                    </p>
                </div>

                <div>
                    <label for="whatsapp_message" class="block text-sm font-medium text-gray-700">Pre-filled message</label>
                    <textarea id="whatsapp_message" name="whatsapp_message" rows="2"
                              placeholder="Hello Hotel Benizia, I would like to make an enquiry."
                              class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#7C0E52] focus:ring-[#7C0E52]">{{ old('whatsapp_message', $whatsappMessage) }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Text that appears in the guest's chat box when they tap the button.</p>
                </div>

                <div class="pt-1">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-[#7C0E52] px-4 py-2 text-sm font-medium text-white hover:bg-[#560A3A] transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-layouts.admin>
