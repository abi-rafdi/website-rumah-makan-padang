<?php
include 'koneksi.php';
session_start();

// Gembok Keamanan: Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID User dari session login
$id_user_login = $_SESSION['id_user'];

// KODE KUNCI: Hanya mengambil data pesanan milik user yang sedang login ($id_user_login)
$query = "SELECT pesanan.*, menu.nama_menu, menu.harga 
          FROM pesanan 
          INNER JOIN menu ON pesanan.id_menu = menu.id
          WHERE pesanan.id_user = '$id_user_login'
          ORDER BY pesanan.tanggal_pesan DESC";

$ambil_riwayat = mysqli_query($konek, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Belanja Saya - RM Lamak Bana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-danger sticky-top shadow-sm mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1 fw-bold">🍛 Riwayat Belanja Saya</span>
            <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill px-3">Kembali ke Menu</a>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h6 class="fw-bold text-secondary mb-3">Halo, <?php echo $_SESSION['nama']; ?> 👋 Berikut catatan pesanan Anda:</h6>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle small">
                        <thead class="table-danger">
                            <tr>
                                <th>Tanggal & Waktu</th>
                                <th>Menu Makanan</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Status Pesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($ambil_riwayat) == 0) {
                                echo "<tr><td colspan='5' class='text-center text-muted py-4'>Anda belum pernah memesan makanan di website ini.</td></tr>";
                            }
                            
                            while ($row = mysqli_fetch_assoc($ambil_riwayat)) { 
                                $total_harga = $row['harga'] * $row['jumlah'];
                            ?>
                            <tr>
                                <td class="text-muted"><?php echo $row['tanggal_pesan']; ?></td>
                                <td class="fw-bold"><?php echo $row['nama_menu']; ?></td>
                                <td class="text-center"><span class="badge bg-secondary">x<?php echo $row['jumlah']; ?></span></td>
                                <td class="fw-bold text-danger">Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></td>
                                <td>
                                    <?php if ($row['status_pesanan'] === 'Selesai') { ?>
                                        <span class="badge bg-success">Selesai ✔️</span>
                                    <?php } elseif ($row['status_pesanan'] === 'Dibatalkan') { ?>
                                        <span class="badge bg-danger">Dibatalkan ❌</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning text-dark">Diproses ⏳</span>
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