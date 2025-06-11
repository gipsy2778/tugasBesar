<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil isi keranjang
$keranjang = mysqli_query($conn, "
    SELECT k.*, p.harga, p.diskon 
    FROM keranjang k 
    JOIN produk p ON k.produk_id = p.id 
    WHERE k.user_id = $user_id
");

if (mysqli_num_rows($keranjang) == 0) {
    echo "<script>alert('Keranjang kosong!'); window.location='keranjang.php';</script>";
    exit;
}

// Hitung total
$total = 0;
$items = [];
while ($row = mysqli_fetch_assoc($keranjang)) {
    $harga_diskon = $row['harga'] - ($row['harga'] * $row['diskon'] / 100);
    $subtotal = $harga_diskon * $row['jumlah'];
    $total += $subtotal;
    $items[] = [
        'produk_id' => $row['produk_id'],
        'jumlah' => $row['jumlah'],
        'harga' => $harga_diskon
    ];
}

// Simpan ke tabel pesanan
mysqli_query($conn, "INSERT INTO pesanan (user_id, total) VALUES ($user_id, $total)");
$pesanan_id = mysqli_insert_id($conn);

// Simpan ke tabel pesanan_detail
foreach ($items as $item) {
    $p_id = $item['produk_id'];
    $jml = $item['jumlah'];
    $harga = $item['harga'];
    mysqli_query($conn, "INSERT INTO pesanan_detail (pesanan_id, produk_id, jumlah, harga) 
                         VALUES ($pesanan_id, $p_id, $jml, $harga)");
}

// Kosongkan keranjang
mysqli_query($conn, "DELETE FROM keranjang WHERE user_id = $user_id");

// Tampilkan konfirmasi
echo "<script>alert('Pesanan berhasil dibuat!'); window.location='riwayat.php';</script>";
exit;
?>
