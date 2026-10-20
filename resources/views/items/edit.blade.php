@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Data Barang</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi detail barang inventaris.</p>
        </div>
        <a href="{{ route('items.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
            &larr; Kembali
        </a>
    </div>

    @if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('items.update', $item->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Kategori -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Kategori Barang <span class="text-red-500">*</span>
                </label>
                <select name="category_id" required autofocus class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm p-3 bg-white shadow-sm">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Kode Barang -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Kode Barang (SKU) <span class="text-red-500">*</span>
                </label>
                <input type="text" name="code" value="{{ old('code', $item->code) }}" required 
                    placeholder="Contoh: LAB-004"
                    class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm p-3 shadow-sm">
            </div>

            <!-- Nama Barang -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $item->name) }}" required 
                    placeholder="Contoh: Mikroskop Binokuler"
                    class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm p-3 shadow-sm">
            </div>

            <!-- Stok & Kondisi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Jumlah Stok <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', $item->stock) }}" min="0" required 
                        placeholder="Contoh: 10"
                        class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm p-3 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Kondisi Barang <span class="text-red-500">*</span>
                    </label>
                    <select name="condition" required class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm p-3 bg-white shadow-sm">
                        <option value="baik" {{ old('condition', $item->condition) == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak ringan" {{ old('condition', $item->condition) == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak berat" {{ old('condition', $item->condition) == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="pt-4 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('items.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection