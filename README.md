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
- Pengujian: Katalon
- Modeler Proses: Draw.io, Bizagi

## Database Design
Database dirancang berbasis relasional dengan entitas utama:
- users: data pengguna (admin, owner, customer)
- products: menu makanan/minuman
- categories: kategori produk
- orders: pemesanan
- order_product: relasi antara pesanan dan produk
- confirms: konfirmasi pembayaran & bukti transaksi

## Installation Guide

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
