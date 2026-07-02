{{-- Shared form partial for apartment create & edit --}}
@php $isEdit = isset($apartment); @endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

    {{-- Name --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $apartment->name ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52] @error('name') border-red-400 @enderror">
        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Slug --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Slug <span class="text-gray-400">(auto-generated if blank)</span></label>
        <input type="text" name="slug" value="{{ old('slug', $apartment->slug ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52] @error('slug') border-red-400 @enderror">
        @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Type --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Type <span class="text-red-500">*</span></label>
        <input type="text" name="type" value="{{ old('type', $apartment->type ?? '') }}"
               placeholder="e.g. Studio, 1-Bedroom, Penthouse"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52] @error('type') border-red-400 @enderror">
        @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Status --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Status <span class="text-red-500">*</span></label>
        <select name="status"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52] @error('status') border-red-400 @enderror">
            @foreach(['Available', 'Occupied', 'Maintenance'] as $opt)
                <option value="{{ $opt }}" {{ old('status', $apartment->status ?? 'Available') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
        </select>
        @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Price --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Price (NGN) <span class="text-red-500">*</span></label>
        <input type="number" name="price" value="{{ old('price', $apartment->price ?? '') }}" min="0"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52] @error('price') border-red-400 @enderror">
        @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Bedrooms / Bathrooms / Occupancy --}}
    <div class="grid grid-cols-3 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Bedrooms <span class="text-red-500">*</span></label>
            <input type="number" name="bedrooms" value="{{ old('bedrooms', $apartment->bedrooms ?? 1) }}" min="0"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52]">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Bathrooms <span class="text-red-500">*</span></label>
            <input type="number" name="bathrooms" value="{{ old('bathrooms', $apartment->bathrooms ?? 1) }}" min="0"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52]">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Occupancy <span class="text-red-500">*</span></label>
            <input type="number" name="occupancy" value="{{ old('occupancy', $apartment->occupancy ?? 2) }}" min="1"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52]">
        </div>
    </div>

    {{-- Sort --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Sort order</label>
        <input type="number" name="sort" value="{{ old('sort', $apartment->sort ?? 0) }}" min="0"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52]">
    </div>

    {{-- Units (inventory) --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Units available</label>
        <input type="number" name="units" value="{{ old('units', $apartment->units ?? 1) }}" min="1"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52]">
        <p class="mt-1 text-xs text-gray-400">How many identical units of this apartment exist. A date is full when bookings reach this number.</p>
    </div>

</div>

{{-- Main image (upload) --}}
<div class="mt-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">
        Main Image @if(!isset($apartment) || !$apartment->image)<span class="text-red-500">*</span>@endif
    </label>
    @if(isset($apartment) && $apartment->imageUrl())
        <div class="mb-2 flex items-center gap-3">
            <img src="{{ $apartment->imageUrl() }}" alt="Current image" class="h-16 w-24 rounded-lg object-cover ring-1 ring-gray-200">
            <span class="text-xs text-gray-500">Current image — upload a new file to replace it.</span>
        </div>
    @endif
    <input type="file" name="image_file" accept="image/*"
           class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-[#7C0E52] file:px-4 file:py-2 file:text-white hover:file:bg-[#560A3A] @error('image_file') border-red-400 @enderror">
    <p class="mt-1 text-xs text-gray-400">JPG, PNG or WebP, up to 4 MB. Photos are automatically sharpened, colour-corrected, resized &amp; optimized on upload.</p>
    @error('image_file') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Description --}}
<div class="mt-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Description <span class="text-red-500">*</span></label>
    <textarea name="description" rows="5"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52] @error('description') border-red-400 @enderror">{{ old('description', $apartment->description ?? '') }}</textarea>
    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- List fields --}}
<div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Amenities <span class="text-gray-400">(one per line)</span></label>
        <textarea name="amenities" rows="4"
                  class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52]">{{ old('amenities', implode("\n", $apartment->amenities ?? [])) }}</textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Gallery Photos <span class="text-gray-400">(upload one or more)</span></label>
        @if(isset($apartment) && count($apartment->gallery ?? []))
            <div class="mb-2 flex flex-wrap gap-2">
                @foreach($apartment->galleryUrls() as $g)
                    <img src="{{ $g }}" alt="Gallery image" class="h-12 w-16 rounded-md object-cover ring-1 ring-gray-200">
                @endforeach
            </div>
            <p class="mb-2 text-xs text-gray-400">Current gallery (incl. main image). New uploads are added to it.</p>
        @endif
        <input type="file" name="gallery_files[]" accept="image/*" multiple
               class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-[#7C0E52] file:px-4 file:py-2 file:text-white hover:file:bg-[#560A3A]">
        @error('gallery_files.*') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror

        <details class="mt-3">
            <summary class="cursor-pointer text-xs font-semibold text-gray-500">Advanced: gallery image URLs (one per line)</summary>
            <textarea name="gallery" rows="3"
                      class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52]">{{ old('gallery', implode("\n", $apartment->gallery ?? [])) }}</textarea>
        </details>
    </div>
</div>

{{-- is_active --}}
<div class="mt-5 flex items-center gap-3">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" id="is_active" name="is_active" value="1"
           {{ old('is_active', $apartment->is_active ?? true) ? 'checked' : '' }}
           class="h-4 w-4 rounded border-gray-300 text-[#7C0E52] focus:ring-[#7C0E52]">
    <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible on public site)</label>
</div>
