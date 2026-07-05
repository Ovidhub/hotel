@php
    use App\Models\Setting;

    // Admin-editable settings, falling back to the hotel's default phone.
    $enabled = Setting::get('whatsapp_enabled', '1') === '1';
    $raw     = Setting::get('whatsapp_number', config('hotel.phone_href'));
    // wa.me needs an international number with no +, spaces or punctuation.
    $number  = preg_replace('/\D/', '', (string) $raw);
    $message = Setting::get('whatsapp_message', 'Hello Hotel Benizia, I would like to make an enquiry.');
    $href    = 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
@endphp

@if($enabled && $number !== '')
    <a href="{{ $href }}"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="Chat with Hotel Benizia on WhatsApp"
       class="group fixed bottom-5 right-5 z-[60] flex items-center gap-3">

        {{-- Hover label (desktop) --}}
        <span class="pointer-events-none hidden max-w-0 overflow-hidden whitespace-nowrap rounded-full bg-white/95 px-0 py-2 text-sm font-medium text-benizia-charcoal shadow-lg ring-1 ring-black/5 transition-all duration-300 group-hover:max-w-xs group-hover:px-4 sm:block">
            Chat with us
        </span>

        {{-- Button --}}
        <span class="relative flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] shadow-xl ring-1 ring-black/5 transition-transform duration-200 group-hover:scale-105">
            {{-- Subtle pulse ring --}}
            <span class="absolute inset-0 rounded-full bg-[#25D366] motion-safe:animate-ping opacity-40"></span>
            <svg class="relative h-7 w-7 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.23 8.23 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.83 2.42a8.19 8.19 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.24 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.25-.64.81-.79.98-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.51.11-.11.25-.29.37-.43.13-.14.17-.25.25-.41.08-.16.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.85-.2-.48-.41-.42-.56-.43-.14-.01-.31-.01-.48-.01-.16 0-.43.06-.66.31-.23.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.16 1.75 2.67 4.24 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.11-.22-.17-.47-.29z"/>
            </svg>
        </span>
    </a>
@endif
