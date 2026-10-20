@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Opsi Cetak Label Barcode & QR Code</h2>
            <p class="text-sm text-gray-500 mt-1">Sesuaikan format dan ukuran label barang sebelum dicetak.</p>
        </div>
        <a href="{{ route('items.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold transition">
            &larr; Kembali ke Barang
        </a>
    </div>

    <!-- Card Form Opsi -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-6">
        <!-- Informasi Barang -->
        <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Barang Dipilih</p>
                <h3 class="text-lg font-bold text-gray-800 mt-0.5">{{ $item->name }}</h3>
                <p class="text-xs text-gray-500">Kode: <span class="font-mono font-bold text-gray-700">{{ $item->code }}</span> | Kategori: {{ $item->category->name ?? '-' }}</p>
            </div>
            <span class="px-3 py-1 bg-white text-blue-700 text-xs font-bold rounded-lg border border-blue-200 shadow-sm">
                Stok: {{ $item->stock }} Unit
            </span>
        </div>

        <form action="{{ route('items.barcode.print', $item->id) }}" method="GET" target="_blank" class="space-y-6">
            <!-- Pilihan Jenis Code -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">1. Pilih Jenis Format Label</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-blue-500 transition group border-gray-200">
                        <input type="radio" name="type" value="qr" checked class="text-blue-600 focus:ring-blue-500">
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-gray-800">QR Code (2D)</span>
                            <span class="block text-xs text-gray-500 mt-0.5">Format kotak serbaguna, sangat cocok dicetak dalam ukuran kecil.</span>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-blue-500 transition group border-gray-200">
                        <input type="radio" name="type" value="barcode" class="text-blue-600 focus:ring-blue-500">
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-gray-800">Barcode Garis (1D Code128)</span>
                            <span class="block text-xs text-gray-500 mt-0.5">Format standar pemindai barcode minimarket/gudang.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Pilihan Ukuran Label -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">2. Pilih Ukuran Label</label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="p-3 border rounded-xl text-center cursor-pointer hover:bg-gray-50 transition border-gray-200">
                        <input type="radio" name="size" value="sm" class="text-blue-600">
                        <span class="block text-sm font-semibold text-gray-800 mt-1">Kecil</span>
                        <span class="block text-xs text-gray-400">120 px</span>
                    </label>
                    <label class="p-3 border rounded-xl text-center cursor-pointer hover:bg-gray-50 transition border-gray-200">
                        <input type="radio" name="size" value="md" checked class="text-blue-600">
                        <span class="block text-sm font-semibold text-gray-800 mt-1">Sedang</span>
                        <span class="block text-xs text-gray-400">200 px</span>
                    </label>
                    <label class="p-3 border rounded-xl text-center cursor-pointer hover:bg-gray-50 transition border-gray-200">
                        <input type="radio" name="size" value="lg" class="text-blue-600">
                        <span class="block text-sm font-semibold text-gray-800 mt-1">Besar</span>
                        <span class="block text-xs text-gray-400">280 px</span>
                    </label>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Buka Halaman Cetak
                </button>
            </div>
        </form>
    </div>
</div>
@endsection