<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use App\Models\ReturnLog;
use App\Models\ActivityLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users (1 Admin & 2 Users)
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $user1 = User::create([
            'name' => 'Siswa Pertama',
            'email' => 'user1@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Siswa Kedua',
            'email' => 'user2@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 2. Categories (3 Kategori)
        $catLab = Category::create(['name' => 'Alat Lab']);
        $catOlahraga = Category::create(['name' => 'Alat Olahraga']);
        $catElektronik = Category::create(['name' => 'Elektronik']);

        // 3. Items (10 Barang)
        $item1 = Item::create(['category_id' => $catLab->id, 'name' => 'Mikroskop Binokuler', 'code' => 'LAB-001', 'stock' => 5, 'condition' => 'baik']);
        $item2 = Item::create(['category_id' => $catLab->id, 'name' => 'Tabung Reaksi 10ml', 'code' => 'LAB-002', 'stock' => 50, 'condition' => 'baik']);
        $item3 = Item::create(['category_id' => $catLab->id, 'name' => 'Pembakar Bunsen', 'code' => 'LAB-003', 'stock' => 8, 'condition' => 'rusak ringan']);
        $item4 = Item::create(['category_id' => $catOlahraga->id, 'name' => 'Bola Basket Molten', 'code' => 'OLG-001', 'stock' => 12, 'condition' => 'baik']);
        $item5 = Item::create(['category_id' => $catOlahraga->id, 'name' => 'Bola Voli Mikasa', 'code' => 'OLG-002', 'stock' => 10, 'condition' => 'baik']);
        $item6 = Item::create(['category_id' => $catOlahraga->id, 'name' => 'Raket Badminton', 'code' => 'OLG-003', 'stock' => 15, 'condition' => 'rusak ringan']);
        $item7 = Item::create(['category_id' => $catElektronik->id, 'name' => 'Proyektor Epson', 'code' => 'ELK-001', 'stock' => 4, 'condition' => 'baik']);
        $item8 = Item::create(['category_id' => $catElektronik->id, 'name' => 'Kabel HDMI 10m', 'code' => 'ELK-002', 'stock' => 6, 'condition' => 'baik']);
        $item9 = Item::create(['category_id' => $catElektronik->id, 'name' => 'Speaker Portable', 'code' => 'ELK-003', 'stock' => 2, 'condition' => 'baik']);
        $item10 = Item::create(['category_id' => $catElektronik->id, 'name' => 'Pointer Presentasi', 'code' => 'ELK-004', 'stock' => 3, 'condition' => 'rusak berat']);

        // 4. Loans (5 Peminjaman)
        $loan1 = Loan::create([
            'user_id' => $user1->id,
            'item_id' => $item1->id,
            'quantity' => 1,
            'loan_date' => now()->subDays(10)->format('Y-m-d'),
            'due_date' => now()->subDays(3)->format('Y-m-d'),
            'status' => 'dikembalikan',
        ]);

        $loan2 = Loan::create([
            'user_id' => $user1->id,
            'item_id' => $item4->id,
            'quantity' => 2,
            'loan_date' => now()->subDays(8)->format('Y-m-d'),
            'due_date' => now()->subDays(1)->format('Y-m-d'),
            'status' => 'dikembalikan',
        ]);

        $loan3 = Loan::create([
            'user_id' => $user2->id,
            'item_id' => $item7->id,
            'quantity' => 1,
            'loan_date' => now()->subDays(5)->format('Y-m-d'),
            'due_date' => now()->subDays(2)->format('Y-m-d'),
            'status' => 'dikembalikan',
        ]);

        $loan4 = Loan::create([
            'user_id' => $user2->id,
            'item_id' => $item8->id,
            'quantity' => 1,
            'loan_date' => now()->subDays(2)->format('Y-m-d'),
            'due_date' => now()->addDays(3)->format('Y-m-d'),
            'status' => 'dipinjam',
        ]);

        $loan5 = Loan::create([
            'user_id' => $user1->id,
            'item_id' => $item5->id,
            'quantity' => 1,
            'loan_date' => now()->subDays(7)->format('Y-m-d'),
            'due_date' => now()->subDays(1)->format('Y-m-d'),
            'status' => 'terlambat',
        ]);

        // 5. Return Logs (3 Log Pengembalian)
        ReturnLog::create([
            'loan_id' => $loan1->id,
            'returned_at' => now()->subDays(3)->format('Y-m-d'),
            'condition_on_return' => 'baik',
            'notes' => 'Dikembalikan tepat waktu dan lengkap.',
        ]);

        ReturnLog::create([
            'loan_id' => $loan2->id,
            'returned_at' => now()->subDays(2)->format('Y-m-d'),
            'condition_on_return' => 'baik',
            'notes' => 'Satu bola dalam keadaan kurang angin.',
        ]);

        ReturnLog::create([
            'loan_id' => $loan3->id,
            'returned_at' => now()->subDays(1)->format('Y-m-d'),
            'condition_on_return' => 'baik',
            'notes' => 'Proyektor berfungsi normal.',
        ]);

        // 6. Activity Logs
        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'create_item',
            'description' => 'Admin menambahkan barang baru: Proyektor Epson (ELK-001)',
        ]);

        ActivityLog::create([
            'user_id' => $user1->id,
            'action' => 'request_loan',
            'description' => 'Siswa Pertama mengajukan peminjaman Mikroskop Binokuler',
        ]);

        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'verify_return',
            'description' => 'Admin memverifikasi pengembalian Proyektor Epson oleh Siswa Kedua',
        ]);
    }
}
