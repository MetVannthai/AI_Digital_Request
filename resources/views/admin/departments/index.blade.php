<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-violet-600">Administration</p>
                <h2 class="mt-1 text-2xl font-bold text-slate-900">Departments</h2>
            </div>
            <a href="{{ route('admin.departments.create') }}" class="rounded-lg bg-violet-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-violet-800">New department</a>
        </div>
    </x-slot>

    <div class="space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @include('partials.flash')

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="p-4">Name</th>
                        <th class="p-4">Description</th>
                        <th class="p-4">Users</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                        <tr class="border-t border-slate-100">
                            <td class="p-4 font-semibold text-slate-900">{{ $department->name }}</td>
                            <td class="p-4 text-slate-600">{{ $department->description ?: '—' }}</td>
                            <td class="p-4 text-slate-600">{{ $department->users_count }}</td>
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.departments.edit', $department) }}" class="font-semibold text-violet-700">Edit</a>
                                <form class="ml-3 inline" method="POST" action="{{ route('admin.departments.destroy', $department) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600" onclick="return confirm('Delete this department?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-500">No departments yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
