<x-layouts.admin title="Edit Apartment">

    <div class="mb-5 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.apartments.index') }}" class="hover:text-[#7C0E52] transition-colors">Apartments</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ $apartment->name }}</span>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <h2 class="mb-6 text-base font-semibold text-gray-800">Edit Apartment</h2>

        <form method="POST" action="{{ route('admin.apartments.update', $apartment) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.apartments._form')

            <div class="mt-6 flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-[#7C0E52] px-5 py-2 text-sm font-medium text-white hover:bg-[#560A3A] transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.apartments.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Cancel</a>
            </div>
        </form>
    </div>

</x-layouts.admin>
