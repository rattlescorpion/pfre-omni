{{-- resources/views/layouts/partials/sidebar.blade.php --}}
<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-all duration-300 ease-in-out lg:translate-x-0"
    :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
    x-cloak>

    {{-- Logo --}}
    <div class="flex items-center h-16 px-6 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="text-xl font-bold text-gray-900 dark:text-white">PFRE-Omni</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto" x-data="{ activeMenu: 'dashboard' }">
        {{-- Dashboard --}}
        <x-sidebar-link href="{{ route('dashboard') }}" icon="home" :active="request()->routeIs('dashboard')">
            Dashboard
        </x-sidebar-link>

        {{-- CRM & Lead Management --}}
        <x-sidebar-dropdown title="CRM & Leads" icon="users">
            <x-sidebar-link href="{{ route('leads.index') }}" :active="request()->routeIs('leads.*')">Leads</x-sidebar-link>
            <x-sidebar-link href="{{ route('followups.index') }}" :active="request()->routeIs('followups.*')">Follow-ups</x-sidebar-link>
            <x-sidebar-link href="{{ route('site-visits.index') }}" :active="request()->routeIs('site-visits.*')">Site Visits</x-sidebar-link>
        </x-sidebar-dropdown>

        {{-- Sales & Booking --}}
        <x-sidebar-dropdown title="Sales & Booking" icon="currency-rupee">
            <x-sidebar-link href="{{ route('properties.index') }}" :active="request()->routeIs('properties.*')">Inventory</x-sidebar-link>
            <x-sidebar-link href="{{ route('bookings.index') }}" :active="request()->routeIs('bookings.*')">Bookings</x-sidebar-link>
            <x-sidebar-link href="{{ route('allotments.index') }}" :active="request()->routeIs('allotments.*')">Allotments</x-sidebar-link>
        </x-sidebar-dropdown>

        {{-- Finance & Billing --}}
        <x-sidebar-dropdown title="Finance & Billing" icon="calculator">
            <x-sidebar-link href="{{ route('finance.payment-plans') }}" :active="request()->routeIs('finance.*')">Payment Plans</x-sidebar-link>
            <x-sidebar-link href="{{ route('finance.receipts') }}" :active="request()->routeIs('receipts.*')">Receipts</x-sidebar-link>
            <x-sidebar-link href="{{ route('finance.ledger') }}" :active="request()->routeIs('ledger.*')">Ledger</x-sidebar-link>
        </x-sidebar-dropdown>

        {{-- GST & Compliance --}}
        <x-sidebar-dropdown title="GST & Compliance" icon="shield-check">
            <x-sidebar-link href="{{ route('gst.returns') }}" :active="request()->routeIs('gst.*')">GST Returns</x-sidebar-link>
            <x-sidebar-link href="{{ route('einvoice.index') }}" :active="request()->routeIs('einvoice.*')">e-Invoice</x-sidebar-link>
        </x-sidebar-dropdown>

        {{-- HRMS --}}
        <x-sidebar-dropdown title="HRMS" icon="briefcase">
            <x-sidebar-link href="{{ route('hrms.employees') }}" :active="request()->routeIs('hrms.*')">Employees</x-sidebar-link>
            <x-sidebar-link href="{{ route('hrms.attendance') }}" :active="request()->routeIs('attendance.*')">Attendance</x-sidebar-link>
        </x-sidebar-dropdown>

        {{-- More clusters... --}}
    </nav>
</aside>