<?php
include 'koneksi.php';
session_start();

// Gembok keamanan session login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil data berdasarkan kategori database
$ambil_makanan = mysqli_query($konek, "SELECT * FROM menu WHERE kategori = 'makanan'");
$ambil_minuman = mysqli_query($konek, "SELECT * FROM menu WHERE kategori = 'minuman'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RM Lamak Bana - Pesan Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .keranjang-box {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 1030;
            background-color: white; box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
            border-top-left-radius: 20px; border-top-right-radius: 20px; display: none;
        }
        .foto-menu {
            width: 80px; height: 80px; object-fit: cover; border-radius: 12px;
        }
        .judul-kategori {
            position: relative; padding-left: 15px;
        }
        .judul-kategori::before {
            content: ''; position: absolute; left: 0; top: 2px; bottom: 2px;
            width: 5px; background-color: #dc3545; border-radius: 3px;
        }
    </style>
</head>
<body class="bg-light" style="padding-bottom: 180px;">

    <nav class="navbar navbar-dark bg-danger sticky-top shadow-sm">
        <div class="container-fluid">
            <div class="d-flex flex-column">
                <span class="navbar-brand mb-0 h1 fw-bold pb-0">🍛 RM Lamak Bana</span>
                <small class="text-white" style="font-size: 11px;">Halo, <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Pelanggan'; ?> 👋</small>
            </div>
            <div class="d-flex align-items-center">
                <a href="riwayat_saya.php" class="btn btn-warning btn-sm rounded-pill px-3 me-2 fw-bold text-dark" style="font-size: 12px;">📜 Riwayat Belanja</a>
                
                <?php if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin') { ?>
                    <a href="admin.php" class="btn btn-outline-light btn-sm rounded-pill px-3 me-2" style="font-size: 12px;">Menu Admin</a>
                <?php } ?>
                
                <a href="ganti_password.php" class="btn btn-outline-light btn-sm rounded-pill px-3 me-2" style="font-size: 12px;">🔒 Ganti Password</a>

                <a href="logout.php" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold" style="font-size: 12px;" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container pt-4">
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                    <span class="input-group-text bg-white border-0 ps-3">🔍</span>
                    <input type="text" id="cariMenu" class="form-control border-0 py-2" placeholder="Cari makanan atau minuman kesukaanmu...">
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        
        <h5 class="fw-bold mb-3 judul-kategori text-dark">🍖 Menu Makanan Spesial</h5>
        <div class="row g-3 mb-5">
            <?php 
            if (mysqli_num_rows($ambil_makanan) == 0) {
                echo "<div class='col-12 text-muted small ps-3'>Belum ada menu makanan tersedia.</div>";
            }
            while($menu = mysqli_fetch_assoc($ambil_makanan)) { 
            ?>
            <div class="col-12 item-menu">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <img src="<?php echo $menu['foto']; ?>" class="foto-menu me-3" onerror="this.src='https://placehold.co/80x80?text=Makanan'">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1 nama-menu"><?php echo $menu['nama_menu']; ?></h6>
                            <p class="text-muted small mb-2"><?php echo $menu['deskripsi']; ?></p>
                            <span class="badge bg-success">Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></span>
                        </div>
                        <button class="btn btn-danger btn-sm rounded-pill px-3 ms-2 btn-tambah" 
                                data-nama="<?php echo htmlspecialchars($menu['nama_menu'], ENT_QUOTES, 'UTF-8'); ?>" 
                                data-harga="<?php echo $menu['harga']; ?>">
                            + Tambah
                        </button>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <h5 class="fw-bold mb-3 judul-kategori text-dark">🍹 Minuman Segar</h5>
        <div class="row g-3">
            <?php 
            if (mysqli_num_rows($ambil_minuman) == 0) {
                echo "<div class='col-12 text-muted small ps-3'>Belum ada menu minuman tersedia.</div>";
            }
            while($menu = mysqli_fetch_assoc($ambil_minuman)) { 
            ?>
            <div class="col-12 item-menu">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <img src="<?php echo $menu['foto']; ?>" class="foto-menu me-3" onerror="this.src='https://placehold.co/80x80?text=Minuman'">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1 nama-menu"><?php echo $menu['nama_menu']; ?></h6>
                            <p class="text-muted small mb-2"><?php echo $menu['deskripsi']; ?></p>
                            <span class="badge bg-success">Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></span>
                        </div>
                        <button class="btn btn-danger btn-sm rounded-pill px-3 ms-2 btn-tambah" 
                                data-nama="<?php echo htmlspecialchars($menu['nama_menu'], ENT_QUOTES, 'UTF-8'); ?>" 
                                data-harga="<?php echo $menu['harga']; ?>">
                            + Tambah
                        </button>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

    </div>

    <div id="kotakKeranjang" class="keranjang-box p-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0 text-dark">🛒 Keranjang Belanja</h6>
                <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none" onclick="kosongkanKeranjang()">Kosongkan</button>
            </div>
            <div id="daftarPesanan" class="small text-muted mb-3" style="max-height: 80px; overflow-y: auto;"></div>
            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                <div>
                    <span class="text-muted small d-block">Total Pembayaran</span>
                    <span class="fw-bold text-danger h5" id="totalHargaTeks">Rp 0</span>
                </div>
                <button class="btn btn-success fw-bold rounded-pill px-4" onclick="kirimKeWhatsApp()">Pesan via WA</button>
            </div>
        </div>
    </div>

    <script>
        let keranjang = [];

        // SCRIPT FITUR LIVE SEARCH
        document.getElementById('cariMenu').addEventListener('keyup', function() {
            let kataKunci = this.value.toLowerCase();
            let semuaMenu = document.querySelectorAll('.item-menu');

            semuaMenu.forEach(function(item) {
                let namaMenu = item.querySelector('.nama-menu').textContent.toLowerCase();
                
                if (namaMenu.indexOf(kataKunci) > -1) {
                    item.style.setProperty('display', '', 'important');
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });
        });

        // Menggunakan Event Listener massal agar tidak merusak inline HTML
        document.querySelectorAll('.btn-tambah').forEach(button => {
            button.addEventListener('click', function() {
                let nama = this.getAttribute('data-nama');
                let harga = parseInt(this.getAttribute('data-harga')) || 0;
                
                tambahKeKeranjang(nama, harga);
            });
        });

        function tambahKeKeranjang(nama, harga) {
            let itemAda = keranjang.find(item => item.nama === nama);
            if (itemAda) { 
                itemAda.jumlah += 1; 
            } else { 
                images:  keranjang.push({ nama: nama, harga: harga, jumlah: 1 }); 
            }
            perbaruiTampilanKeranjang();
        }

        function perbaruiTampilanKeranjang() {
            let kotak = document.getElementById('kotakKeranjang');
            let daftar = document.getElementById('daftarPesanan');
            let totalTeks = document.getElementById('totalHargaTeks');
            
            if (keranjang.length === 0) { 
                kotak.style.display = 'none'; 
                return; 
            }
            
            kotak.style.display = 'block'; 
            daftar.innerHTML = '';
            let totalHarga = 0;
            
            keranjang.forEach(item => {
                let subTotal = item.harga * item.jumlah;
                totalHarga += subTotal;
                daftar.innerHTML += `
                    <div class="d-flex justify-content-between my-1">
                        <span>${item.nama} <strong>(x${item.jumlah})</strong></span>
                        <span>Rp ${subTotal.toLocaleString('id-ID')}</span>
                    </div>`;
            });
            totalTeks.innerHTML = "Rp " + totalHarga.toLocaleString('id-ID');
        }

        function kosongkanKeranjang() { 
            keranjang = []; 
            perbaruiTampilanKeranjang(); 
        }

        function kirimKeWhatsApp() {
            if (keranjang.length === 0) return;

            fetch('simpan_pesanan.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ keranjang: keranjang })
            })
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    let nomorWA = "6282287691909"; 
                    let teksPesan = "Halo RM Lamak Bana, saya *" + "<?php echo $_SESSION['nama']; ?>" + "* mau pesan:%0A";
                    let totalHarga = 0;
                    
                    keranjang.forEach(item => {
                        let subTotal = item.harga * item.jumlah;
                        totalHarga += subTotal;
                        teksPesan += `- ${item.nama} x${item.jumlah} (Rp ${subTotal.toLocaleString('id-ID')})%0A`;
                    });
                    
                    teksPesan += `%0A*Total Akhir: Rp ${totalHarga.toLocaleString('id-ID')}*%0A%0A`;
                    let linkWhatsApp = "https://api.whatsapp.com/send?phone=" + nomorWA + "&text=" + teksPesan;
                    
                    kosongkanKeranjang();
                    window.open(linkWhatsApp, '_blank');
                } else {
                    alert('Gagal menyimpan riwayat pesanan: ' + res.pesan);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan koneksi server.');
            });
        }
    </script>
</body>
</html>