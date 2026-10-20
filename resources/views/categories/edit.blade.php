@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-xl">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Kategori Barang</h1>
            <p class="text-sm text-gray-500">Perbarui nama kategori inventaris sekolah.</p>
        </div>
        <a href="{{ route('categories.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
            &larr; Kembali
        </a>
    </div>

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
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block font-bold text-sm text-gray-700 mb-1">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" autofocus
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    placeholder="Contoh: Alat Musik, Alat Olahraga" required>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('categories.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 text-sm font-semibold rounded-xl transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection