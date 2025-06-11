<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan!";
    exit;
}

$id = $_GET['id'];

// Hapus gambar terlebih dahulu jika ada
$cek = mysqli_query($conn, "SELECT gambar FROM produk WHERE id=$id");
$data = mysqli_fetch_assoc($cek);

if ($data && !empty($data['gambar']) && file_exists("../assets/img/" . $data['gambar'])) {
    unlink("../assets/img/" . $data['gambar']);
}

// Hapus dari database
$hapus = mysqli_query($conn, "DELETE FROM produk WHERE id=$id");

if ($hapus) {
    header("Location: produk.php");
    exit;
} else {
    echo "Gagal menghapus produk.";
}
