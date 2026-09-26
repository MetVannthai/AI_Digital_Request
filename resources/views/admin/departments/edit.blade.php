<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit department</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.departments.update', $department) }}" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf
                @method('PUT')
                @include('admin.departments.form', ['department' => $department])
            </form>
        </div>
    </div>
</x-app-layout>
