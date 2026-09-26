@php
    $user = Auth::user();
    $isSuperAdmin = $user && $user->hasRole('super_admin');
    $isAdmin = $user && $user->hasRole('admin');
    $isStaff = $user && $user->hasRole('staff');
@endphp

<nav x-data="{ open: false, requests: {{ request()->routeIs('admin.requests.*') ? 'true' : 'false' }}, stock: {{ request()->routeIs('admin.items.*') || request()->routeIs('admin.stock.*') || request()->routeIs('admin.transactions.*') ? 'true' : 'false' }}, users: {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') ? 'true' : 'false' }}, reports: {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }} }">
    <div x-show="open" x-cloak @click="open = false" class="fixed inset-0 z-40 bg-slate-950/40 lg:hidden"></div>

    <aside :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="admin-sidebar fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-[#39206f] text-white shadow-2xl transition-transform duration-200 lg:translate-x-0">
        <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-lg font-black text-violet-800 shadow-lg">OS</div>
            <div><p class="text-sm font-bold tracking-wide">Office Stock</p><p class="text-[10px] uppercase tracking-[0.22em] text-violet-200">Control desk</p></div>
            <button @click="open = false" class="ml-auto text-xl text-violet-200 lg:hidden" aria-label="Close menu">&times;</button>
        </div>

        <div class="flex-1 overflow-y-auto px-3 py-6">
            <p class="px-3 text-[10px] font-bold uppercase tracking-[0.22em] text-violet-300">Workspace</p>
            <a href="{{ $isStaff ? route('staff.dashboard') : route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('staff.dashboard') || request()->routeIs('admin.dashboard') ? 'admin-nav-link-active' : '' }}"><span class="admin-nav-glyph">▦</span>Dashboard</a>

            @if ($isSuperAdmin || $isAdmin)
                <p class="mt-7 px-3 text-[10px] font-bold uppercase tracking-[0.22em] text-violet-300">Operations</p>
                <button @click="requests = !requests" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">↗</span>Requests</span><span class="text-violet-300" x-text="requests ? '−' : '+'"></span></button>
                <div x-show="requests" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                    <a href="{{ route('admin.requests.index') }}" class="admin-subnav-link">All Requests</a>
                    <a href="{{ route('admin.requests.mine') }}" class="admin-subnav-link">My Requests</a>
                </div>

                <button @click="stock = !stock" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">□</span>Stock</span><span class="text-violet-300" x-text="stock ? '−' : '+'"></span></button>
                <div x-show="stock" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                    <a href="{{ route('admin.items.index') }}" class="admin-subnav-link">Stock Items</a>
                    <a href="{{ route('admin.stock.in') }}" class="admin-subnav-link">Stock In</a>
                    <a href="{{ route('admin.stock.out') }}" class="admin-subnav-link">Stock Out</a>
                </div>

                <p class="mt-7 px-3 text-[10px] font-bold uppercase tracking-[0.22em] text-violet-300">Administration</p>
                <button @click="users = !users" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">◎</span>Users</span><span class="text-violet-300" x-text="users ? '−' : '+'"></span></button>
                <div x-show="users" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                    <a href="{{ route('admin.users.index') }}" class="admin-subnav-link">Users</a>
                    @if ($isSuperAdmin)
                        <a href="{{ route('admin.departments.index') }}" class="admin-subnav-link">Departments</a>
                        <a href="{{ route('admin.roles.index') }}" class="admin-subnav-link">Roles</a>
                    @endif
                </div>

                <button @click="reports = !reports" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">▤</span>Reports</span><span class="text-violet-300" x-text="reports ? '−' : '+'"></span></button>
                <div x-show="reports" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                    <a href="{{ route('admin.reports.requests') }}" class="admin-subnav-link">Request Report</a>
                    <a href="{{ route('admin.reports.stock') }}" class="admin-subnav-link">Stock Report</a>
                </div>
            @endif
        </div>

        <div class="border-t border-white/10 p-4">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl p-3 transition hover:bg-white/10"><span class="flex h-9 w-9 items-center justify-center rounded-full bg-violet-400 font-bold text-violet-950">{{ strtoupper(substr($user->name, 0, 1)) }}</span><span class="min-w-0"><span class="block truncate text-sm font-semibold">{{ $user->name }}</span><span class="block text-xs text-violet-200">{{ $isStaff ? 'Staff account' : 'Account settings' }}</span></span></a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">@csrf<button class="w-full rounded-lg px-3 py-2 text-left text-xs font-semibold text-violet-200 transition hover:bg-white/10 hover:text-white">Sign out</button></form>
        </div>
    </aside>

    <div class="fixed left-4 top-4 z-30 lg:hidden"><button @click="open = true" class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#39206f] text-xl text-white shadow-lg" aria-label="Open menu">☰</button></div>
</nav>
