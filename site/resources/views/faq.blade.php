<x-layouts.app
    title="Frequently Asked Questions"
    description="Common questions about staying at Hotel Benizia in Asaba, Delta State. Check-in times, breakfast, pool access, events, reservations, and more answered here.">

    <x-page-hero
        title="Frequently Asked Questions"
        subtitle="Answers to common questions about rooms, reservations, amenities, events, and staying at Hotel Benizia in Asaba, Delta State."
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'FAQ']]"
    />

    <x-schema.breadcrumb :items="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'FAQ', 'url' => route('faq')],
    ]" />

    {{-- FAQ Schema --}}
    <x-schema.faq :faqs="$faqs->map(fn($f) => ['question' => $f->question, 'answer' => $f->answer])->toArray()" />

    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-3xl">
            <x-section-intro
                eyebrow="FAQ"
                title="Common Guest Questions"
                text="Everything you need to know before your stay at Hotel Benizia in Asaba."
            />

            <div class="mt-12 space-y-4" x-data="{ open: null }">
                @foreach($faqs as $index => $faq)
                    <div
                        class="rounded-2xl bg-benizia-cream ring-1 ring-gray-100 overflow-hidden"
                        x-data="{ id: {{ $index }} }"
                    >
                        <button
                            type="button"
                            class="flex w-full cursor-pointer items-center justify-between p-6 font-semibold text-benizia-charcoal text-left hover:text-benizia-green transition"
                            @click="open === id ? open = null : open = id"
                            :aria-expanded="open === id"
                        >
                            <span>{{ $faq->question }}</span>
                            <svg
                                class="h-5 w-5 flex-shrink-0 text-benizia-gold transition-transform duration-300"
                                :class="open === id ? 'rotate-180' : ''"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </button>
                        <div
                            x-show="open === id"
                            x-collapse
                            class="px-6 pb-6 text-gray-600 leading-relaxed"
                        >
                            {{ $faq->answer }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <p class="text-gray-600 mb-4">Still have questions?</p>
                <a href="{{ route('contact') }}"
                   class="inline-block rounded-full bg-benizia-green px-8 py-3 font-bold text-white transition hover:bg-benizia-charcoal">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    <x-cta title="Have More Questions?" text="Our team is available 24 hours. Call or email us and we'll be happy to help plan your stay in Asaba." />

</x-layouts.app>
