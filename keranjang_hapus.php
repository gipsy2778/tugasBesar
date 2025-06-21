<?php
session_start();
include 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: keranjang.php");
    exit;
}

// Jika user login, hapus dari database
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "DELETE FROM keranjang WHERE id = $id AND user_id = $user_id");
} else {
    // Jika belum login, hapus dari cookie
    if (isset($_COOKIE['cart'])) {
        $cart = json_decode($_COOKIE['cart'], true);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            setcookie('cart', json_encode($cart), time() + (86400 * 7), "/"); // perbarui cookie
        }
    }
}

header("Location: keranjang.php");
exit;
