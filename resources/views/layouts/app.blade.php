<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Sistem Inventaris</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles (Tailwind via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">

        <!-- SIDEBAR VERTIKAL (KIRI) -->
        <aside class="w-64 bg-blue-600 text-white flex flex-col justify-between shrink-0 shadow-lg">
            <div>
                <!-- Brand / Logo -->
                <div class="p-6 flex items-center gap-3 border-b border-blue-500/50">
                    <div class="bg-white/10 p-2 rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-wider uppercase">Inventaris</span>
                </div>

                <!-- Navigasi Utama -->
                <nav class="p-4 space-y-1">
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white shadow-inner font-bold' : 'text-blue-100 hover:bg-blue-500/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 00-1 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    {{-- Barang (Semua User) --}}
                    <a href="{{ route('items.index') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('items.*') ? 'bg-blue-700 text-white shadow-inner font-bold' : 'text-blue-100 hover:bg-blue-500/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        Barang
                    </a>

                    {{-- Kategori (KHUSUS ADMIN) --}}
                    @if(Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ route('categories.index') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('categories.*') ? 'bg-blue-700 text-white shadow-inner font-bold' : 'text-blue-100 hover:bg-blue-500/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Kategori
                    </a>
                    @endif

                    {{-- Peminjaman Saya (Untuk User / Siswa) --}}
                    @if(Auth::check() && Auth::user()->role !== 'admin')
                    <a href="{{ route('loans.my') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('loans.my') ? 'bg-blue-700 text-white shadow-inner font-bold' : 'text-blue-100 hover:bg-blue-500/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Peminjaman Saya
                    </a>
                    @endif

                    {{-- Kelola Peminjaman (KHUSUS ADMIN) --}}
                    @if(Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ route('admin.loans.index') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.loans.*') ? 'bg-blue-700 text-white shadow-inner font-bold' : 'text-blue-100 hover:bg-blue-500/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Kelola Peminjaman
                    </a>
                    @endif

                    {{-- Activity Log --}}
                    <a href="{{ route('activity-logs.index') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('activity-logs.*') ? 'bg-blue-700 text-white shadow-inner font-bold' : 'text-blue-100 hover:bg-blue-500/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Activity Log
                    </a>
                </nav>
            </div>

            <!-- Profil User di Bawah Sidebar -->
            <div class="p-4 border-t border-blue-500/50">
                <div class="flex items-center gap-3 bg-blue-700/50 p-3 rounded-xl">
                    <div class="w-9 h-9 rounded-full bg-white text-blue-600 font-bold flex items-center justify-center text-sm shadow">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold truncate">{{ Auth::user()->name ?? 'Pengguna' }}</p>
                        <p class="text-xs text-blue-200 capitalize truncate">{{ Auth::user()->role ?? 'User' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- KONTEN UTAMA (KANAN) -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">

            <!-- Top Header Bar -->
            <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between shadow-sm">
                <div>
                    <h1 class="text-lg font-bold text-gray-800">Dashboard</h1>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-600">
                        {{ Auth::user()->name ?? '' }}
                        <span class="text-xs text-gray-400">({{ Auth::user()->role ?? 'user' }})</span>
                    </span>

                    <!-- Form Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Halaman Konten Utama -->
            <main class="flex-1">
                @yield('content')
            </main>
        </div>

    </div>
</body>

</html>