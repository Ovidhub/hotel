@props([
    'title',
    'text'        => null,
    'buttonLabel' => 'Book Now',
    'buttonUrl'   => '#',
])

<section class="relative overflow-hidden bg-benizia-green py-20 px-4 text-white" aria-label="Call to action">
    {{-- Decorative background pattern --}}
    <div class="pointer-events-none absolute inset-0 opacity-5" aria-hidden="true">
        <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full border-2 border-benizia-gold"></div>
        <div class="absolute -left-10 -bottom-10 h-48 w-48 rounded-full border-2 border-benizia-gold"></div>
    </div>

    <div class="relative mx-auto max-w-3xl text-center">
        {{-- Gold accent --}}
        <div class="mb-6 flex justify-center">
            <span class="h-0.5 w-12 bg-benizia-gold"></span>
        </div>

        <h2 class="font-serif text-3xl leading-tight text-white md:text-5xl">
            {!! $title !!}
        </h2>

        @if($text)
            <p class="mt-5 text-base leading-7 text-white/75">
                {{ $text }}
            </p>
        @endif

        <a
            href="{{ $buttonUrl }}"
            class="mt-10 inline-flex items-center gap-2 rounded-full bg-benizia-gold px-8 py-4 text-sm font-bold text-white shadow-lg transition hover:bg-white hover:text-benizia-green hover:shadow-xl"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
            </svg>
            {{ $buttonLabel }}
        </a>
    </div>
</section>
