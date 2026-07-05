@props([
    'current'  => null,   // URL of the existing main image, or null
    'required' => false,
])

<div class="mt-5" x-data="{ preview: null }">
    <label class="block text-xs font-semibold text-gray-600 mb-1">
        Main Image @if($required)<span class="text-red-500">*</span>@endif
    </label>

    <div class="mb-2 flex items-center gap-3">
        <div class="relative h-20 w-28 shrink-0 overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
            {{-- New selection preview (instant) --}}
            <img x-show="preview" x-cloak :src="preview" alt="New image preview"
                 class="absolute inset-0 h-full w-full object-cover">

            {{-- Existing / placeholder shown until a new file is chosen --}}
            @if($current)
                <img x-show="!preview" src="{{ $current }}" alt="Current image"
                     class="absolute inset-0 h-full w-full object-cover">
            @else
                <div x-show="!preview"
                     class="flex h-full w-full items-center justify-center text-[10px] text-gray-400">
                    No image
                </div>
            @endif
        </div>

        <span class="text-xs text-gray-500"
              x-text="preview
                ? 'New image selected — it will be saved when you submit.'
                : '{{ $current ? 'Current image — upload a new file to replace it.' : 'Upload the main image.' }}'">
        </span>
    </div>

    <input type="file" name="image_file" accept="image/*"
           @change="preview = ($event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null)"
           class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-[#7C0E52] file:px-4 file:py-2 file:text-white hover:file:bg-[#560A3A] @error('image_file') border-red-400 @enderror">
    <p class="mt-1 text-xs text-gray-400">JPG, PNG or WebP, up to 4 MB. Photos are automatically sharpened, colour-corrected, resized &amp; optimized on upload.</p>
    @error('image_file') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>
