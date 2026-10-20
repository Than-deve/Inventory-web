<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori beserta fitur pencarian dan kalkulasi stok.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::withCount('items')
            ->withSum('items as total_stock', 'stock')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('categories.index', compact('categories', 'search'));
    }

    /**
     * Menambahkan method edit untuk menampilkan form ubah kategori.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori ini sudah ada.',
        ]);

        $category = Category::create([
            'name' => trim($request->name),
        ]);

        // Catat ke Activity Log
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Menambah Kategori',
            'description' => 'Menambahkan kategori baru: ' . $category->name,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori "' . $category->name . '" berhasil ditambahkan.');
    }

    /**
     * Memperbarui data kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori ini sudah ada.',
        ]);

        $oldName = $category->name;
        $category->update([
            'name' => trim($request->name),
        ]);

        // Catat ke Activity Log
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Mengubah Kategori',
            'description' => 'Mengubah kategori "' . $oldName . '" menjadi "' . $category->name . '"',
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        $categoryName = $category->name;
        $category->delete();

        // Catat ke Activity Log
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Menghapus Kategori',
            'description' => 'Menghapus kategori: ' . $categoryName,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori "' . $categoryName . '" berhasil dihapus.');
    }
}