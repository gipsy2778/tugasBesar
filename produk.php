<?php
session_start();
include 'koneksi.php';

$loggedIn = false;
$username = '';

if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
}

// Ambil ID produk dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data produk dari database
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk - <?= $produk['nama']; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Header -->
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

<!-- Detail Produk -->
<section class="detail-produk">
    <div class="container">
        <div class="produk-detail">
            <img src="assets/img/<?= $produk['gambar']; ?>" alt="<?= $produk['nama']; ?>">
            <div class="info">
                <h2><?= $produk['nama']; ?></h2>
                <p class="harga">
                    <?php if ($produk['diskon'] > 0): ?>
                        <del>Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></del><br>
                        <strong>
                            Rp <?= number_format($produk['harga'] - ($produk['harga'] * $produk['diskon'] / 100), 0, ',', '.'); ?>
                        </strong>
                    <?php else: ?>
                        <strong>Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></strong>
                    <?php endif; ?>
                </p>
                <p>Stok: <?= $produk['stok']; ?> pcs</p>
                <p><?= nl2br($produk['deskripsi']); ?></p>
                
                <form action="keranjang_tambah.php" method="post">
                    <input type="hidden" name="produk_id" value="<?= $produk['id']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" max="<?= $produk['stok']; ?>">
                    <button type="submit" class="btn">+ Tambah ke Keranjang</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2025 onShop. All rights reserved.</p>
</footer>
</body>
</html>
