@props([
    'src',
    'poster',
    'eyebrow' => 'Video Tour',
    'title',
    'text' => null,
    'bg' => 'bg-white',
])

{{-- Styled video highlight. A branded poster + gold play button overlay;
     preload="none" means the (large) file only downloads once the visitor
     presses play, at which point native controls appear. --}}
<section class="py-20 px-4 {{ $bg }}" aria-label="{{ $title }}">
    <div class="mx-auto max-w-5xl">
        <x-section-intro :eyebrow="$eyebrow" :title="$title" :text="$text" />

        <div
            x-data="{ started: false }"
            class="group relative mt-10 aspect-video w-full overflow-hidden rounded-3xl bg-black shadow-2xl ring-1 ring-benizia-gold/30"
        >
            <video
                x-ref="video"
                class="absolute inset-0 h-full w-full object-contain"
                :controls="started"
                preload="none"
                playsinline
                poster="{{ $poster }}"
                @play="started = true"
            >
                <source src="{{ $src }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            {{-- Play overlay (hidden once the video starts) --}}
            <button
                type="button"
                x-show="!started"
                x-transition.opacity.duration.300ms
                @click="$refs.video.play()"
                class="absolute inset-0 flex flex-col items-center justify-center gap-4
                       bg-gradient-to-t from-black/70 via-black/25 to-black/40
                       transition hover:from-black/60"
                aria-label="Play video"
            >
                <span class="flex h-20 w-20 items-center justify-center rounded-full bg-benizia-gold
                             text-white shadow-2xl ring-8 ring-white/10
                             transition duration-300 group-hover:scale-110">
                    <svg class="h-8 w-8 translate-x-0.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </span>
                <span class="text-xs font-semibold uppercase tracking-[0.3em] text-white/90">Play Video</span>
            </button>
        </div>
    </div>
</section>
