<x-layouts.admin title="Testimonials">

    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-gray-800">Testimonials</h2>
        <a href="{{ route('admin.testimonials.create') }}"
           class="inline-flex items-center gap-1.5 rounded-lg bg-[#7C0E52] px-4 py-2 text-sm font-medium text-white hover:bg-[#560A3A] transition-colors">
            + New Testimonial
        </a>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden md:table-cell">Role</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Rating</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden lg:table-cell">Review</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($testimonials as $t)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-gray-900">{{ $t->name }}</td>
                    <td class="px-5 py-3.5 hidden md:table-cell text-gray-600">{{ $t->role }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="font-semibold text-amber-600">{{ $t->rating }}/5</span>
                    </td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-gray-500 text-xs max-w-xs truncate">{{ Str::limit($t->text, 80) }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.testimonials.edit', $t) }}"
                               class="inline-flex items-center rounded-lg bg-[#C79A46]/10 px-3 py-1.5 text-xs font-medium text-[#C79A46] hover:bg-[#C79A46]/20 transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}"
                                  onsubmit="return confirm('Delete this testimonial?')">
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
                    <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-400">
                        No testimonials yet. <a href="{{ route('admin.testimonials.create') }}" class="text-[#7C0E52] underline">Add one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>
