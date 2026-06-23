# 🍛 Website Rumah Makan Padang

Aplikasi sistem informasi berbasis web untuk pengelolaan menu dan autentikasi pada **Rumah Makan Padang**. Proyek ini dibangun menggunakan **PHP Native**, **MySQL** sebagai basis data, dan **Bootstrap 5** untuk menghasilkan tampilan antarmuka yang responsif dan modern.

## ✨ Fitur Utama
- **Autentikasi Multi-User:** Sistem login yang mendukung hak akses Admin dan Pelanggan, lengkap dengan pengamanan password (`password_verify`).
- **Manajemen Akun Mandiri:** Fitur **Ganti Password** dari dalam sistem serta fitur **Lupa Password / Reset Password** langsung di halaman login yang terintegrasi aman dengan database.
- **Pencarian Responsif (Live Search):** Fitur pencarian menu makanan dan minuman secara instan demi kenyamanan pelanggan.
- **Panel CRUD Admin:** Manajemen penuh bagi admin untuk menambah menu baru (termasuk unggah foto/gambar makanan) serta menghapus data menu.

## 🛠️ Teknologi yang Digunakan
- **Backend:** PHP (Native)
- **Database:** MySQL
- **Frontend:** Bootstrap 5, CSS, & JavaScript (AJAX)
- **Environment:** XAMPP

## 🚀 Cara Menjalankan Proyek di Lokal
1. Clone atau unduh repository ini dalam bentuk ZIP.
2. Pindahkan folder proyek ke dalam direktori server lokal Anda (misal: `C:/xampp/htdocs/restoran-padang`).
3. Aktifkan **Apache** dan **MySQL** pada XAMPP Control Panel.
4. Buka `localhost/phpmyadmin`, buat database baru dengan nama `db_restoran`.
5. Import file database **`db_restoran.sql`** yang berada di dalam folder proyek ini ke dalam database tersebut.
6. Akses aplikasi melalui browser dengan mengetik URL `localhost/restoran-padang/login.php`.
