<?php
include 'koneksi.php';
session_start();

// Proteksi Keamanan: Jika belum melewati verifikasi lupa_password.php, dilarang masuk
if (!isset($_SESSION['reset_username'])) {
    header("Location: lupa_password.php");
    exit;
}

$username_target = $_SESSION['reset_username'];

if (isset($_POST['submit_reset'])) {
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    if ($password_baru === $konfirmasi_password) {
        
        // Menggunakan enkripsi aman password_hash demi mengimbangi fungsi password_verify di login.php kamu
        $password_fix = password_hash($password_baru, PASSWORD_DEFAULT); 

        // Update ke tabel users milikmu
        $query_update = mysqli_query($konek, "UPDATE users SET password = '$password_fix' WHERE username = '$username_target'");

        if ($query_update) {
            // Bersihkan token session reset demi keamanan sistem
            unset($_SESSION['reset_username']);
            
            echo "<script>
                    alert('Password akun Anda berhasil diperbarui! Silakan mencoba login kembali.');
                    window.location.href = 'login.php';
                  </script>";
            exit;
        } else {
            echo "<script>alert('Gagal mereset password ke database.');</script>";
        }
    } else {
        echo "<script>alert('Konfirmasi password baru tidak cocok!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - RM Lamak Bana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-center mb-3">🔒 Buat Password Baru</h4>
                        <p class="text-muted text-center small mb-4">Silakan tentukan kata sandi baru yang aman untuk akun <strong><?php echo htmlspecialchars($username_target); ?></strong>.</p>
                        
                        <form action="reset_password.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Password Baru</label>
                                <input type="password" name="password_baru" class="form-control" placeholder="Minimal 5 karakter" minlength="5" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Ulangi Password Baru</label>
                                <input type="password" name="konfirmasi_password" class="form-control" placeholder="Pastikan ketikan sama persis" required>
                            </div>
                            
                            <button type="submit" name="submit_reset" class="btn btn-success w-100 fw-bold shadow-sm">Simpan Perubahan Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>