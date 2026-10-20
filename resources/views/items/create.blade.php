@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Barang Baru</h2>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <form action="{{ route('items.store') }}" method="POST">
            @csrf
            
            <!-- Kategori -->
            <div class="mb-4">
                <label class="block font-semibold mb-1 text-sm text-gray-700">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="category_id" class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm" required autofocus>
                    <option value="">-- Pilih Kategori Barang --</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Kode Barang (SKU) -->
            <div class="mb-4">
                <label class="block font-semibold mb-1 text-sm text-gray-700">
                    Kode Barang (SKU) <span class="text-red-500">*</span>
                </label>
                <input type="text" name="code" value="{{ old('code') }}" 
                    class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                    placeholder="Contoh: LAB-004" required>
                <p class="text-xs text-gray-400 mt-1">*Jika kode sudah ada, stok akan otomatis ditambahkan (Upsert).</p>
            </div>

            <!-- Nama Barang -->
            <div class="mb-4">
                <label class="block font-semibold mb-1 text-sm text-gray-700">
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" 
                    class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                    placeholder="Contoh: Mikroskop Binokuler Digital" required>
            </div>

            <!-- Jumlah Stok -->
            <div class="mb-4">
                <label class="block font-semibold mb-1 text-sm text-gray-700">
                    Jumlah Stok <span class="text-red-500">*</span>
                </label>
                <input type="number" name="stock" value="{{ old('stock', 1) }}" min="1" 
                    class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                    placeholder="Contoh: 10" required>
            </div>

            <!-- Kondisi -->
            <div class="mb-6">
                <label class="block font-semibold mb-1 text-sm text-gray-700">
                    Kondisi <span class="text-red-500">*</span>
                </label>
                <select name="condition" class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm" required>
                    <option value="baik">Baik</option>
                    <option value="rusak ringan">Rusak Ringan</option>
                    <option value="rusak berat">Rusak Berat</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('items.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 text-sm font-semibold rounded-xl transition shadow-sm">
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection