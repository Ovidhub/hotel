<x-layouts.admin title="Payment Methods">

    @if(session('status'))
        <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700 ring-1 ring-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-gray-800">Payment Methods</h2>
        <a href="{{ route('admin.payment-methods.create') }}"
           class="inline-flex items-center gap-1.5 rounded-lg bg-[#1D5C52] px-4 py-2 text-sm font-medium text-white hover:bg-[#164840] transition-colors">
            + New Method
        </a>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden md:table-cell">Type</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden lg:table-cell">Provider</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Active</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 hidden md:table-cell">Commitment Fee</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($paymentMethods as $pm)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-gray-900">{{ $pm->name }}</td>
                    <td class="px-5 py-3.5 hidden md:table-cell text-gray-600">{{ $pm->type }}</td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-gray-600">{{ $pm->provider }}</td>
                    <td class="px-5 py-3.5 text-center">
                        @if($pm->active)
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700">Active</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center hidden md:table-cell">
                        @if($pm->accepts_commitment_fee)
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">Yes</span>
                        @else
                            <span class="text-xs text-gray-400">No</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.payment-methods.edit', $pm) }}"
                               class="inline-flex items-center rounded-lg bg-[#C8922A]/10 px-3 py-1.5 text-xs font-medium text-[#C8922A] hover:bg-[#C8922A]/20 transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.payment-methods.destroy', $pm) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($pm->name) }}? This cannot be undone.')">
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
                    <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">No payment methods yet. <a href="{{ route('admin.payment-methods.create') }}" class="text-[#1D5C52] underline">Add one.</a></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>
