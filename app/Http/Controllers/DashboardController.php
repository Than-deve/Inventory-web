<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $totalCategories = Category::count();
        $activeLoans = Loan::where('status', 'dipinjam')->count();
        $totalUsers = User::where('role', 'user')->count();

        // Barang stok kritis (<= 3 unit)
        $lowStockItems = Item::where('stock', '<=', 3)->get();

        // Top 5 Barang Paling Sering Dipinjam
        $popularItems = Item::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 Siswa Paling Aktif Meminjam
        $topBorrowers = User::where('role', 'user')
            ->withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get();

        // Data Grafik Peminjaman Bulanan
        $monthlyLoans = Loan::select(
            DB::raw('MONTH(loan_date) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('loan_date', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Format data 12 bulan untuk Chart.js
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyLoans[$i] ?? 0;
        }

        return view('dashboard', compact(
            'totalItems',
            'totalCategories',
            'activeLoans',
            'totalUsers',
            'lowStockItems',
            'popularItems',
            'topBorrowers',
            'chartData'
        ));
    }
}