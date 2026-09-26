<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-800">
        <div class="min-h-screen bg-slate-100">
            @include('layouts.navigation')

            <div class="admin-main min-h-screen lg:pl-64">
                <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
                    <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3 pl-12 lg:pl-0">
                            <div class="hidden h-9 w-9 items-center justify-center rounded-xl bg-violet-100 text-violet-700 sm:flex">▦</div>
                            <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-violet-600">Office stock</p><p class="text-sm font-semibold text-slate-900">{{ config('app.name', 'Stock Desk') }}</p></div>
                        </div>
                        <div class="flex items-center gap-3">
                            @if (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin'))
                                <a href="{{ route('request.create') }}" target="_blank" class="hidden rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-violet-300 hover:text-violet-700 sm:inline-flex">Open employee form</a>
                            @endif
                            <div class="h-8 w-px bg-slate-200"></div>
                            <div class="hidden text-right sm:block"><p class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p><p class="text-xs text-slate-500">{{ Auth::user()->hasRole('staff') ? 'Staff user' : 'Administrator' }}</p></div>
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-violet-600 text-sm font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        </div>
                    </div>
                </header>

                @isset($header)
                    <header class="border-b border-slate-200 bg-white"><div class="mx-auto max-w-[1500px] px-4 py-6 sm:px-6 lg:px-8">{{ $header }}</div></header>
                @endisset

                <main class="mx-auto max-w-[1500px]">{{ $slot }}</main>
            </div>
        </div>
    </body>
</html>
