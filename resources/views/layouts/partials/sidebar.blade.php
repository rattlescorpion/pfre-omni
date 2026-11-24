{{-- PFRE-Omni primary navigation --}}
<aside class="fixed inset-y-0 left-0 z-50 w-64 border-r border-gray-200 bg-white transition-all dark:border-gray-700 dark:bg-gray-800"
       :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }" x-cloak>
    <div class="flex h-16 items-center border-b border-gray-200 px-6 dark:border-gray-700">
        <a href="{{ route('dashboard') }}" class="text-xl font-extrabold tracking-tight text-blue-700 dark:text-blue-300">PFRE-Omni</a>
    </div>
    <nav class="space-y-1 overflow-y-auto px-3 py-4">
        <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">Dashboard</a>
        <a href="{{ route('directory.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->routeIs('directory.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">Contacts & Companies 360°</a>
        <div class="px-3 pb-1 pt-4 text-xs font-bold uppercase tracking-wider text-gray-400">CRM</div>
        <a href="{{ route('crm.leads.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Leads</a>
        <a href="{{ route('crm.tasks.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Tasks</a>
        <a href="{{ route('crm.e-registrations.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">e-Registrations</a>
        <div class="px-3 pb-1 pt-4 text-xs font-bold uppercase tracking-wider text-gray-400">Inventory</div>
        <a href="{{ route('inventory.projects.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Projects</a>
        <a href="{{ route('inventory.properties.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Properties</a>
        <div class="px-3 pb-1 pt-4 text-xs font-bold uppercase tracking-wider text-gray-400">Operations</div>
        <a href="{{ route('finance.invoices.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Invoices</a>
        <a href="{{ route('hr.employees.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Employees</a>
    </nav>
</aside>
