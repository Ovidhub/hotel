<x-layouts.admin title="FAQs">

    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-gray-800">FAQs</h2>
        <a href="{{ route('admin.faqs.create') }}"
           class="inline-flex items-center gap-1.5 rounded-lg bg-[#7C0E52] px-4 py-2 text-sm font-medium text-white hover:bg-[#560A3A] transition-colors">
            + New FAQ
        </a>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 w-16">Sort</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Question</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden lg:table-cell">Answer</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($faqs as $faq)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3.5 text-center text-gray-400 text-xs font-mono">{{ $faq->sort }}</td>
                    <td class="px-5 py-3.5 font-medium text-gray-900">{{ $faq->question }}</td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-gray-500 text-xs max-w-sm truncate">{{ Str::limit($faq->answer, 100) }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.faqs.edit', $faq) }}"
                               class="inline-flex items-center rounded-lg bg-[#C79A46]/10 px-3 py-1.5 text-xs font-medium text-[#C79A46] hover:bg-[#C79A46]/20 transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
                                  onsubmit="return confirm('Delete this FAQ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-12 text-center text-sm text-gray-400">
                        No FAQs yet. <a href="{{ route('admin.faqs.create') }}" class="text-[#7C0E52] underline">Add one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>
