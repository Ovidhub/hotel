{{-- Shared form for FAQ create & edit --}}
@php $isEdit = isset($faq); @endphp

{{-- Question --}}
<div class="mb-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Question <span class="text-red-500">*</span></label>
    <input type="text" name="question" value="{{ old('question', $faq->question ?? '') }}"
           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('question') border-red-400 @enderror">
    @error('question') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Answer --}}
<div class="mb-5">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Answer <span class="text-red-500">*</span></label>
    <textarea name="answer" rows="4"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52] @error('answer') border-red-400 @enderror">{{ old('answer', $faq->answer ?? '') }}</textarea>
    @error('answer') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Sort --}}
<div class="mb-5 w-32">
    <label class="block text-xs font-semibold text-gray-600 mb-1">Sort Order</label>
    <input type="number" name="sort" value="{{ old('sort', $faq->sort ?? 0) }}" min="0"
           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#1D5C52] focus:outline-none focus:ring-1 focus:ring-[#1D5C52]">
</div>
