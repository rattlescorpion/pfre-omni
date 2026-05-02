{{-- resources/views/layouts/partials/topnav.blade.php --}}
<header class="sticky top-0 z-40 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6">
        {{-- Left: Mobile hamburger + Logo/Title (optional) --}}
        <div class="flex items-center space-x-4">
            {{-- Mobile sidebar toggle --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    aria-label="Toggle sidebar">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- Page title or breadcrumb can go here --}}
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 hidden sm:block">
                @yield('page-title', 'Dashboard')
            </span>
        </div>

        {{-- Right: Search, Dark mode, User menu --}}
        <div class="flex items-center space-x-3">
            {{-- Global search (optional) --}}
            <div class="hidden md:flex items-center relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="search"
                       placeholder="Search..."
                       class="pl-10 pr-4 py-2 w-64 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
            </div>

            {{-- Dark mode toggle --}}
            <button @click="darkMode = !darkMode"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    aria-label="Toggle dark mode">
                <svg x-show="!darkMode" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg x-show="darkMode" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </button>

            {{-- User dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none">
                    <img class="h-8 w-8 rounded-full object-cover"
                         src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=fff"
                         alt="User avatar">
                    <span class="hidden md:block ml-2">{{ auth()->user()->name }}</span>
                    <svg class="ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                    <a href="{{ route('profile') }}"
                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Your Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>