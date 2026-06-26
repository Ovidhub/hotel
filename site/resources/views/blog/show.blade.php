<x-layouts.app
    :title="$post->title . ' — Hotel Benizia Blog'"
    :description="$post->excerpt ?? 'Read ' . $post->title . ' on the Hotel Benizia blog.'">

    <x-page-hero
        :title="$post->title"
        :image="$post->image"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog', 'url' => route('blog.index')],
            ['label' => $post->title],
        ]"
    />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-3xl">

            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-4 mb-8 text-sm text-gray-400">
                @if($post->category)
                    <span class="inline-block rounded-full bg-benizia-cream px-3 py-1 text-xs font-semibold text-benizia-green">{{ $post->category }}</span>
                @endif
                <span>By {{ $post->author ?? 'Hotel Benizia' }}</span>
                @if($post->published_at)
                    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('F d, Y') }}</time>
                @endif
            </div>

            {{-- Excerpt --}}
            @if($post->excerpt)
                <p class="text-lg text-gray-600 leading-relaxed mb-8 border-l-4 border-benizia-gold pl-4 italic">
                    {{ $post->excerpt }}
                </p>
            @endif

            {{-- Body --}}
            <div class="prose prose-lg max-w-none text-gray-700">
                {!! nl2br(e($post->body)) !!}
            </div>

            {{-- Back link --}}
            <div class="mt-12">
                <a href="{{ route('blog.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-benizia-green hover:text-benizia-charcoal transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    Back to Blog
                </a>
            </div>
        </div>
    </section>

    <x-cta title="Stay at Hotel Benizia, Asaba" text="Rooms from ₦30,000/night. Breakfast and pool access included with every booking." />

</x-layouts.app>
