# Sistem Inventaris Sekolah

Aplikasi Web Manajemen Inventaris Sekolah berbasis Laravel untuk mengelola barang, kategori, transaksi peminjaman, serta audit trail log aktivitas.

---

## 🔑 Kredensial Pengujian (Seeder)

Gunakan akun berikut setelah menjalankan seeder untuk login ke aplikasi:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@sekolah.sch.id` | `password123` |
| **User 1** | `user1@sekolah.sch.id` | `password123` |
| **User 2** | `user2@sekolah.sch.id` | `password123` |

---

## 💻 Panduan Instalasi & Eksekusi Local Server

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di komputer lokal:

1. **Install Dependencies PHP & JavaScript:**
   ```bash
   composer install
   npm install && npm run build

   Salin File Environment & Generate Key:


1. cp .env.example .env
php artisan key:generate
Konfigurasi Database & Jalankan Migration + Seeder:
(Pastikan database inven sudah dibuat di MySQL/phpMyAdmin)


2. php artisan migrate:fresh --seed
Jalankan Aplikasi Laravel:


3. php artisan serve
Buka browser dan akses: http://127.0.0.1:8000