{{-- Shared form for testimonial create & edit --}}
@php $isEdit = isset($testimonial); @endphp

<div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

    {{-- Name --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $testimonial->name ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52] @error('name') border-red-400 @enderror">
        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Role --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Role / Description <span class="text-red-500">*</span></label>
        <input type="text" name="role" value="{{ old('role', $testimonial->role ?? '') }}"
               placeholder="e.g. Business Traveller, Couple"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52] @error('role') border-red-400 @enderror">
        @error('role') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Rating --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Rating <span class="text-red-500">*</span></label>
        <select name="rating"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52] @error('rating') border-red-400 @enderror">
            @for($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}" {{ (int) old('rating', $testimonial->rating ?? 5) === $i ? 'selected' : '' }}>
                {{ $i }} Star{{ $i > 1 ? 's' : '' }}
            </option>
            @endfor
        </select>
        @error('rating') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Avatar --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Avatar URL <span class="text-gray-400">(optional)</span></label>
        <input type="text" name="avatar" value="{{ old('avatar', $testimonial->avatar ?? '') }}"
               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52]">
    </div>

</div>

{{-- Review Text --}}
<div class="mt-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Review Text <span class="text-red-500">*</span></label>
    <textarea name="text" rows="4"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#7C0E52] focus:outline-none focus:ring-1 focus:ring-[#7C0E52] @error('text') border-red-400 @enderror">{{ old('text', $testimonial->text ?? '') }}</textarea>
    @error('text') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>
