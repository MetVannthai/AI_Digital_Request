<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <script>
            if (localStorage.getItem('office-stock-theme') === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        </script>

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
                            <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-violet-600">Cambodia time</p><p class="text-sm font-semibold text-slate-900" aria-label="Cambodia local date and time"><span data-cambodia-date>{{ now()->format('D, d M Y') }}</span><span aria-hidden="true"> | </span><time data-cambodia-time>{{ now()->format('g:i A') }}</time></p></div>
                        </div>
                        <div class="flex items-center gap-3">
                            @if (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin'))
                                <a href="{{ route('request.create') }}" target="_blank" class="hidden rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-violet-300 hover:text-violet-700 sm:inline-flex">Open employee form</a>
                            @endif
                            <div class="h-8 w-px bg-slate-200"></div>
                            <div x-data="{ profileOpen: false }" @click.outside="profileOpen = false" @keydown.escape.window="profileOpen = false" class="relative">
                                <button type="button" @click="profileOpen = !profileOpen" :aria-expanded="profileOpen.toString()" aria-haspopup="true" class="flex items-center gap-3 rounded-lg p-1.5 text-left transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-violet-500">
                                    <span class="hidden text-right sm:block"><span class="block text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</span><span class="block text-xs text-slate-500">{{ Auth::user()->hasRole('staff') ? 'Staff user' : 'Administrator' }}</span></span>
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-violet-600 text-sm font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </button>
                                <div x-cloak x-show="profileOpen" x-transition class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-violet-50 hover:text-violet-700">Profile settings</a>
                                    <button type="button" role="switch" :aria-checked="$store.theme.dark.toString()" @click="$store.theme.toggle()" class="flex w-full items-center justify-between border-t border-slate-100 px-4 py-2.5 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                        <span x-text="$store.theme.dark ? 'Dark mode' : 'Light mode'"></span>
                                        <span class="flex h-5 w-9 items-center rounded-full p-0.5 transition-colors" :class="$store.theme.dark ? 'bg-violet-700' : 'bg-slate-300'"><span class="h-4 w-4 rounded-full bg-white shadow transition-transform" :class="$store.theme.dark ? 'translate-x-4' : 'translate-x-0'"></span></span>
                                    </button>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full border-t border-slate-100 px-4 py-2.5 text-left text-sm font-medium text-slate-700 transition hover:bg-violet-50 hover:text-violet-700">Sign out</button>
                                    </form>
                                </div>
                            </div>
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
