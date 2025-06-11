<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];

    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = "../assets/img/produk";

    if ($gambar != "") {
        move_uploaded_file($tmp, $folder . $gambar);
    } else {
        $gambar = "default.jpg"; // jika tidak upload gambar
    }

    $query = "INSERT INTO produk (nama, harga, kategori, deskripsi, gambar) 
              VALUES ('$nama', '$harga', '$kategori', '$deskripsi', '$gambar')";
    mysqli_query($conn, $query);

    header("Location: produk.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="../assets/css/style_admin.css">
</head>
<body>
    <h2>Tambah Produk Baru</h2>
    <p><a href="produk.php">← Kembali</a></p>

    <form method="post" enctype="multipart/form-data" style="width: 400px; margin:auto;">
        <label>Nama Produk:</label><br>
        <input type="text" name="nama" required><br><br>

        <label>Harga:</label><br>
        <input type="number" name="harga" required><br><br>

        <label>Kategori:</label><br>
        <select name="kategori" required>
            <option value="">--Pilih--</option>
            <option value="Baju">Baju</option>
            <option value="Celana">Celana</option>
            <option value="Sepatu">Sepatu</option>
            <option value="Topi">Topi</option>
            <option value="Tas">Tas</option>
            <option value="Aksesoris">Aksesoris</option>
            <option value="Lainnya">Lainnya</option>
        </select><br><br>

        <label>Stok:</label><br>
        <input type="number" name="stok" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi" rows="4" required></textarea><br><br>

        <label>Gambar Produk:</label><br>
        <input type="file" name="gambar"><br><br>

        <button type="submit" name="submit">Simpan</button>
    </form>
</body>
</html>
