<?php
session_start();
include 'koneksi.php';

$loggedIn = false;
$username = '';
$cart_items = [];
$total = 0;

if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
    $user_id = $_SESSION['user_id'];

    // Ambil username
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM user WHERE id = $user_id"));
    $username = $user['username'];

    // Jika ada cart di cookie, pindahkan ke DB
    if (isset($_COOKIE['cart'])) {
        $cookie_cart = json_decode($_COOKIE['cart'], true);
        foreach ($cookie_cart as $produk_id => $jumlah) {
            $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id = $user_id AND produk_id = $produk_id");
            if (mysqli_num_rows($cek) > 0) {
                mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + $jumlah WHERE user_id = $user_id AND produk_id = $produk_id");
            } else {
                mysqli_query($conn, "INSERT INTO keranjang (user_id, produk_id, jumlah) VALUES ($user_id, $produk_id, $jumlah)");
            }
        }
        setcookie('cart', '', time() - 3600, '/'); // hapus cookie
    }

    // Ambil data dari database
    $result = mysqli_query($conn, "
        SELECT k.*, p.nama, p.harga, p.diskon, p.gambar 
        FROM keranjang k 
        JOIN produk p ON k.produk_id = p.id 
        WHERE k.user_id = $user_id
    ");
    while ($item = mysqli_fetch_assoc($result)) {
        $cart_items[] = $item;
    }

} else {
    // Jika belum login, ambil dari cookie
    if (isset($_COOKIE['cart'])) {
        $cookie_cart = json_decode($_COOKIE['cart'], true);
        foreach ($cookie_cart as $produk_id => $jumlah) {
            $produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id = $produk_id"));
            if ($produk) {
                $produk['jumlah'] = $jumlah;
                $cart_items[] = $produk;
            }
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
    <?php if (empty($cart_items)): ?>
        <p>Keranjang kamu kosong.</p>
    <?php else: ?>
    <table>
        <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($cart_items as $item): 
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
            <td>
                <a href="keranjang_hapus.php?id=<?= $item['id'] ?? $item['produk_id']; ?>" onclick="return confirm('Hapus item ini?')">Hapus</a>
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
