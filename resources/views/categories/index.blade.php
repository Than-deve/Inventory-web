@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-7xl">

    <!-- Header Halaman & Pencarian -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Kategori Barang</h1>
            <p class="text-sm text-gray-500">Ringkasan daftar kategori dan jumlah alat inventaris sekolah.</p>
        </div>

        <!-- Form Pencarian -->
        <form method="GET" action="{{ route('categories.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari nama kategori..."
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-64 shadow-sm">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition">
                Cari
            </button>
            @if(!empty($search))
            <a href="{{ route('categories.index') }}" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition flex items-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm mb-6 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Form Tambah Kategori -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
            <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Tambah Kategori Baru</h2>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" autofocus
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('name') border-red-500 @enderror shadow-sm"
                        placeholder="Contoh: Alat Musik, Alat Olahraga" required>
                    @error('name')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg text-sm shadow transition">
                    + Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Kategori -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Daftar Kategori</h3>
                <span class="text-xs bg-gray-200 text-gray-700 font-bold px-2.5 py-1 rounded-full">Total: {{ $categories->count() }} Kategori</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 uppercase text-[11px] font-semibold tracking-wider border-b">
                            <th class="py-3 px-6">Kategori</th>
                            <th class="py-3 px-6 text-center">Jenis Alat</th>
                            <th class="py-3 px-6 text-center">Total Stok Alat</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6 font-semibold text-gray-800">
                                {{ $category->name }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md text-xs font-semibold">
                                    {{ $category->items_count }} Jenis
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-md text-xs font-bold">
                                    {{ $category->total_stock ?? 0 }} Unit
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('categories.edit', $category->id) }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold hover:underline transition">
                                        Edit
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold hover:underline transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400">
                                Data kategori tidak ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection