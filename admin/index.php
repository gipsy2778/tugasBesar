<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

// Ambil data ringkasan
$jumlah_produk = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM produk"));
$jumlah_pesanan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pesanan"));
$pesanan_baru = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pesanan WHERE status = 'Menunggu Konfirmasi'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/style_admin.css">
</head>
<body>
    <div class="judul">
        <h2>Dashboard Admin</h2>
    </div>

    <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

    <div class="dashboard-box">
        <div class="box">
            <h3><?php echo $jumlah_produk; ?></h3>
            <p>Jumlah Produk</p>
        </div>
        <div class="box">
            <h3><?php echo $jumlah_pesanan; ?></h3>
            <p>Total Pesanan</p>
        </div>
        <div class="box">
            <h3><?php echo $pesanan_baru; ?></h3>
            <p>Pesanan Baru</p>
        </div>
    </div>

    <nav>
        <a href="produk.php">Kelola Produk</a> |
        <a href="pesanan.php">Kelola Pesanan</a> |
        <a href="../logout.php">Logout</a>
    </nav>
</body>
</html>
