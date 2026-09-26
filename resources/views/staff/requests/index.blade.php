<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-violet-600">Staff requests</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">My requests</h2>
            </div>
            <a href="{{ route('request.create') }}" class="rounded-lg bg-violet-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-violet-800">New request</a>
        </div>
    </x-slot>

    <div class="space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @include('partials.flash')

        <form method="GET" class="flex flex-wrap gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
            <select name="status" class="rounded-lg border-slate-300">
                <option value="">All statuses</option>
                @foreach(['PENDING','APPROVED','REJECTED','ISSUED'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Filter</button>
        </form>

        <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="p-4">Tracking</th>
                        <th class="p-4">Item</th>
                        <th class="p-4">Qty</th>
                        <th class="p-4">Dept</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr class="border-t border-slate-100">
                            <td class="p-4 font-semibold text-slate-900">{{ $request->tracking_code }}</td>
                            <td class="p-4">{{ $request->item?->name ?? '—' }}</td>
                            <td class="p-4">{{ $request->quantity }}</td>
                            <td class="p-4">{{ $request->department }}</td>
                            <td class="p-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $request->status === 'APPROVED' ? 'bg-emerald-50 text-emerald-700' : ($request->status === 'REJECTED' ? 'bg-red-50 text-red-700' : ($request->status === 'ISSUED' ? 'bg-sky-50 text-sky-700' : 'bg-amber-50 text-amber-700')) }}">
                                    {{ $request->status }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-500">{{ $request->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">No requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $requests->links() }}</div>
    </div>
</x-app-layout>
