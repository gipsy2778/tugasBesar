<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produk_id = isset($_POST['produk_id']) ? (int)$_POST['produk_id'] : 0;
    $jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 1;

    // Validasi jumlah
    if ($jumlah < 1) $jumlah = 1;

    // Jika user sudah login, simpan ke database
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Cek apakah produk sudah ada di keranjang user
        $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id = $user_id AND produk_id = $produk_id");
        if (mysqli_num_rows($cek) > 0) {
            mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + $jumlah WHERE user_id = $user_id AND produk_id = $produk_id");
        } else {
            mysqli_query($conn, "INSERT INTO keranjang (user_id, produk_id, jumlah) VALUES ($user_id, $produk_id, $jumlah)");
        }
    } else {
        // Jika belum login, simpan ke cookie (keranjang guest)
        $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

        if (isset($cart[$produk_id])) {
            $cart[$produk_id] += $jumlah;
        } else {
            $cart[$produk_id] = $jumlah;
        }

        // Simpan kembali ke cookie (7 hari)
        setcookie('cart', json_encode($cart), time() + (7 * 24 * 60 * 60), '/');
    }

    // Redirect kembali ke keranjang
    header("Location: keranjang.php");
    exit;
} else {
    echo "Akses tidak valid.";
}
