@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header & Tombol Tambah Barang -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Barang Inventaris</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data inventaris dan ketersediaan stok barang sekolah.</p>
        </div>
        
        {{-- Tombol Tambah Barang hanya untuk Admin --}}
        @if(Auth::check() && Auth::user()->role === 'admin')
            <a href="{{ route('items.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Tambah Barang
            </a>
        @endif
    </div>

    <!-- Alert Notifikasi Sukses -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm flex items-center justify-between">
            <p class="font-medium text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Alert Notifikasi Error -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm flex items-center justify-between">
            <p class="font-medium text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Tabel Daftar Barang -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kondisi</th>
                        <!-- KOLOM KHUSUS BARCODE/QR CODE -->
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Barcode</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Pengembalian & Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item->code }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $item->name }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $item->category->name ?? '-' }}
                            </td>
                            <!-- Format Angka Pemisah Ribuan -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-center font-bold text-gray-700">
                                {{ number_format($item->stock, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                @if($item->condition == 'baik')
                                    <span class="px-3 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Baik</span>
                                @elseif($item->condition == 'rusak ringan')
                                    <span class="px-3 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Rusak ringan</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Rusak berat</span>
                                @endif
                            </td>

                            <!-- ISI KOLOM TERPISAH: TOMBOL BARCODE / QR CODE -->
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('items.barcode.options', $item->id) }}" title="Opsi Cetak Barcode / QR Code" class="inline-flex items-center gap-1 bg-gray-800 hover:bg-gray-900 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m8-8h-1M5 12H4m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M9 9h6v6H9V9z"/>
                                    </svg>
                                    Cetak
                                </a>
                            </td>

                            <!-- KOLOM PENGEMBALIAN & AKSI EDIT/HAPUS -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Form Pinjam Barang (Untuk Semua User) -->
                                    <form action="{{ route('loans.store') }}" method="POST" class="flex items-center gap-1.5 inline">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $item->stock }}" class="w-14 border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 px-2 py-1">
                                        <input type="date" name="due_date" required class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 px-2 py-1">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-3 py-1.5 rounded-lg text-xs transition shadow-sm">
                                            Pinjam
                                        </button>
                                    </form>

                                    <!-- FITUR KHUSUS ADMIN: EDIT & HAPUS -->
                                    @if(Auth::check() && Auth::user()->role === 'admin')
                                        <!-- Tombol Edit Warna Indigo -->
                                        <a href="{{ route('items.edit', $item->id) }}" style="background-color: #4f46e5;" class="hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1 shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang {{ $item->name }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Barang" style="background-color: #ef4444;" class="hover:bg-red-600 text-white p-1.5 rounded-lg text-xs font-medium transition flex items-center justify-center shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-400">
                                Belum ada data barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection