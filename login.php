<?php
include 'koneksi.php';
session_start(); 

if (isset($_POST['tombol_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data user berdasarkan username yang diketik
    $ambil_user = mysqli_query($konek, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($ambil_user) === 1) {
        $data_user = mysqli_fetch_assoc($ambil_user);
        
        // Logika pencocokan: Mendukung password teks biasa (admin123) ATAU password terenkripsi
        if ($password === $data_user['password'] || password_verify($password, $data_user['password'])) {
            
            // Daftarkan data ke session browser
            $_SESSION['id_user'] = $data_user['id'];
            $_SESSION['username'] = $data_user['username'];
            $_SESSION['nama'] = $data_user['nama_lengkap'];
            $_SESSION['role'] = $data_user['role'];

            // Alirkan halaman sesuai role
            if ($data_user['role'] === 'admin') {
                header("Location: admin.php"); 
            } else {
                header("Location: index.php"); 
            }
            exit; // Hentikan script agar proses redirect berjalan sempurna
        }
    }
    // Jika tidak cocok, lempar pesan eror tulisan salah
    echo "<script>alert('Username atau Password salah!');</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RM Lamak Bana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                
                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') { ?>
                    <div class="alert alert-danger text-center small p-2 mb-3 shadow-sm rounded-3" role="alert">
                        🛑 <strong>Akses Ditolak!</strong> Anda harus login sebagai Admin terlebih dahulu.
                    </div>
                <?php } ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-center mb-4">Silakan Login</h4>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            
                            <div class="mb-3 text-end">
                                <a href="lupa_password.php" class="text-decoration-none small text-danger fw-bold">Lupa Password?</a>
                            </div>

                            <button type="submit" name="tombol_login" class="btn btn-danger w-100 fw-bold mb-3">Masuk</button>
                            <p class="text-center small text-muted mb-0">Belum punya akun? <a href="daftar.php" class="text-danger text-decoration-none fw-bold">Daftar di sini</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>