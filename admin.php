<?php
include 'koneksi.php';
session_start();

// Gembok Keamanan: Pastikan yang masuk adalah admin (Anti-sensitif huruf kapital)
if (!isset($_SESSION['username']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit;
}

// ========================================================
// LOGIKA PROSES TAMBAH MENU DENGAN UPLOAD GAMBAR & KATEGORI
// ========================================================
if (isset($_POST['tambah_menu'])) {
    $nama_menu = mysqli_real_escape_string($konek, $_POST['nama_menu']);
    $harga     = mysqli_real_escape_string($konek, $_POST['harga']);
    $deskripsi = mysqli_real_escape_string($konek, $_POST['deskripsi']);
    $kategori  = mysqli_real_escape_string($konek, $_POST['kategori']); // Menangkap input kategori baru
    
    // Ambil informasi file yang di-upload
    $nama_file   = $_FILES['foto_menu']['name'];
    $ukuran_file = $_FILES['foto_menu']['size'];
    $error_file  = $_FILES['foto_menu']['error'];
    $tmp_file    = $_FILES['foto_menu']['tmp_name'];
    
    if ($error_file === 0) {
        $ekstensi_valid = ['jpg', 'jpeg', 'png'];
        $ekstensi_file  = explode('.', $nama_file);
        $ekstensi_file  = strtolower(end($ekstensi_file));
        
        if (in_array($ekstensi_file, $ekstensi_valid)) {
            if ($ukuran_file < 2048000) { // Maksimal 2MB
                
                $nama_file_baru = uniqid() . '.' . $ekstensi_file;
                $jalur_simpan   = 'uploads/' . $nama_file_baru;
                
                // Buat folder uploads jika belum ada demi mencegah error stream
                if (!is_dir('uploads/')) {
                    mkdir('uploads/', 0777, true);
                }
                
                if (move_uploaded_file($tmp_file, $jalur_simpan)) {
                    // MEMASUKKAN DATA KATEGORI KE DALAM QUERY DATABASE
                    $query_tambah = "INSERT INTO menu (nama_menu, harga, deskripsi, kategori, foto) 
                                     VALUES ('$nama_menu', '$harga', '$deskripsi', '$kategori', '$jalur_simpan')";
                    
                    if (mysqli_query($konek, $query_tambah)) {
                        header("Location: admin.php?sukses=tambah");
                        exit;
                    } else {
                        echo "<script>alert('Gagal menyimpan data ke database: " . mysqli_error($konek) . "');</script>";
                    }
                } else {
                    echo "<script>alert('Gagal mengunggah gambar ke server');</script>";
                }
            } else {
                echo "<script>alert('Ukuran gambar terlalu besar! Maksimal 2MB');</script>";
            }
        } else {
            echo "<script>alert('Format file harus JPG, JPEG, atau PNG!');</script>";
        }
    } else {
        echo "<script>alert('Silakan pilih file gambar terlebih dahulu!');</script>";
    }
}

// Logika Hapus Menu
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    
    $cari_foto = mysqli_query($konek, "SELECT foto FROM menu WHERE id = '$id_hapus'");
    $data_foto = mysqli_fetch_assoc($cari_foto);
    if (file_exists($data_foto['foto'])) {
        unlink($data_foto['foto']); 
    }
    
    mysqli_query($konek, "DELETE FROM menu WHERE id = '$id_hapus'");
    header("Location: admin.php?sukses=hapus");
    exit;
}

// Ambil data menu untuk ditampilkan di tabel kanan
$ambil_data = mysqli_query($konek, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - RM Lamak Bana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">⚙️ Panel Admin - RM Lamak Bana</span>
            
            <div class="d-flex align-items-center">
                <a href="laporan_pesanan.php" class="btn btn-warning btn-sm rounded-pill px-3 me-2 fw-bold text-dark">📋 Riwayat Pesanan</a>
                
                <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill px-3 me-2">Lihat Aplikasi</a>
                
                <a href="logout.php" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Logout 🚨</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row g-4">
            
            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Tambah Menu Baru</h5>
                        
                        <form action="admin.php" method="POST" enctype="multipart/form-data">
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Makanan/Minuman</label>
                                <input type="text" name="nama_menu" class="form-control" placeholder="Contoh: Nasi Rendang Daging" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Harga (Angka saja)</label>
                                <input type="number" name="harga" class="form-control" placeholder="Contoh: 25000" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Kategori Menu</label>
                                <select name="kategori" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    <option value="makanan">🍖 Makanan</option>
                                    <option value="minuman">🍹 Minuman</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Tuliskan komposisi menu..." required></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Unggah Foto Makanan (.jpg / .png)</label>
                                <input type="file" name="foto_menu" class="form-control" accept="image/*" required>
                                <div class="form-text" style="font-size: 11px;">Maksimal ukuran file 2MB.</div>
                            </div>
                            
                            <button type="submit" name="tambah_menu" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm">
                                Simpan ke Database
                            </button>
                        </form>
                        
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Daftar Menu Sekarang</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Preview</th>
                                        <th>Menu</th>
                                        <th>Harga</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($menu = mysqli_fetch_assoc($ambil_data)) { ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo $menu['foto']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" onerror="this.src='https://placehold.co/50x50?text=No+Img'">
                                        </td>
                                        <td class="fw-bold">
                                            <?php echo $menu['nama_menu']; ?>
                                            <br><small class="badge bg-secondary text-capitalize" style="font-size: 9px; font-weight: normal;"><?php echo isset($menu['kategori']) ? $menu['kategori'] : '-'; ?></small>
                                        </td>
                                        <td class="text-muted">Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <a href="admin.php?hapus=<?php echo $menu['id']; ?>" class="btn btn-danger btn-sm rounded-3 px-3 fw-bold" onclick="return confirm('Hapus menu <?php echo $menu['nama_menu']; ?>?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>

</body>
</html>