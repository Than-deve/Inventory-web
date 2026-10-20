<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Menampilkan daftar barang dengan fitur pencarian dan filter kategori.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $items = Item::with('category')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->latest()
            ->get();

        $categories = Category::all();

        return view('items.index', compact('items', 'categories'));
    }

    /**
     * Menampilkan form tambah barang (Khusus Admin).
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat menambah barang.');
        }

        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * Menyimpan barang baru atau menambah stok otomatis (Upsert) jika kode barang sudah ada.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat menyimpan barang.');
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50',
            'stock'       => 'required|integer|min:1',
            'condition'   => 'required|in:baik,rusak ringan,rusak berat',
        ]);

        $existingItem = Item::where('code', $request->code)->first();

        if ($existingItem) {
            $existingItem->increment('stock', $request->stock);
            $existingItem->update([
                'condition'   => $request->condition,
                'category_id' => $request->category_id,
            ]);

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'action'      => 'update_item_stock',
                'description' => 'Menambah stok barang: ' . $existingItem->name . ' (' . $existingItem->code . ') sebanyak ' . $request->stock . ' unit',
            ]);

            return redirect()->route('items.index')->with('success', 'Stok barang ' . $existingItem->name . ' berhasil ditambahkan sebanyak ' . $request->stock . ' unit.');
        }

        $item = Item::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'code'        => $request->code,
            'stock'       => $request->stock,
            'condition'   => $request->condition,
        ]);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'create_item',
            'description' => 'Menambahkan barang baru: ' . $item->name . ' (' . $item->code . ')',
        ]);

        return redirect()->route('items.index')->with('success', 'Barang baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit barang (Khusus Admin).
     */
    public function edit(Item $item)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat mengedit barang.');
        }

        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Memperbarui data barang di database (Khusus Admin).
     */
    public function update(Request $request, Item $item)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat mengedit barang.');
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:items,code,' . $item->id,
            'stock'       => 'required|integer|min:0',
            'condition'   => 'required|in:baik,rusak ringan,rusak berat',
        ]);

        $item->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'code'        => $request->code,
            'stock'       => $request->stock,
            'condition'   => $request->condition,
        ]);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'update_item',
            'description' => 'Mengubah data barang: ' . $item->name . ' (' . $item->code . ')',
        ]);

        return redirect()->route('items.index')->with('success', 'Data barang ' . $item->name . ' berhasil diperbarui.');
    }

    /**
     * Menghapus data barang (Khusus Admin).
     */
    public function destroy(Item $item)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat menghapus barang.');
        }

        $itemName = $item->name;
        $itemCode = $item->code;

        $item->delete();

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'delete_item',
            'description' => 'Menghapus barang: ' . $itemName . ' (' . $itemCode . ')',
        ]);

        return redirect()->route('items.index')->with('success', 'Barang ' . $itemName . ' berhasil dihapus.');
    }

    /**
     * Menampilkan label QR Code standar siap cetak.
     */
    public function qrcode(Item $item)
    {
        return view('items.qrcode', compact('item'));
    }

    /**
     * Menampilkan halaman opsi cetak Barcode / QR Code.
     */
    public function barcodeOptions(Item $item)
    {
        return view('items.barcode_options', compact('item'));
    }

    /**
     * Merender cetak Barcode / QR Code berdasarkan pilihan opsi admin.
     */
    public function printBarcode(Request $request, Item $item)
    {
        $type = $request->input('type', 'qr');
        $size = $request->input('size', 'md');

        $dimension = match ($size) {
            'sm'    => 120,
            'lg'    => 280,
            default => 200,
        };

        return view('items.barcode_print', compact('item', 'type', 'dimension'));
    }
}