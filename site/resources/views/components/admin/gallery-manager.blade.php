@props([
    'items' => [],   // array of ['value' => path, 'url' => resolved url]
])

<div class="mt-1" x-data="galleryManager({ existing: @js($items) })">
    <label class="block text-xs font-semibold text-gray-600 mb-1">
        Gallery Photos <span class="text-gray-400">(add, remove &amp; reorder)</span>
    </label>

    {{-- Submitted state: ordered token list + the real (rebuilt) file input --}}
    <input type="hidden" name="gallery_order" :value="orderJson()">
    <input type="file" x-ref="fileInput" name="gallery_files[]" accept="image/*" multiple class="hidden">
    {{-- Picker used only to choose new files; not submitted --}}
    <input type="file" x-ref="picker" accept="image/*" multiple class="hidden" @change="addFiles($event)">

    {{-- Thumbnails --}}
    <div x-show="items.length" class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
        <template x-for="(it, index) in items" :key="it.id">
            <div class="group relative overflow-hidden rounded-lg bg-gray-100 ring-1 ring-gray-200">
                <img :src="it.url" alt="Gallery image" class="h-28 w-full object-cover">

                {{-- Position badge --}}
                <span class="absolute left-1 top-1 rounded bg-black/60 px-1.5 py-0.5 text-[10px] font-semibold text-white"
                      x-text="'#' + (index + 1)"></span>

                {{-- Remove --}}
                <button type="button" @click="remove(it.id)" title="Remove"
                        class="absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-white shadow hover:bg-red-700">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Reorder controls --}}
                <div class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-0.5 bg-black/50 px-1 py-1
                            opacity-0 transition-opacity duration-150 group-hover:opacity-100">
                    <button type="button" @click="moveToStart(index)" :disabled="index === 0" title="Move to first"
                            class="rounded px-1 py-0.5 text-white hover:bg-white/20 disabled:opacity-30">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 17l-5-5 5-5M11 17l-5-5 5-5"/></svg>
                    </button>
                    <button type="button" @click="move(index, -1)" :disabled="index === 0" title="Move left"
                            class="rounded px-1 py-0.5 text-white hover:bg-white/20 disabled:opacity-30">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" @click="move(index, 1)" :disabled="index === items.length - 1" title="Move right"
                            class="rounded px-1 py-0.5 text-white hover:bg-white/20 disabled:opacity-30">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <button type="button" @click="moveToEnd(index)" :disabled="index === items.length - 1" title="Move to end"
                            class="rounded px-1 py-0.5 text-white hover:bg-white/20 disabled:opacity-30">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 17l5-5-5-5M13 17l5-5-5-5"/></svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    {{-- Empty state --}}
    <p x-show="!items.length"
       class="rounded-lg border border-dashed border-gray-300 px-3 py-5 text-center text-xs text-gray-400">
        No gallery images yet — add some below.
    </p>

    <div class="mt-3 flex items-center gap-3">
        <button type="button" @click="$refs.picker.click()"
                class="inline-flex items-center gap-2 rounded-lg border border-[#7C0E52] px-4 py-2 text-sm font-medium text-[#7C0E52] transition-colors hover:bg-[#7C0E52]/5">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add images
        </button>
        <span class="text-xs text-gray-400"><span x-text="items.length"></span> image(s)</span>
    </div>
    <p class="mt-1 text-xs text-gray-400">The first image (#1) leads the gallery. Hover a photo to reorder or remove it. JPG, PNG or WebP up to 16 MB each — optimized on upload.</p>
</div>
