{{-- Shared form partial for blog create & edit --}}
{{-- $post is set on edit; on create use old() only --}}

@php $isEdit = isset($post); @endphp

<div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

    {{-- Title --}}
    <div class="lg:col-span-2">
        <label class="block text-xs font-semibold text-gray-600 mb-1">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('title') border-red-400 @enderror">
        @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Category --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Category <span class="text-red-500">*</span></label>
        <input type="text" name="category" value="{{ old('category', $post->category ?? '') }}"
               placeholder="e.g. News, Events, Tips"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('category') border-red-400 @enderror">
        @error('category') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Category Color --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Category Color <span class="text-gray-400">(optional hex)</span></label>
        <input type="text" name="category_color" value="{{ old('category_color', $post->category_color ?? '') }}"
               placeholder="#1D5C52"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
    </div>

    {{-- Author --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Author <span class="text-red-500">*</span></label>
        <input type="text" name="author" value="{{ old('author', $post->author ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('author') border-red-400 @enderror">
        @error('author') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Published At --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Publish Date <span class="text-gray-400">(leave blank = publish now)</span></label>
        <input type="datetime-local" name="published_at"
               value="{{ old('published_at', $isEdit && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
    </div>

</div>

{{-- Image URL --}}
<div class="mt-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Image URL <span class="text-red-500">*</span></label>
    <input type="text" name="image" value="{{ old('image', $post->image ?? '') }}"
           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('image') border-red-400 @enderror">
    @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Excerpt --}}
<div class="mt-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Excerpt <span class="text-red-500">*</span></label>
    <textarea name="excerpt" rows="2"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('excerpt') border-red-400 @enderror">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    @error('excerpt') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Body --}}
<div class="mt-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Body <span class="text-red-500">*</span></label>
    <textarea name="body" rows="10"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('body') border-red-400 @enderror">{{ old('body', $post->body ?? '') }}</textarea>
    @error('body') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>
