# PSI-2425ge-01-dco 

Please read [RULES.md](RULES.md).

## General Discription
Sistem Informasi Deltizen Corner berbasis website adalah solusi digital yang dikembangkan untuk meningkatkan efisiensi pemesanan makanan dan minuman di kafe Deltizen Corner, Sitoluama. Sistem ini dirancang untuk menggantikan proses manual yang rentan terhadap kesalahan, dengan mendukung pemesanan online, pembayaran digital, serta pelaporan transaksi secara real-time.


## Features
- Registrasi dan login untuk pelanggan, admin, dan owner
- Pemesanan makanan & minuman secara online
- Checkout dan pemilihan metode pembayaran (QRIS/tunai)
- Upload dan verifikasi bukti pembayaran
- Manajemen menu, kategori, dan stok oleh admin
- Laporan penjualan harian/mingguan/bulanan
- Dashboard pemilik untuk monitoring performa bisnis
- Sistem notifikasi status pesanan secara otomatis
- Fitur cetak invoice dan rekap pesanan

## Architectural Design
- Framework: Laravel (PHP)
- Bahasa: PHP, HTML, CSS, JavaScrip
- Database: MySQL / phpMyAdmin
- Server: Apache (Client-server model)
- Text Editor: Visual Studio Code
- Model Pengembangan: MVC (Model-View-Controller)
- Pengujian: PHP unit

## Database Design
Database dirancang berbasis relasional dengan entitas utama:
- users: data pengguna (admin, owner, customer)
- products: menu makanan/minuman
- categories: kategori produk
- orders: pemesanan
- order_product: relasi antara pesanan dan produk
- confirms: konfirmasi pembayaran & bukti transaksi

## Installation Guide

Berikut saya ubah ke format **README.md** yang rapi dan sesuai standar:

````markdown
# Panduan Setup Proyek Laravel

---

## 1. Clone Repositori

Pertama, gunakan perintah **`git clone`** untuk mengunduh kode proyek dari repositori ke komputer Anda.  

```bash
git clone https://www.andarepository.com/
````

Contoh:

```bash
git clone https://github.com/username/nama-proyek.git
```

Setelah itu, masuk ke direktori proyek:

```bash
cd nama-proyek
```

---

## 2. Install Dependensi (Vendor)

Proyek yang di-*clone* biasanya tidak menyertakan folder `vendor`. Install semua pustaka menggunakan **Composer**:

```bash
composer install
```

Perintah ini akan membaca file `composer.json` dan mengunduh semua dependensi yang diperlukan.

---

## 3. Buat File Environment (.env)

Salin file contoh `.env.example` menjadi file `.env` baru:

```bash
cp .env.example .env
```

> Untuk Windows gunakan:
>
> ```bash
> copy .env.example .env
> ```

---

## 4. Generate Kunci Aplikasi

Laravel membutuhkan kunci enkripsi unik. Buat kunci dengan perintah berikut:

```bash
php artisan key:generate
```

Kunci akan otomatis tersimpan di variabel `APP_KEY` dalam file `.env`.

---

## 5. Konfigurasi Database

Edit file `.env` dan sesuaikan dengan pengaturan database lokal Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=password_anda
```

> Pastikan sudah membuat database kosong sesuai nama di atas.

---

## 6. Jalankan Migrasi Database

Buat tabel database dengan migrasi:

```bash
php artisan migrate
```

Jika perlu mengisi data awal (seeding):

```bash
php artisan db:seed
```

---

## 7. Jalankan Server Pengembangan

Jalankan server bawaan Laravel:

```bash
php artisan serve
```

Buka aplikasi di browser pada alamat berikut:

👉 [http://127.0.0.1:8000](http://127.0.0.1:8000) ✅

---

```

Mau saya tambahkan juga bagian **troubleshooting umum** (misalnya error `storage` permission atau cache) biar lebih lengkap?
```


### Minimum Hardware Requirements
- Prosesor: Intel Core i5 atau setara
- RAM: 8 GB
- Internet/Wi-Fi: untuk testing QRIS dan deployment lokal

### Minimum Software Requirements
- OS: Windows 11
- Laravel (via Composer)
- MySQL / phpMyAdmin
- Apache (XAMPP/Laragon)
- Composer
- Node.js
- Browser (Chrome/Edge)
