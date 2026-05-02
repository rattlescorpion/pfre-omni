{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true, darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PFRE-Omni') | Property Finder Real Estate Omni Platform</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    {{-- Styles --}}
    @vite(['resources/css/app.css', 'resources/css/design-tokens.css'])

    {{-- Stack for page-specific styles --}}
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Main Content Wrapper --}}
    <div class="lg:pl-64 flex flex-col min-h-screen" :class="{ 'lg:pl-64': sidebarOpen, 'lg:pl-20': !sidebarOpen }">
        {{-- Top Navigation --}}
        @include('layouts.partials.topnav')

        {{-- Page Content --}}
        <main class="flex-1 p-6 lg:p-8">
            {{-- Flash Messages --}}
            @if (session('success'))
                <x-alert type="success" :message="session('success')" dismissible />
            @endif
            @if (session('error'))
                <x-alert type="error" :message="session('error')" dismissible />
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        @include('layouts.partials.footer')
    </div>

    {{-- Scripts --}}
    @vite(['resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts')
</body>
</html>