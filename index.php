<?php
session_start();
include 'koneksi.php';

$loggedIn = false;
$username = '';

if (isset($_SESSION['user_id'])) {
    $loggedIn = true;

    // Ambil nama user dari database
    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($conn, "SELECT username FROM user WHERE id = $user_id");
    $data = mysqli_fetch_assoc($result);
    $username = $data['username'];
}
?>

<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>onShop - Toko Online Pakaian</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header / Navbar -->
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

    <!-- Hero / Banner -->
    <section class="hero">
        <?php if ($loggedIn): ?>
        <span class="welcome">Halo, <?= htmlspecialchars($username) ?>!</span>
        <?php endif; ?>
        <div class="hero-text">
            <h1>Selamat Datang di onShop</h1>
            <p>Toko online pakaian dengan berbagai pilihan gaya kekinian.</p>
            <a href="katalog.php" class="btn">Belanja Sekarang</a>
        </div>
    </section>

    <!-- Produk Unggulan -->
    <section class="unggulan">
        <br>
        <div class="judul">
            <h2>Produk Unggulan</h2>
        </div>
        <div class="produk-grid">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM produk");
            while ($row = mysqli_fetch_assoc($query)) :
            ?>
                <a href="produk.php?id=<?= $row['id']; ?>" class="produk-card-link">
                    <div class="produk-card">
                        <img src="assets/img/<?= $row['gambar']; ?>" alt="<?= $row['nama']; ?>">
                        <h3><?= $row['nama']; ?></h3>
                        <p>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 onShop. All rights reserved.</p>
    </footer>
</body>
</html>
