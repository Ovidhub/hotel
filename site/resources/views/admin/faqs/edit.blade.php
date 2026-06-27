<x-layouts.admin title="Edit FAQ">

    <div class="mb-5">
        <a href="{{ route('admin.faqs.index') }}" class="text-sm text-[#1D5C52] hover:underline">&larr; Back to FAQs</a>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-6">
        <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
            @csrf
            @method('PUT')
            @include('admin.faqs._form')
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.faqs.index') }}"
                   class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-[#1D5C52] px-4 py-2 text-sm font-medium text-white hover:bg-[#164840] transition-colors">
                    Update FAQ
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin>
