# Panduan Menjalankan TaxFlow di Windows

## 1. Informasi Project

TaxFlow merupakan aplikasi berbasis Laravel untuk simulasi pengelolaan Merchant, Jenis Pajak, dan Transaksi Pajak.

| Komponen | Teknologi |
|---|---|
| Backend | Laravel 13 |
| Bahasa Pemrograman | PHP 8.5 |
| Database | PostgreSQL 18 |
| Frontend | Laravel Blade, Bootstrap, CSS, JavaScript |
| Sistem Operasi Pengembangan | Windows |
| Database Aplikasi | `taxflow_db` |
| Database Pengujian | `taxflow_test` |
| Repository | https://github.com/fikriandarlan/taxflow |

Lokasi project pada komputer pengembangan:

```text
C:\Users\support\Projects\taxflow
```

## 2. Menjalankan Kembali Project

### Langkah 1 — Buka PowerShell

Masuk ke direktori project:

```powershell
Set-Location "C:\Users\support\Projects\taxflow"
```

Periksa bahwa Laravel tersedia:

```powershell
php artisan --version
```

Jika perintah `php` tidak dikenali, periksa konfigurasi PHP pada PATH Windows.

### Langkah 2 — Periksa PostgreSQL

```powershell
Get-Service postgresql-x64-18
```

Hasil yang diharapkan:

```text
Status   Name
------   ----
Running  postgresql-x64-18
```

Jika layanan berhenti, jalankan PowerShell sebagai Administrator, kemudian:

```powershell
Start-Service postgresql-x64-18
```

Jangan menghapus database atau membuat ulang migration untuk mengaktifkan project.

### Langkah 3 — Periksa kondisi Git

```powershell
git status -sb
```

Branch pengembangan utama adalah `main`.

Periksa kondisi Git sebelum melakukan perubahan baru.

Jika ada perubahan lokal, jangan menggunakan `git restore .` atau `git reset --hard` tanpa pemeriksaan terlebih dahulu.

### Langkah 4 — Periksa port 8000

```powershell
Get-NetTCPConnection -LocalPort 8000 -State Listen -ErrorAction SilentlyContinue
```

Jika tidak ada output, port tersebut kemungkinan tersedia.

Jika ada proses yang menggunakan port 8000, periksa terlebih dahulu sebelum memutuskan tindakan.

Alternatif: jalankan TaxFlow pada port 8001.

### Langkah 5 — Jalankan Laravel

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

Jika berhasil:

```text
Server running on [http://127.0.0.1:8000].
```

Terminal yang menjalankan server harus dibiarkan terbuka.

### Langkah 6 — Buka aplikasi

Buka browser:

```text
http://127.0.0.1:8000/login
```

Login menggunakan akun TaxFlow yang sudah dibuat.

Setelah berhasil Login, pengguna diarahkan ke:

```text
http://127.0.0.1:8000/dashboard
```

Menu yang tersedia:

- Dashboard
- Merchant
- Jenis Pajak
- Transaksi

### Langkah 7 — Vite (Jika Diperlukan)

Jika perubahan frontend menggunakan asset yang dikompilasi Vite, buka terminal kedua:

```powershell
Set-Location "C:\Users\support\Projects\taxflow"
npm run dev
```

Jika halaman hanya menggunakan stylesheet dan JavaScript dari direktori `public` serta Bootstrap CDN, Vite tidak harus dijalankan.

## 3. Cara Menghentikan Project

### Langkah 1 — Hentikan Laravel

Di terminal yang menjalankan:

```powershell
php artisan serve
```

Tekan:

```text
Ctrl + C
```

### Langkah 2 — Hentikan Vite

Jika menjalankan `npm run dev`, tekan `Ctrl + C` pada terminal Vite.

### Langkah 3 — Periksa Git

```powershell
git status -sb
```

Jika ada perubahan yang ingin disimpan, lakukan review, commit, dan push terlebih dahulu.

### Langkah 4 — Tutup Aplikasi

Setelah server berhenti dan pekerjaan tersimpan, tutup PowerShell dan VS Code.

PostgreSQL boleh tetap berjalan jika digunakan project lainnya.

## 4. Menyimpan Perubahan ke GitHub

Periksa perubahan:

```powershell
git status -sb
git diff --check
```

Tambahkan file yang memang ingin disimpan menggunakan `git add` dengan path yang spesifik.

Lakukan commit:

```powershell
git commit -m "deskripsi perubahan"
```

Kemudian push:

```powershell
git push origin main
```

Periksa kembali:

```powershell
git status -sb
git log -1 --oneline
```

Jangan memasukkan `.env`, `.env.testing`, password, atau informasi rahasia ke GitHub.

## 5. Troubleshooting

### PostgreSQL Tidak Berjalan

Periksa:

```powershell
Get-Service postgresql-x64-18
```

Jika berhenti, jalankan layanannya dengan hak Administrator.

### Port 8000 Sudah Digunakan

Periksa:

```powershell
Get-NetTCPConnection -LocalPort 8000 -State Listen -ErrorAction SilentlyContinue
```

Gunakan port lain apabila proses yang menggunakan port 8000 masih diperlukan:

```powershell
php artisan serve --port=8001
```

### Laravel Tidak Bisa Terhubung ke Database

Periksa konfigurasi database pada `.env` secara lokal tanpa membagikan password.

Pastikan PostgreSQL aktif dan database `taxflow_db` tersedia.

Jangan menjalankan `migrate:fresh` atau `db:wipe` untuk memperbaiki koneksi.

### CSS atau Tampilan Tidak Berubah

Coba:

```powershell
php artisan view:clear
```

Kemudian refresh browser menggunakan `Ctrl + F5`.

Periksa apakah file CSS yang benar sudah dimuat.

### Project Tidak Bisa Dijalankan

Periksa:

```powershell
php --version
php artisan --version
git status -sb
```

Baca pesan kesalahan terlebih dahulu sebelum mengubah konfigurasi.

## 6. Catatan Keamanan dan Data

- `taxflow_db` adalah database aplikasi utama.
- `taxflow_test` digunakan untuk pengujian otomatis.
- Jangan menjalankan pengujian yang menghapus data pada database utama.
- Data transaksi yang sudah tersimpan tidak boleh diubah hanya untuk percobaan.
- Jangan menyimpan kredensial database ke dalam repository.

## 7. Checkpoint Pengembangan

Commit UI yang telah berhasil disimpan:

```text
c687886
```

Pekerjaan yang telah diselesaikan meliputi Dashboard, sidebar interaktif, mode gelap/terang, jam Jakarta WIB, notifikasi modal, dropdown Kota/Kabupaten Merchant, dan sorting tabel Transaksi.

Tahap pengembangan berikutnya adalah pengujian otomatis dan regresi Dashboard serta sorting Transaksi menggunakan database `taxflow_test`.

---

**Catatan:** panduan ini dibuat untuk lingkungan pengembangan Windows yang telah terpasang PHP, Composer dependencies, Node.js jika diperlukan, PostgreSQL, dan source TaxFlow. Ini bukan panduan instalasi dari nol.