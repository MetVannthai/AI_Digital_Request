<x-app-layout>
    <x-slot name="header"><div><p class="text-sm font-medium text-violet-600">Administration</p><h2 class="mt-1 text-2xl font-bold text-slate-900">Create user</h2></div></x-slot>
    <div class="px-4 py-8 sm:px-6 lg:px-8"><div class="max-w-3xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8"><p class="mb-6 text-sm text-slate-500">A temporary PIN is generated automatically when the PIN field is left blank.</p><form method="POST" action="{{ route('admin.users.store') }}">@csrf @include('admin.users.form', ['user' => null])</form></div></div>
</x-app-layout>
