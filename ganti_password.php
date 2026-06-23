<?php
include 'koneksi.php';
session_start();

// Gembok Keamanan: Pastikan user sudah login terlebih dahulu
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username_sekarang = $_SESSION['username'];

if (isset($_POST['ganti_password'])) {
    $password_lama = mysqli_real_escape_string($konek, $_POST['password_lama']);
    $password_baru = mysqli_real_escape_string($konek, $_POST['password_baru']);
    $konfirmasi_password = mysqli_real_escape_string($konek, $_POST['konfirmasi_password']);

    // SUDAH DIPERBAIKI: Menggunakan nama tabel 'users' sesuai databasemu
    $query_cek = mysqli_query($konek, "SELECT password FROM users WHERE username = '$username_sekarang'");
    $data_user = mysqli_fetch_assoc($query_cek);

    if ($data_user) {
        
        // Menyesuaikan logika pencatatan login kamu: mendukung teks biasa ATAU hash bawaan PHP
        if ($password_lama === $data_user['password'] || password_verify($password_lama, $data_user['password'])) {
            
            // Cek apakah password baru dan konfirmasi cocok
            if ($password_baru === $konfirmasi_password) {
                
                // Menggunakan password_hash demi mengimbangi fungsi password_verify di login.php kamu
                $password_fix = password_hash($password_baru, PASSWORD_DEFAULT); 

                // SUDAH DIPERBAIKI: Menggunakan nama tabel 'users'
                $query_update = "UPDATE users SET password = '$password_fix' WHERE username = '$username_sekarang'";
                
                if (mysqli_query($konek, $query_update)) {
                    echo "<script>
                            alert('Password berhasil diubah! Silakan login kembali dengan password baru.');
                            window.location.href = 'logout.php';
                          </script>";
                    exit;
                } else {
                    echo "<script>alert('Gagal memperbarui password di database.');</script>";
                }

            } else {
                echo "<script>alert('Konfirmasi password baru tidak cocok!');</script>";
            }

        } else {
            echo "<script>alert('Password lama yang Anda masukkan salah!');</script>";
        }

    } else {
        echo "<script>alert('User tidak ditemukan.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - RM Lamak Bana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-danger shadow-sm mb-4">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">🔒 Keamanan Akun</span>
            <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill px-3">⬅️ Kembali ke Menu</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-center mb-4">Ganti Password Akun</h5>
                        
                        <form action="ganti_password.php" method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Password Sekarang (Lama)</label>
                                <input type="password" name="password_lama" class="form-control" placeholder="Masukkan password saat ini" required>
                            </div>
                            
                            <hr class="text-muted my-3">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Password Baru</label>
                                <input type="password" name="password_baru" class="form-control" placeholder="Minimal 5 karakter" minlength="5" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Ulangi Password Baru</label>
                                <input type="password" name="konfirmasi_password" class="form-control" placeholder="Pastikan ketikan sama persis" required>
                            </div>
                            
                            <button type="submit" name="ganti_password" class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm py-2">
                                Perbarui Password Akun
                            </button>

                        </form>
                    </div>
                </div>
                
                <p class="text-center text-muted small mt-3" style="font-size: 11px;">
                    Setelah berhasil mengganti password, sistem akan otomatis me-logout Anda demi keamanan akun.
                </p>
            </div>
        </div>
    </div>

</body>
</html>