<x-layouts.admin title="New Payment Method">

    <div class="mb-5 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.payment-methods.index') }}" class="hover:text-[#1D5C52] transition-colors">Payment Methods</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">New Method</span>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <h2 class="mb-6 text-base font-semibold text-gray-800">Create Payment Method</h2>

        <form method="POST" action="{{ route('admin.payment-methods.store') }}">
            @csrf

            @include('admin.payment-methods._form')

            <div class="mt-6 flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-[#1D5C52] px-5 py-2 text-sm font-medium text-white hover:bg-[#164840] transition-colors">
                    Create Method
                </button>
                <a href="{{ route('admin.payment-methods.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Cancel</a>
            </div>
        </form>
    </div>

</x-layouts.admin>
