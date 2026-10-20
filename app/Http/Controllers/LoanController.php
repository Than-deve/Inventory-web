<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Item;
use App\Models\ActivityLog;
use App\Models\ReturnLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Menampilkan daftar peminjaman milik user/siswa yang sedang login.
     */
    public function myLoans()
    {
        // Update otomatis status terlambat untuk barang yang lewat tenggat
        Loan::where('status', 'dipinjam')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'terlambat']);

        $loans = Loan::with(['item', 'returnLog'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('loans.my_loans', compact('loans'));
    }

    /**
     * Menampilkan semua data peminjaman untuk Admin (Approval & Monitoring).
     */
    public function adminIndex(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // Update otomatis status terlambat untuk barang yang lewat tenggat
        Loan::where('status', 'dipinjam')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'terlambat']);

        $status = $request->input('status');

        $loans = Loan::with(['user', 'item', 'returnLog'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->get();

        return view('loans.admin_index', compact('loans', 'status'));
    }

    /**
     * Pengajuan peminjaman oleh User (Siswa).
     * Menggunakan status 'proses' agar aman dari pembatasan panjang karakter database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id'  => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($item->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi untuk peminjaman ini.');
        }

        Loan::create([
            'user_id'   => Auth::id(),
            'item_id'   => $item->id,
            'quantity'  => $request->quantity,
            'loan_date' => now()->toDateString(),
            'due_date'  => $request->due_date,
            'status'    => 'proses', // Status awal pengajuan (belum disetujui admin)
        ]);

        // Catat di Activity Log
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Mengajukan Peminjaman',
            'description' => 'Mengajukan peminjaman ' . $item->name . ' sebanyak ' . $request->quantity . ' unit (Menunggu Persetujuan)',
        ]);

        return redirect()->back()->with('success', 'Pengajuan peminjaman barang ' . $item->name . ' berhasil dikirim.');
    }

    /**
     * Admin Menyetujui Pengajuan Peminjaman.
     */
    public function approve(Loan $loan)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        if ($loan->item->stock < $loan->quantity) {
            return redirect()->back()->with('error', 'Stok barang tidak cukup untuk menyetujui peminjaman ini.');
        }

        // Potong stok barang & ubah status menjadi dipinjam
        $loan->item->decrement('stock', $loan->quantity);
        $loan->update(['status' => 'dipinjam']);

        // Catat di Activity Log
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Menyetujui Peminjaman',
            'description' => 'Menyetujui peminjaman ' . $loan->item->name . ' untuk ' . ($loan->user->name ?? 'Siswa'),
        ]);

        return redirect()->back()->with('success', 'Peminjaman berhasil disetujui dan stok telah dikurangi.');
    }

    /**
     * Memproses Pengembalian Barang oleh Admin.
     */
    public function processReturn(Request $request, Loan $loan)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'condition_on_return' => 'required|in:baik,rusak ringan,rusak berat',
            'notes'               => 'nullable|string',
        ]);

        // Update status peminjaman menjadi dikembalikan
        $loan->update(['status' => 'dikembalikan']);

        // Kembalikan stok barang
        $loan->item->increment('stock', $loan->quantity);

        // Catat ke ReturnLog
        ReturnLog::create([
            'loan_id'             => $loan->id,
            'returned_at'         => now()->toDateString(),
            'condition_on_return' => $request->condition_on_return,
            'notes'               => $request->notes,
        ]);

        // Catat di Activity Log
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Proses Pengembalian',
            'description' => 'Memproses pengembalian ' . $loan->item->name . ' milik ' . ($loan->user->name ?? 'Siswa') . ' (Kondisi: ' . ucfirst($request->condition_on_return) . ')',
        ]);

        return redirect()->back()->with('success', 'Pengembalian barang berhasil diproses dan stok telah diperbarui.');
    }

    /**
     * Menghapus riwayat peminjaman (Khusus Admin).
     */
    public function destroy(Loan $loan)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // Jika dihapus saat status dipinjam/terlambat, kembalikan stoknya terlebih dahulu
        if ($loan->status === 'dipinjam' || $loan->status === 'terlambat') {
            $loan->item->increment('stock', $loan->quantity);
        }

        $loan->delete();

        return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus.');
    }

    /**
     * Menampilkan halaman laporan peminjaman siap cetak / simpan PDF.
     */
    public function exportPdf(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // Update otomatis status terlambat sebelum dicetak
        Loan::where('status', 'dipinjam')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'terlambat']);

        $status = $request->input('status');

        $loans = Loan::with(['user', 'item', 'returnLog'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->get();

        return view('loans.pdf', compact('loans', 'status'));
    }
}