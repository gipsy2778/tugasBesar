<?php
session_start();
include 'koneksi.php';

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$produk_id = (int)$_POST['produk_id'];
$jumlah = (int)$_POST['jumlah'];

// Cek apakah produk sudah ada di keranjang user
$cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id=$user_id AND produk_id=$produk_id");
if (mysqli_num_rows($cek) > 0) {
    // Jika sudah ada, update jumlah
    mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + $jumlah WHERE user_id=$user_id AND produk_id=$produk_id");
} else {
    // Jika belum, insert baru
    mysqli_query($conn, "INSERT INTO keranjang (user_id, produk_id, jumlah) VALUES ($user_id, $produk_id, $jumlah)");
}

header("Location: keranjang.php");
exit;
