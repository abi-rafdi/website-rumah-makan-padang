<?php
session_start();
session_destroy(); // Hapus semua ingatan login di browser
header("Location: login.php"); // Kembalikan ke halaman login
exit;