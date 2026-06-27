{{-- Shared form partial for create & edit --}}
{{-- $room is set on edit; on create use old() only --}}

@php $isEdit = isset($room); @endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

    {{-- Name --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $room->name ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('name') border-red-400 @enderror">
        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Slug (optional, auto-derived) --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Slug <span class="text-gray-400">(auto-generated if blank)</span></label>
        <input type="text" name="slug" value="{{ old('slug', $room->slug ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('slug') border-red-400 @enderror">
        @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Category --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Category <span class="text-red-500">*</span></label>
        <input type="text" name="category" value="{{ old('category', $room->category ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('category') border-red-400 @enderror">
        @error('category') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Size --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Size</label>
        <input type="text" name="size" value="{{ old('size', $room->size ?? '') }}"
               placeholder="e.g. Large, 35m²"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
    </div>

    {{-- Price --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Price (NGN) <span class="text-red-500">*</span></label>
        <input type="number" name="price" value="{{ old('price', $room->price ?? '') }}" min="0"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('price') border-red-400 @enderror">
        @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Guests / Beds / Baths --}}
    <div class="grid grid-cols-3 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Guests <span class="text-red-500">*</span></label>
            <input type="number" name="guests" value="{{ old('guests', $room->guests ?? 1) }}" min="1"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Beds <span class="text-red-500">*</span></label>
            <input type="number" name="beds" value="{{ old('beds', $room->beds ?? 1) }}" min="1"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Baths</label>
            <input type="number" name="baths" value="{{ old('baths', $room->baths ?? '') }}" min="0"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
        </div>
    </div>

    {{-- Rating / Reviews --}}
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Rating (0–5)</label>
            <input type="number" name="rating" value="{{ old('rating', $room->rating ?? '') }}" min="0" max="5" step="0.1"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Reviews count</label>
            <input type="number" name="reviews" value="{{ old('reviews', $room->reviews ?? 0) }}" min="0"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
        </div>
    </div>

    {{-- Sort --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Sort order</label>
        <input type="number" name="sort" value="{{ old('sort', $room->sort ?? 0) }}" min="0"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
    </div>

</div>

{{-- Image URL --}}
<div class="mt-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Image URL <span class="text-red-500">*</span></label>
    <input type="text" name="image" value="{{ old('image', $room->image ?? '') }}"
           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('image') border-red-400 @enderror">
    @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Excerpt --}}
<div class="mt-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Excerpt <span class="text-red-500">*</span></label>
    <textarea name="excerpt" rows="2"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('excerpt') border-red-400 @enderror">{{ old('excerpt', $room->excerpt ?? '') }}</textarea>
    @error('excerpt') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Description --}}
<div class="mt-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Description <span class="text-red-500">*</span></label>
    <textarea name="description" rows="5"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('description') border-red-400 @enderror">{{ old('description', $room->description ?? '') }}</textarea>
    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- List fields --}}
<div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">

    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Amenities <span class="text-gray-400">(one per line)</span></label>
        <textarea name="amenities" rows="4"
                  class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">{{ old('amenities', implode("\n", $room->amenities ?? [])) }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Gallery URLs <span class="text-gray-400">(one per line)</span></label>
        <textarea name="gallery" rows="4"
                  class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">{{ old('gallery', implode("\n", $room->gallery ?? [])) }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Includes <span class="text-gray-400">(one per line)</span></label>
        <textarea name="includes" rows="4"
                  class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">{{ old('includes', implode("\n", $room->includes ?? [])) }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Policies <span class="text-gray-400">(one per line)</span></label>
        <textarea name="policies" rows="4"
                  class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">{{ old('policies', implode("\n", $room->policies ?? [])) }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Best For <span class="text-gray-400">(one per line)</span></label>
        <textarea name="best_for" rows="4"
                  class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">{{ old('best_for', implode("\n", $room->best_for ?? [])) }}</textarea>
    </div>

</div>

{{-- is_active checkbox --}}
<div class="mt-5 flex items-center gap-3">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" id="is_active" name="is_active" value="1"
           {{ old('is_active', $room->is_active ?? true) ? 'checked' : '' }}
           class="h-4 w-4 rounded border-gray-300 text-[#1D5C52] focus:ring-[#1D5C52]">
    <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible on public site)</label>
</div>
