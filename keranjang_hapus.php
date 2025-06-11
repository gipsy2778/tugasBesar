<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Hapus item keranjang milik user
mysqli_query($conn, "DELETE FROM keranjang WHERE id = $id AND user_id = $user_id");

header("Location: keranjang.php");
exit;
