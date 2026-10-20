@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header Welcome Banner (Warna Lebih Kontras & Tegas) -->
    <div style="background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);" class="rounded-2xl p-6 text-white shadow-md">
        <h2 class="text-2xl font-bold text-white tracking-wide">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h2>
        <p class="text-blue-100 text-sm mt-1 font-medium">Berikut adalah ringkasan status inventaris dan aktivitas peminjaman sekolah saat ini.</p>
    </div>

    <!-- Ringkasan Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card Total Barang -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Barang</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalItems }}</h3>
            </div>
            <div class="bg-blue-50 p-3 rounded-xl text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
        </div>

        <!-- Card Kategori -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Kategori</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCategories }}</h3>
            </div>
            <div class="bg-indigo-50 p-3 rounded-xl text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
        </div>

        <!-- Card Peminjaman Aktif -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Peminjaman Aktif</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $activeLoans }}</h3>
            </div>
            <div class="bg-emerald-50 p-3 rounded-xl text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
        </div>

        <!-- Card Total User -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Siswa Terdaftar</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</h3>
            </div>
            <div class="bg-amber-50 p-3 rounded-xl text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Grafik Tren Peminjaman & Peringatan Stok Kritis -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Grafik Peminjaman (2 Kolom) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-base font-bold text-gray-800 mb-4">Grafik Tren Peminjaman Barang (Tahun Ini)</h3>
            <div class="w-full h-64">
                <canvas id="loanChart"></canvas>
            </div>
        </div>

        <!-- Peringatan Stok Kritis (1 Kolom) -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-red-600 flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Peringatan Stok Kritis
                </h3>
                <div class="space-y-3">
                    @forelse($lowStockItems as $lowItem)
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl border border-red-100">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $lowItem->name }}</p>
                                <p class="text-xs text-gray-500">Kode: {{ $lowItem->code }}</p>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-bold bg-red-600 text-white rounded-lg">
                                Stok: {{ $lowItem->stock }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-6">Semua stok barang masih tergolong aman.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- FITUR BARU: Barang Paling Sering Dipinjam & Siswa Paling Aktif -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- List Barang Paling Sering Dipinjam -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Barang Paling Sering Dipinjam
            </h3>
            <div class="divide-y divide-gray-100">
                @forelse($popularItems as $popItem)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $popItem->name }}</p>
                            <p class="text-xs text-gray-500">Kode: {{ $popItem->code }} | Stok Sisa: {{ $popItem->stock }}</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                            {{ $popItem->loans_count }}x Dipinjam
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada data peminjaman barang.</p>
                @endforelse
            </div>
        </div>

        <!-- List Siswa Paling Aktif Meminjam -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Siswa Paling Aktif Meminjam
            </h3>
            <div class="divide-y divide-gray-100">
                @forelse($topBorrowers as $borrower)
                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($borrower->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $borrower->name }}</p>
                                <p class="text-xs text-gray-500">{{ $borrower->email }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded-full border border-indigo-100">
                            {{ $borrower->loans_count }} Transaksi
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada transaksi peminjaman oleh siswa.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('loanChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: @json($chartData),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endsection