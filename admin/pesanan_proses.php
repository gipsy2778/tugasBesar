<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

if (isset($_GET['id']) && isset($_GET['aksi'])) {
    $id = intval($_GET['id']);
    $aksi = $_GET['aksi'];

    // Validasi status yang diizinkan
    $allowed_status = ['proses', 'dikirim', 'selesai'];
    if (in_array($aksi, $allowed_status)) {
        $query = mysqli_query($conn, "UPDATE pesanan SET status='$aksi' WHERE id=$id");
        if ($query) {
            header("Location: pesanan.php?pesan=sukses");
            exit;
        } else {
            header("Location: pesanan.php?pesan=gagal");
            exit;
        }
    } else {
        header("Location: pesanan.php?pesan=status_tidak_valid");
        exit;
    }
} else {
    header("Location: pesanan.php?pesan=parameter_tidak_lengkap");
    exit;
}
?>
