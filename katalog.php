<?php
session_start();
include 'koneksi.php';

$kategori_list = ['Baju', 'Celana', 'Sepatu', 'Topi', 'Tas', 'Aksesoris', 'Lainnya'];
$kategori_data = [];

$loggedIn = false;
$username = '';

if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
}


// Ambil satu produk dari setiap kategori
$query = "SELECT * FROM produk GROUP BY kategori";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $kategori_data[$row['kategori']] = $row['gambar'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Produk</title>
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

    <div class="container">
        <br>
        <div class="judul">
            <h1>Katalog Produk</h1>
        </div>
        <br><br>
        <div class="katalog-grid top-row">
            <?php for ($i = 0; $i < 4; $i++): $kategori = $kategori_list[$i]; ?>
                <div class="kategori-card">
                    <a href="list_produk.php?kategori=<?= urlencode($kategori) ?>">
                        <?php if (isset($kategori_data[$kategori])): ?>
                            <img src="assets/img/<?= $kategori_data[$kategori] ?>" alt="<?= $kategori ?>">
                        <?php else: ?>
                            <img src="assets/img/default-<?= strtolower($kategori) ?>.jpg" alt="<?= $kategori ?>">
                        <?php endif; ?>
                        <h2><?= htmlspecialchars($kategori) ?></h2>
                    </a>
                </div>
            <?php endfor; ?>
        </div>

        <div class="katalog-grid bottom-row">
            <?php for ($i = 4; $i < count($kategori_list); $i++): $kategori = $kategori_list[$i]; ?>
                <div class="kategori-card">
                    <a href="list_produk.php?kategori=<?= urlencode($kategori) ?>">
                        <?php if (isset($kategori_data[$kategori])): ?>
                            <img src="assets/img/<?= $kategori_data[$kategori] ?>" alt="<?= $kategori ?>">
                        <?php else: ?>
                            <img src="assets/img/default-<?= strtolower($kategori) ?>.jpg" alt="<?= $kategori ?>">
                        <?php endif; ?>
                        <h2><?= htmlspecialchars($kategori) ?></h2>
                    </a>
                </div>
            <?php endfor; ?>
        </div>
    </div>

</body>
</html>
