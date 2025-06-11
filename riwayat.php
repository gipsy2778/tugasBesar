<?php
session_start();
include 'koneksi.php';

$loggedIn = false;
$username = '';

if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil daftar pesanan
$pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE user_id = $user_id ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pesanan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <div class="navbar">
        <div class="logo">onShop</div>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="katalog.php">Katalog</a>
            <a href="keranjang.php">Keranjang</a>
            <?php if ($loggedIn): ?>
                <a href="riwayat.php">Riwayat</a>
                <a href="logout.php" class="btn">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<div class="riwayat">
    <h2>Riwayat Pesanan Saya</h2>

    <?php if (mysqli_num_rows($pesanan) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($pesanan)): ?>
            <div class="pesanan-box">
                <p><strong>Tanggal:</strong> <?= $row['tanggal'] ?></p>
                <p><strong>Total:</strong> Rp<?= number_format($row['total'], 0, ',', '.') ?></p>
                <p><strong>Detail:</strong></p>
                <ul>
                <?php
                    $id_pesanan = $row['id'];
                    $detail = mysqli_query($conn, "
                        SELECT pd.jumlah, pd.harga, pr.nama 
                        FROM pesanan_detail pd 
                        JOIN produk pr ON pd.produk_id = pr.id 
                        WHERE pd.pesanan_id = $id_pesanan
                    ");
                    while($d = mysqli_fetch_assoc($detail)) {
                        echo "<li>{$d['nama']} ({$d['jumlah']}x) - Rp" . number_format($d['harga'], 0, ',', '.') . "</li>";
                    }
                ?>
                </ul>
                <p><strong>Status:</strong> 
                    <span class="status <?= $row['status'] ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Belum ada pesanan.</p>
    <?php endif; ?>
</div>
</body>
</html>
