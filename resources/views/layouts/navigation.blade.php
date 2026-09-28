@php
    $user = Auth::user();
    $isSuperAdmin = $user && $user->hasRole('super_admin');
    $isAdmin = $user && $user->hasRole('admin');
    $isStaff = $user && $user->hasRole('staff');
    $activeMenu = request()->query('menu');
    $inventoryOpen = (is_string($activeMenu) && str_starts_with($activeMenu, 'inventory-')) || (! $activeMenu && request()->routeIs('admin.categories.*', 'admin.items.*', 'admin.transactions.*'));
    $organizationOpen = $activeMenu === 'departments' || (! $activeMenu && request()->routeIs('admin.departments.*'));
    $requestsOpen = (is_string($activeMenu) && str_starts_with($activeMenu, 'requests-')) || (! $activeMenu && request()->routeIs('admin.requests.*'));
    $usersOpen = in_array($activeMenu, ['users', 'roles'], true) || (! $activeMenu && request()->routeIs('admin.users.*', 'admin.roles.*'));
    $reportsOpen = (is_string($activeMenu) && str_starts_with($activeMenu, 'reports-')) || (! $activeMenu && request()->routeIs('admin.reports.*'));
@endphp

<nav x-data="{ open: false, inventory: {{ $inventoryOpen ? 'true' : 'false' }}, organization: {{ $organizationOpen ? 'true' : 'false' }}, requests: {{ $requestsOpen ? 'true' : 'false' }}, users: {{ $usersOpen ? 'true' : 'false' }}, reports: {{ $reportsOpen ? 'true' : 'false' }}, menuSelected: null }">
    <div x-show="open" x-cloak @click="open = false" class="fixed inset-0 z-40 bg-slate-950/40 lg:hidden"></div>

    <aside :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="admin-sidebar fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-violet-700 text-white shadow-2xl transition-transform duration-200 lg:translate-x-0">
        <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-lg font-black text-violet-800 shadow-lg">OS</div>
            <div><p class="text-sm font-bold tracking-wide">Office Stock</p><p class="text-[10px] uppercase tracking-[0.22em] text-violet-200">Control desk</p></div>
            <button @click="open = false" class="ml-auto text-xl text-violet-200 lg:hidden" aria-label="Close menu">&times;</button>
        </div>

        <div class="flex-1 overflow-y-auto px-3 py-6">
            <p class="px-3 text-[10px] font-bold uppercase tracking-[0.22em] text-violet-300">Workspace</p>
            <a href="{{ $isStaff ? route('staff.dashboard') : route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('staff.dashboard') || request()->routeIs('admin.dashboard') ? 'admin-nav-link-active' : '' }}"><span class="admin-nav-glyph">▦</span>Dashboard</a>

            @if ($isStaff)
                <div class="mt-4 space-y-1">
                    <a href="{{ route('request.create') }}" class="admin-nav-link {{ request()->routeIs('request.create') ? 'admin-nav-link-active' : '' }}"><span class="admin-nav-glyph">📝</span>Request Stock</a>
                    <a href="{{ route('staff.requests.index') }}" class="admin-nav-link {{ request()->routeIs('staff.requests.*') ? 'admin-nav-link-active' : '' }}"><span class="admin-nav-glyph">📋</span>My Requests</a>
                    <a href="{{ route('admin.settings.edit') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'admin-nav-link-active' : '' }}"><span class="admin-nav-glyph">⚙️</span>System Settings</a>
                </div>
            @elseif ($isAdmin)
                <div class="mt-4 space-y-1">
                    <button @click="inventory = !inventory; menuSelected = 'inventory-group'" :class="menuSelected === 'inventory-group' ? 'admin-nav-link-active' : ''" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">📦</span>Inventory</span><span aria-hidden="true" class="ml-1 text-sm leading-none text-white/75" x-text="inventory ? '▾' : '▸'"></span></button>
                    <div x-cloak x-show="inventory" x-transition:enter="transition ease-in-out duration-250" x-transition:enter-start="-translate-y-1 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-1 opacity-0" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                        <a href="{{ route('admin.categories.index', ['menu' => 'inventory-categories']) }}" class="admin-subnav-link {{ request()->query('menu') === 'inventory-categories' || (! request()->query('menu') && request()->routeIs('admin.categories.*')) ? 'admin-subnav-link-active' : '' }}">Categories</a>
                        <a href="{{ route('admin.items.index', ['menu' => 'inventory-items']) }}" class="admin-subnav-link {{ request()->query('menu') === 'inventory-items' || (! request()->query('menu') && request()->routeIs('admin.items.*')) ? 'admin-subnav-link-active' : '' }}">Items</a>
                        <a href="{{ route('admin.items.index', ['menu' => 'inventory-stock']) }}" class="admin-subnav-link {{ request()->query('menu') === 'inventory-stock' ? 'admin-subnav-link-active' : '' }}">Stock</a>
                        <a href="{{ route('admin.items.index', ['menu' => 'inventory-add-stock']) }}" class="admin-subnav-link {{ request()->query('menu') === 'inventory-add-stock' ? 'admin-subnav-link-active' : '' }}">Add Stock</a>
                        <a href="{{ route('admin.items.index', ['menu' => 'inventory-adjustment']) }}" class="admin-subnav-link {{ request()->query('menu') === 'inventory-adjustment' ? 'admin-subnav-link-active' : '' }}">Stock Adjustment</a>
                        <a href="{{ route('admin.transactions.index', ['menu' => 'inventory-transactions']) }}" class="admin-subnav-link {{ request()->query('menu') === 'inventory-transactions' || (! request()->query('menu') && request()->routeIs('admin.transactions.*')) ? 'admin-subnav-link-active' : '' }}">Stock Transactions</a>
                    </div>

                    <button @click="requests = !requests; menuSelected = 'requests-group'" :class="menuSelected === 'requests-group' ? 'admin-nav-link-active' : ''" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">📝</span>Requests</span><span aria-hidden="true" class="ml-1 text-sm leading-none text-white/75" x-text="requests ? '▾' : '▸'"></span></button>
                    <div x-cloak x-show="requests" x-transition:enter="transition ease-in-out duration-250" x-transition:enter-start="-translate-y-1 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-1 opacity-0" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                        <a href="{{ route('admin.requests.index', ['menu' => 'requests-all']) }}" class="admin-subnav-link {{ request()->query('menu') === 'requests-all' || (! request()->query('menu') && request()->routeIs('admin.requests.index') && ! request()->query('status')) ? 'admin-subnav-link-active' : '' }}">All Requests</a>
                        <a href="{{ route('admin.requests.index', ['status' => 'PENDING', 'menu' => 'requests-pending']) }}" class="admin-subnav-link {{ request()->query('menu') === 'requests-pending' || (! request()->query('menu') && request()->routeIs('admin.requests.index') && request()->query('status') === 'PENDING') ? 'admin-subnav-link-active' : '' }}">Pending</a>
                        <a href="{{ route('admin.requests.index', ['status' => 'APPROVED', 'menu' => 'requests-approved']) }}" class="admin-subnav-link {{ request()->query('menu') === 'requests-approved' || (! request()->query('menu') && request()->routeIs('admin.requests.index') && request()->query('status') === 'APPROVED') ? 'admin-subnav-link-active' : '' }}">Approved</a>
                        <a href="{{ route('admin.requests.index', ['status' => 'REJECTED', 'menu' => 'requests-rejected']) }}" class="admin-subnav-link {{ request()->query('menu') === 'requests-rejected' || (! request()->query('menu') && request()->routeIs('admin.requests.index') && request()->query('status') === 'REJECTED') ? 'admin-subnav-link-active' : '' }}">Rejected</a>
                        <a href="{{ route('admin.requests.index', ['status' => 'ISSUED', 'menu' => 'requests-issued']) }}" class="admin-subnav-link {{ request()->query('menu') === 'requests-issued' || (! request()->query('menu') && request()->routeIs('admin.requests.index') && request()->query('status') === 'ISSUED') ? 'admin-subnav-link-active' : '' }}">Issued</a>
                    </div>

                    <button @click="reports = !reports; menuSelected = 'reports-group'" :class="menuSelected === 'reports-group' ? 'admin-nav-link-active' : ''" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">📊</span>Reports</span><span aria-hidden="true" class="ml-1 text-sm leading-none text-white/75" x-text="reports ? '▾' : '▸'"></span></button>
                    <div x-cloak x-show="reports" x-transition:enter="transition ease-in-out duration-250" x-transition:enter-start="-translate-y-1 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-1 opacity-0" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                        <a href="{{ route('admin.transactions.index', ['menu' => 'reports-stock']) }}" class="admin-subnav-link {{ request()->query('menu') === 'reports-stock' ? 'admin-subnav-link-active' : '' }}">Stock Report</a>
                        <a href="{{ route('admin.requests.index', ['menu' => 'reports-requests']) }}" class="admin-subnav-link {{ request()->query('menu') === 'reports-requests' ? 'admin-subnav-link-active' : '' }}">Request Report</a>
                        <a href="{{ route('admin.transactions.index', ['menu' => 'reports-movement']) }}" class="admin-subnav-link {{ request()->query('menu') === 'reports-movement' ? 'admin-subnav-link-active' : '' }}">Stock Movement</a>
                    </div>
                </div>
            @elseif ($isSuperAdmin)
                <div class="mt-4 space-y-1">
                    <button @click="users = !users; menuSelected = 'users-group'" :class="menuSelected === 'users-group' ? 'admin-nav-link-active' : ''" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">👥</span>User Management</span><span aria-hidden="true" class="ml-1 text-sm leading-none text-white/75" x-text="users ? '▾' : '▸'"></span></button>
                    <div x-cloak x-show="users" x-transition:enter="transition ease-in-out duration-250" x-transition:enter-start="-translate-y-1 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-1 opacity-0" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                        <a href="{{ route('admin.users.index', ['menu' => 'users']) }}" class="admin-subnav-link {{ request()->query('menu') === 'users' || (! request()->query('menu') && request()->routeIs('admin.users.*')) ? 'admin-subnav-link-active' : '' }}">Users</a>
                        <a href="{{ route('admin.roles.index', ['menu' => 'roles']) }}" class="admin-subnav-link {{ request()->query('menu') === 'roles' || (! request()->query('menu') && request()->routeIs('admin.roles.*')) ? 'admin-subnav-link-active' : '' }}">Roles &amp; Permissions</a>
                    </div>

                    <button @click="organization = !organization; menuSelected = 'organization-group'" :class="menuSelected === 'organization-group' ? 'admin-nav-link-active' : ''" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">🏢</span>Organization</span><span aria-hidden="true" class="ml-1 text-sm leading-none text-white/75" x-text="organization ? '▾' : '▸'"></span></button>
                    <div x-cloak x-show="organization" x-transition:enter="transition ease-in-out duration-250" x-transition:enter-start="-translate-y-1 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-1 opacity-0" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                        <a href="{{ route('admin.departments.index', ['menu' => 'departments']) }}" class="admin-subnav-link {{ request()->query('menu') === 'departments' || (! request()->query('menu') && request()->routeIs('admin.departments.*')) ? 'admin-subnav-link-active' : '' }}">Departments</a>
                    </div>

                    <button @click="inventory = !inventory; menuSelected = 'inventory-group'" :class="menuSelected === 'inventory-group' ? 'admin-nav-link-active' : ''" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">📦</span>Inventory</span><span aria-hidden="true" class="ml-1 text-sm leading-none text-white/75" x-text="inventory ? '▾' : '▸'"></span></button>
                    <div x-cloak x-show="inventory" x-transition:enter="transition ease-in-out duration-250" x-transition:enter-start="-translate-y-1 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-1 opacity-0" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                        <a href="{{ route('admin.categories.index', ['menu' => 'inventory-categories']) }}" class="admin-subnav-link {{ request()->query('menu') === 'inventory-categories' || (! request()->query('menu') && request()->routeIs('admin.categories.*')) ? 'admin-subnav-link-active' : '' }}">Categories</a>
                        <a href="{{ route('admin.items.index', ['menu' => 'inventory-items']) }}" class="admin-subnav-link {{ request()->query('menu') === 'inventory-items' || (! request()->query('menu') && request()->routeIs('admin.items.*')) ? 'admin-subnav-link-active' : '' }}">Items</a>
                        <a href="{{ route('admin.items.index', ['menu' => 'inventory-stock']) }}" class="admin-subnav-link {{ request()->query('menu') === 'inventory-stock' ? 'admin-subnav-link-active' : '' }}">Stock</a>
                        <a href="{{ route('admin.items.index', ['menu' => 'inventory-adjustment']) }}" class="admin-subnav-link {{ request()->query('menu') === 'inventory-adjustment' ? 'admin-subnav-link-active' : '' }}">Stock Adjustment</a>
                        <a href="{{ route('admin.transactions.index', ['menu' => 'inventory-transactions']) }}" class="admin-subnav-link {{ request()->query('menu') === 'inventory-transactions' || (! request()->query('menu') && request()->routeIs('admin.transactions.*')) ? 'admin-subnav-link-active' : '' }}">Stock Transactions</a>
                    </div>

                    <button @click="requests = !requests; menuSelected = 'requests-group'" :class="menuSelected === 'requests-group' ? 'admin-nav-link-active' : ''" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">📝</span>Requests</span><span aria-hidden="true" class="ml-1 text-sm leading-none text-white/75" x-text="requests ? '▾' : '▸'"></span></button>
                    <div x-cloak x-show="requests" x-transition:enter="transition ease-in-out duration-250" x-transition:enter-start="-translate-y-1 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-1 opacity-0" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                        <a href="{{ route('admin.requests.index', ['menu' => 'requests-all']) }}" class="admin-subnav-link {{ request()->query('menu') === 'requests-all' || (! request()->query('menu') && request()->routeIs('admin.requests.index') && ! request()->query('status')) ? 'admin-subnav-link-active' : '' }}">All Requests</a>
                        <a href="{{ route('admin.requests.index', ['status' => 'PENDING', 'menu' => 'requests-pending']) }}" class="admin-subnav-link {{ request()->query('menu') === 'requests-pending' || (! request()->query('menu') && request()->routeIs('admin.requests.index') && request()->query('status') === 'PENDING') ? 'admin-subnav-link-active' : '' }}">Pending</a>
                        <a href="{{ route('admin.requests.index', ['status' => 'APPROVED', 'menu' => 'requests-approved']) }}" class="admin-subnav-link {{ request()->query('menu') === 'requests-approved' || (! request()->query('menu') && request()->routeIs('admin.requests.index') && request()->query('status') === 'APPROVED') ? 'admin-subnav-link-active' : '' }}">Approved</a>
                        <a href="{{ route('admin.requests.index', ['status' => 'REJECTED', 'menu' => 'requests-rejected']) }}" class="admin-subnav-link {{ request()->query('menu') === 'requests-rejected' || (! request()->query('menu') && request()->routeIs('admin.requests.index') && request()->query('status') === 'REJECTED') ? 'admin-subnav-link-active' : '' }}">Rejected</a>
                        <a href="{{ route('admin.requests.index', ['status' => 'ISSUED', 'menu' => 'requests-issued']) }}" class="admin-subnav-link {{ request()->query('menu') === 'requests-issued' || (! request()->query('menu') && request()->routeIs('admin.requests.index') && request()->query('status') === 'ISSUED') ? 'admin-subnav-link-active' : '' }}">Issued</a>
                    </div>

                    <button @click="reports = !reports; menuSelected = 'reports-group'" :class="menuSelected === 'reports-group' ? 'admin-nav-link-active' : ''" class="admin-nav-link w-full justify-between"><span class="flex items-center gap-3"><span class="admin-nav-glyph">📊</span>Reports</span><span aria-hidden="true" class="ml-1 text-sm leading-none text-white/75" x-text="reports ? '▾' : '▸'"></span></button>
                    <div x-cloak x-show="reports" x-transition:enter="transition ease-in-out duration-250" x-transition:enter-start="-translate-y-1 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-1 opacity-0" class="ml-11 space-y-1 border-l border-white/15 pl-3">
                        <a href="{{ route('admin.transactions.index', ['menu' => 'reports-stock']) }}" class="admin-subnav-link {{ request()->query('menu') === 'reports-stock' ? 'admin-subnav-link-active' : '' }}">Stock Report</a>
                        <a href="{{ route('admin.requests.index', ['menu' => 'reports-requests']) }}" class="admin-subnav-link {{ request()->query('menu') === 'reports-requests' ? 'admin-subnav-link-active' : '' }}">Request Report</a>
                        <a href="{{ route('admin.transactions.index', ['menu' => 'reports-movement']) }}" class="admin-subnav-link {{ request()->query('menu') === 'reports-movement' ? 'admin-subnav-link-active' : '' }}">Stock Movement</a>
                    </div>

                    <a href="{{ route('admin.transactions.index', ['menu' => 'audit-logs']) }}" class="admin-nav-link {{ request()->query('menu') === 'audit-logs' || (! request()->query('menu') && request()->routeIs('admin.transactions.*')) ? 'admin-nav-link-active' : '' }}"><span class="admin-nav-glyph">📋</span>Audit Logs</a>
                    <a href="{{ route('admin.settings.edit') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'admin-nav-link-active' : '' }}"><span class="admin-nav-glyph">⚙️</span>System Settings</a>
                </div>
            @endif
        </div>

    </aside>

    <div class="fixed left-4 top-4 z-30 lg:hidden"><button @click="open = true" class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-700 text-xl text-white shadow-lg" aria-label="Open menu">☰</button></div>
</nav>
