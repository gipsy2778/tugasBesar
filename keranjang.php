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
$query = mysqli_query($conn, "
    SELECT k.*, p.nama, p.harga, p.diskon, p.gambar 
    FROM keranjang k 
    JOIN produk p ON k.produk_id = p.id 
    WHERE k.user_id = $user_id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
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

    <section class="keranjang">
        <h2>Keranjang Belanja</h2>
        <table>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
            <?php
            $total = 0;
            while ($item = mysqli_fetch_assoc($query)):
                $harga_diskon = $item['harga'] - ($item['harga'] * $item['diskon'] / 100);
                $subtotal = $harga_diskon * $item['jumlah'];
                $total += $subtotal;
            ?>
            <tr>
                <td>
                    <img src="assets/img/<?= $item['gambar']; ?>" width="50"> 
                    <?= $item['nama']; ?>
                </td>
                <td>Rp <?= number_format($harga_diskon, 0, ',', '.'); ?></td>
                <td><?= $item['jumlah']; ?></td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
                <td><a href="keranjang_hapus.php?id=<?= $item['id']; ?>" onclick="return confirm('Hapus item ini?')">Hapus</a></td>
            </tr>
            <?php endwhile; ?>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td colspan="2"><strong>Rp <?= number_format($total, 0, ',', '.'); ?></strong></td>
            </tr>
        </table>
        <br>
        <a href="checkout.php" class="btn-acc">Checkout Sekarang</a>
    </section>
</body>
</html>
