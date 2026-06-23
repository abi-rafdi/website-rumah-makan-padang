<?php
// Mengaktifkan pelacak eror
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';
session_start();

// Gembok Keamanan: Hanya Admin yang bisa melihat halaman ini
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ========================================================
// LOGIKA 1: MENGUBAH STATUS PESANAN MENJADI SELESAI
// ========================================================
if (isset($_GET['selesai'])) {
    $id_pesanan_selesai = $_GET['selesai'];
    $query_update = "UPDATE pesanan SET status_pesanan = 'Selesai' WHERE id_pesanan = '$id_pesanan_selesai'";
    $eksekusi_update = mysqli_query($konek, $query_update);
    if ($eksekusi_update) {
        header("Location: laporan_pesanan.php");
        exit;
    }
}

// ========================================================
// BAGIAN BARU - LOGIKA 2: MENGUBAH STATUS PESANAN MENJADI DIBATALKAN
// ========================================================
if (isset($_GET['batal'])) {
    $id_pesanan_batal = $_GET['batal'];
    $query_batal = "UPDATE pesanan SET status_pesanan = 'Dibatalkan' WHERE id_pesanan = '$id_pesanan_batal'";
    $eksekusi_batal = mysqli_query($konek, $query_batal);
    if ($eksekusi_batal) {
        header("Location: laporan_pesanan.php");
        exit;
    }
}

// Menggabungkan 3 tabel (pesanan, users, menu) menggunakan INNER JOIN
$query = "SELECT pesanan.*, users.nama_lengkap, menu.nama_menu, menu.harga 
          FROM pesanan 
          INNER JOIN users ON pesanan.id_user = users.id
          INNER JOIN menu ON pesanan.id_menu = menu.id
          ORDER BY pesanan.tanggal_pesan DESC";

$ambil_pesanan = mysqli_query($konek, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pesanan - RM Lamak Bana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1 fw-bold">📋 Rekap Pesanan Pelanggan</span>
            <a href="admin.php" class="btn btn-outline-light btn-sm rounded-pill px-3">Kembali ke Panel Admin</a>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Daftar Riwayat Transaksi Website</h5>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Tanggal & Waktu</th>
                                <th>Nama Pelanggan</th>
                                <th>Menu yang Dibeli</th>
                                <th>Porsi</th>
                                <th>Subtotal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($ambil_pesanan) == 0) {
                                echo "<tr><td colspan='8' class='text-center text-muted py-3'>Belum ada pesanan yang masuk.</td></tr>";
                            }
                            
                            while ($row = mysqli_fetch_assoc($ambil_pesanan)) { 
                                $subtotal = $row['harga'] * $row['jumlah'];
                            ?>
                            <tr>
                                <td class="fw-bold">#<?php echo $row['id_pesanan']; ?></td>
                                <td class="small text-muted"><?php echo $row['tanggal_pesan']; ?></td>
                                <td class="fw-bold text-secondary"><?php echo $row['nama_lengkap']; ?></td>
                                <td><?php echo $row['nama_menu']; ?></td>
                                <td><span class="badge bg-secondary">x<?php echo $row['jumlah']; ?></span></td>
                                <td class="fw-bold text-success">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                <td>
                                    <?php if ($row['status_pesanan'] === 'Selesai') { ?>
                                        <span class="badge bg-success">Selesai</span>
                                    <?php } elseif ($row['status_pesanan'] === 'Dibatalkan') { ?>
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning text-dark">Diproses</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($row['status_pesanan'] === 'Diproses') { ?>
                                        
                                        <a href="laporan_pesanan.php?selesai=<?php echo $row['id_pesanan']; ?>" 
                                           class="btn btn-success btn-sm fw-bold rounded-pill px-2 me-1"
                                           onclick="return confirm('Konfirmasi bahwa pesanan #<?php echo $row['id_pesanan']; ?> ini sudah selesai?')">
                                           Selesai ✔️
                                        </a>

                                        <a href="laporan_pesanan.php?batal=<?php echo $row['id_pesanan']; ?>" 
                                           class="btn btn-danger btn-sm fw-bold rounded-pill px-2"
                                           onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan #<?php echo $row['id_pesanan']; ?>?')">
                                           Batalkan ❌
                                        </a>

                                    <?php } else { ?>
                                        <span class="text-muted small">Tidak ada aksi</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</body>
</html>