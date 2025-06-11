<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

// Ambil data produk
$produk = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Produk - Admin</title>
    <link rel="stylesheet" href="../assets/css/style_admin.css">
</head>
<body>
    <h2>Kelola Produk</h2>
    <p><a href="index.php">← Kembali ke Dashboard</a></p>
    <div class="btnt">
        <a href="produk_tambah.php" class="btn">+ Tambah Produk</a>
    </div>

    <table border="1" cellpadding="10" cellspacing="0" style="margin: auto; background: #fff;">
        <tr style="background-color: #f0f0f0;">
            <th>No</th>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($produk)) {
            echo "<tr>";
            echo "<td>{$no}</td>";
            echo "<td><img src='../assets/img/{$row['gambar']}' width='60'></td>";
            echo "<td>{$row['nama']}</td>";
            echo "<td>Rp " . number_format($row['harga']) . "</td>";
            echo "<td>{$row['kategori']}</td>";
            echo "<td>
                <a href='produk_edit.php?id={$row['id']}'>Edit</a> |
                <a href='produk_hapus.php?id={$row['id']}' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
            </td>";
            echo "</tr>";
            $no++;
        }
        ?>
    </table>
</body>
</html>
