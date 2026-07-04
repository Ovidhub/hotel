@props([
    'src',
    'poster',
    'eyebrow' => 'Video Tour',
    'title',
    'text' => null,
    'bg' => 'bg-white',
])

{{-- Video highlight. Uses preload="none" so the (large) file only downloads
     when the visitor presses play; the poster image shows until then. --}}
<section class="py-20 px-4 {{ $bg }}" aria-label="{{ $title }}">
    <div class="mx-auto max-w-5xl">
        <x-section-intro :eyebrow="$eyebrow" :title="$title" :text="$text" />

        <div class="mt-10 overflow-hidden rounded-3xl bg-benizia-charcoal shadow-2xl ring-1 ring-black/5">
            <video
                class="mx-auto block max-h-[80vh] w-full bg-black"
                controls
                preload="none"
                playsinline
                poster="{{ $poster }}"
            >
                <source src="{{ $src }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</section>
