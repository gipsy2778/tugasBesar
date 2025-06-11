<?php
session_start();
include 'koneksi.php';

// Cek apakah parameter kategori ada
if (!isset($_GET['kategori'])) {
    header("Location: katalog.php");
    exit;
}

$kategori = mysqli_real_escape_string($conn, $_GET['kategori']);

$loggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';

// Ambil semua produk dari kategori tersebut
$query = "SELECT * FROM produk WHERE kategori = '$kategori'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk - <?= htmlspecialchars($kategori) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .produk-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .produk-card {
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            background-color: #fff;
            transition: transform 0.2s ease;
        }

        .produk-card:hover {
            transform: scale(1.03);
        }

        .produk-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .produk-card h2 {
            font-size: 16px;
            margin: 10px 0 5px;
        }

        .produk-card p {
            color: #333;
            margin-bottom: 10px;
        }
    </style>
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
    <h1 style="text-align:center; margin-top: 20px;"><?= htmlspecialchars($kategori) ?></h1>
    <div class="produk-grid">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="produk-card">
                    <a href="produk.php?id=<?= $row['id'] ?>">
                        <img src="assets/img/<?= $row['gambar'] ?>" alt="<?= $row['nama'] ?>">
                        <h2><?= htmlspecialchars($row['nama']) ?></h2>
                    </a>
                    <p>Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">Belum ada produk untuk kategori ini.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
