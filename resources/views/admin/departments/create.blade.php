<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">New department</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.departments.store') }}" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf
                @include('admin.departments.form', ['department' => null])
            </form>
        </div>
    </div>
</x-app-layout>
