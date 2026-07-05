<x-layouts.admin title="Account">

    <div class="mx-auto max-w-2xl space-y-6">

        {{-- Account summary --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <h2 class="text-base font-semibold text-gray-800">Account</h2>
            <dl class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ auth()->user()->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ auth()->user()->email }}</dd>
                </div>
            </dl>
        </div>

        {{-- Change password --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <h2 class="text-base font-semibold text-gray-800">Change Password</h2>
            <p class="mt-1 text-sm text-gray-500">
                Use a long, unique password to keep the admin account secure.
            </p>

            @if ($errors->any())
                <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-200">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.account.password') }}" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current password</label>
                    <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                           class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#7C0E52] focus:ring-[#7C0E52]">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">New password</label>
                    <input type="password" id="password" name="password" autocomplete="new-password"
                           class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#7C0E52] focus:ring-[#7C0E52]">
                    <p class="mt-1 text-xs text-gray-400">At least 8 characters.</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm new password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                           class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#7C0E52] focus:ring-[#7C0E52]">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-[#7C0E52] px-4 py-2 text-sm font-medium text-white hover:bg-[#560A3A] transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-layouts.admin>
