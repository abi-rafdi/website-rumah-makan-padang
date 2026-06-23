<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "db_restoran";

// Perintah untuk menyambungkan ke MySQL
$konek = mysqli_connect($host, $user, $password, $database);

// Cek apakah koneksi berhasil atau gagal
if (!$konek) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>