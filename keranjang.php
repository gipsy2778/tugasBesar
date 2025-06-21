<?php
session_start();
include 'koneksi.php';

$loggedIn = false;
$username = '';

if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($conn, "SELECT username FROM user WHERE id = $user_id");
    $data = mysqli_fetch_assoc($result);
    $username = $data['username'];
}

// Ambil isi keranjang dari cookie
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

$items = [];
$total = 0;

if (!empty($cart)) {
    foreach ($cart as $produk_id => $jumlah) {
        $result = mysqli_query($conn, "SELECT * FROM produk WHERE id = $produk_id");
        if ($produk = mysqli_fetch_assoc($result)) {
            $harga_diskon = $produk['harga'] - ($produk['harga'] * $produk['diskon'] / 100);
            $subtotal = $harga_diskon * $jumlah;
            $produk['jumlah'] = $jumlah;
            $produk['harga_diskon'] = $harga_diskon;
            $produk['subtotal'] = $subtotal;
            $items[] = $produk;
            $total += $subtotal;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
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

    <!-- Keranjang -->
    <section class="keranjang">
        <h2>Keranjang Belanja</h2>

        <?php if (empty($items)): ?>
            <p>Keranjang masih kosong.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <img src="assets/img/<?= $item['gambar']; ?>" width="50">
                            <?= htmlspecialchars($item['nama']); ?>
                        </td>
                        <td>Rp <?= number_format($item['harga_diskon'], 0, ',', '.'); ?></td>
                        <td><?= $item['jumlah']; ?></td>
                        <td>Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="keranjang_hapus.php?id=<?= $item['id']; ?>" onclick="return confirm('Hapus item ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td colspan="2"><strong>Rp <?= number_format($total, 0, ',', '.'); ?></strong></td>
                </tr>
            </table>
            <br>
            <?php if ($loggedIn): ?>
                <a href="checkout.php" class="btn-acc">Checkout Sekarang</a>
            <?php else: ?>
                <a href="login.php" class="btn-acc">Login untuk Checkout</a>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</body>
</html>
