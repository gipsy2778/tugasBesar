<?php
// Ambil data dari cookie
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

// Ambil ID produk yang akan dihapus
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Hapus item jika ada
if ($id && isset($cart[$id])) {
    unset($cart[$id]);

    // Simpan kembali ke cookie (1 hari)
    setcookie('cart', json_encode($cart), time() + (86400), '/');
}

// Kembali ke keranjang
header("Location: keranjang.php");
exit;
?>
