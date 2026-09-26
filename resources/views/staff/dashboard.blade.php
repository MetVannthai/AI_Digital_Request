<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-violet-600">Staff overview</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Welcome, {{ Str::before(Auth::user()->name, ' ') }}</h2>
            </div>
            <p class="text-sm text-slate-500">{{ now()->format('l, d F Y') }}</p>
        </div>
    </x-slot>

    <div class="space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-2xl bg-[#39206f] p-5 text-white shadow-lg shadow-violet-900/10">
                <p class="text-sm text-violet-200">My pending requests</p>
                <p class="mt-3 text-3xl font-bold">{{ $pendingCount }}</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Catalogue items</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $itemCount }}</p>
            </div>

            <a href="{{ route('admin.requests.index') }}" class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:ring-violet-300">
                <p class="text-sm text-slate-500">Open request desk</p>
                <p class="mt-3 text-sm font-semibold text-violet-700">View requests &rarr;</p>
            </a>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">My recent requests</h3>
                    <p class="mt-1 text-sm text-slate-500">Latest items requested by you.</p>
                </div>
            </div>

            <div class="mt-5 space-y-3">
                @forelse($recentRequests as $request)
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 p-4">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $request->item?->name ?? 'Request item' }}</p>
                            <p class="text-xs text-slate-500">{{ $request->status }} • {{ $request->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $request->status === 'APPROVED' ? 'bg-emerald-50 text-emerald-700' : ($request->status === 'REJECTED' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">
                            {{ $request->status }}
                        </span>
                    </div>
                @empty
                    <p class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">No requests submitted yet.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
