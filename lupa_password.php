<?php
include 'koneksi.php';
session_start();

if (isset($_POST['cek_akun'])) {
    $username     = mysqli_real_escape_string($konek, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($konek, $_POST['nama_lengkap']);

    // SUDAH DIPERBAIKI: Menggunakan tabel 'users' sesuai database RM Lamak Bana
    $query = mysqli_query($konek, "SELECT * FROM users WHERE username = '$username' AND nama_lengkap = '$nama_lengkap'");
    
    if (mysqli_num_rows($query) > 0) {
        // Simpan username di session sementara untuk divalidasi ke halaman reset
        $_SESSION['reset_username'] = $username;
        header("Location: reset_password.php");
        exit;
    } else {
        echo "<script>alert('Username atau Nama Lengkap tidak cocok dengan data kami!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - RM Lamak Bana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-center mb-3">🤔 Lupa Password</h4>
                        <p class="text-muted text-center small mb-4">Masukkan data identitas akun Anda untuk melakukan verifikasi keselamatan.</p>
                        
                        <form action="lupa_password.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Username Anda</label>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Nama Lengkap (Sesuai Pendaftaran)</label>
                                <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan nama lengkap Anda" required>
                            </div>
                            
                            <button type="submit" name="cek_akun" class="btn btn-danger w-100 fw-bold mb-3 shadow-sm">Verifikasi Identitas Akun</button>
                            <div class="text-center">
                                <a href="login.php" class="text-decoration-none small text-muted">⬅️ Kembali ke Halaman Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>