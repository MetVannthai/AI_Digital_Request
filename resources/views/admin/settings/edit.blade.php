<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-violet-600">Inventory</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">System Settings</h2>
        </div>
    </x-slot>

    <div class="space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @include('partials.flash')

        <form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-3xl space-y-6">
            @csrf
            @method('PUT')

            <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-bold text-slate-900">Inventory controls</h3>
                <div class="mt-5 max-w-sm">
                    <label for="default_min_stock" class="block text-sm font-semibold text-slate-700">Default low-stock threshold</label>
                    <input id="default_min_stock" name="default_min_stock" type="number" min="0" max="1000000" required value="{{ old('default_min_stock', $settings->default_min_stock) }}" class="mt-2 w-full rounded-lg border-slate-300">
                    <x-input-error :messages="$errors->get('default_min_stock')" class="mt-2" />
                    <p class="mt-2 text-sm text-slate-500">Used for new items and items without an individual threshold.</p>
                </div>
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-bold text-slate-900">Request workflow</h3>
                <label for="require_request_approval" class="mt-5 flex max-w-xl cursor-pointer items-start gap-3">
                    <input type="hidden" name="require_request_approval" value="0">
                    <input id="require_request_approval" name="require_request_approval" type="checkbox" value="1" @checked(old('require_request_approval', $settings->require_request_approval)) class="mt-1 rounded border-slate-300 text-violet-700 focus:ring-violet-500">
                    <span>
                        <span class="block text-sm font-semibold text-slate-800">Require approval before issuing stock</span>
                        <span class="mt-1 block text-sm text-slate-500">When disabled, new requests are approved automatically. Stock still needs to be issued by an authorized user.</span>
                    </span>
                </label>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="rounded-lg bg-violet-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-800">Save settings</button>
            </div>
        </form>
    </div>
</x-app-layout>
