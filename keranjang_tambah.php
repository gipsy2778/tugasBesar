<?php
session_start();

$produk_id = $_POST['produk_id'];
$jumlah = $_POST['jumlah'];

$cart = [];

if (isset($_COOKIE['cart'])) {
    $cart = json_decode($_COOKIE['cart'], true);
}

// Tambahkan atau update jumlah produk
if (isset($cart[$produk_id])) {
    $cart[$produk_id] += $jumlah;
} else {
    $cart[$produk_id] = $jumlah;
}

// Simpan kembali ke cookie selama 7 hari
setcookie('cart', json_encode($cart), time() + (86400 * 7), "/");

header("Location: keranjang.php");
exit;
?>