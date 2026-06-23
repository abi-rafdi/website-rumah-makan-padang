<?php
include 'koneksi.php';
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'pesan' => 'Belum login']);
    exit;
}

// Tangkap kiriman data dari JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['keranjang'])) {
    $id_user = $_SESSION['id_user'];

    foreach ($data['keranjang'] as $item) {
        $nama_menu = $item['nama'];
        $jumlah = $item['jumlah'];

        // Cari ID Menu berdasarkan Nama Menu dari database
        $cari_menu = mysqli_query($konek, "SELECT id FROM menu WHERE nama_menu = '$nama_menu'");
        if (mysqli_num_rows($cari_menu) > 0) {
            $row_menu = mysqli_fetch_assoc($cari_menu);
            $id_menu = $row_menu['id'];

            // Simpan setiap item pesanan ke dalam database
            $query_simpan = "INSERT INTO pesanan (id_user, id_menu, jumlah) VALUES ('$id_user', '$id_menu', '$jumlah')";
            mysqli_query($konek, $query_simpan);
        }
    }
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Keranjang kosong']);
}
?>