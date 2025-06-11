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
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    echo "Produk tidak ditemukan!";
    exit;
}

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $diskon = $_POST['diskon'];
    $deskripsi = $_POST['deskripsi'];

    // Cek jika ada gambar baru diupload
    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "../assets/img/" . $gambar);
    } else {
        $gambar = $produk['gambar']; // gunakan gambar lama jika tidak diubah
    }

    $update = mysqli_query($conn, "UPDATE produk SET 
        nama='$nama',
        kategori='$kategori',
        harga='$harga',
        stok='$stok',
        diskon='$diskon',
        deskripsi='$deskripsi',
        gambar='$gambar'
        WHERE id=$id");

    if ($update) {
        header("Location: produk.php");
        exit;
    } else {
        $error = "Gagal memperbarui produk.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <link rel="stylesheet" href="../assets/css/style_admin.css">
</head>
<body>
    <h2>Edit Produk</h2>

    <a href="produk.php">← Kembali</a>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Nama Produk</label>
        <input type="text" name="nama" value="<?= $produk['nama'] ?>" required>

        <label>Kategori</label>
        <select name="kategori" required>
            <?php
            $kategori_list = ['Baju', 'Celana', 'Sepatu', 'Topi', 'Tas', 'Aksesoris'];
            foreach ($kategori_list as $kat) {
                $selected = ($produk['kategori'] == $kat) ? "selected" : "";
                echo "<option value='$kat' $selected>$kat</option>";
            }
            ?>
        </select>

        <label>Harga</label>
        <input type="number" name="harga" value="<?= $produk['harga'] ?>" required>

        <label>Stok</label>
        <input type="number" name="stok" value="<?= $produk['stok'] ?>" required>

        <label>Diskon (%)</label>
        <input type="number" name="diskon" value="<?= $produk['diskon'] ?>">

        <label>Deskripsi</label>
        <textarea name="deskripsi"><?= $produk['deskripsi'] ?></textarea>

        <label>Gambar Produk (kosongkan jika tidak ingin mengubah)</label>
        <input type="file" name="gambar">

        <?php if ($produk['gambar']): ?>
            <p>Gambar saat ini: <img src="../assets/img/<?= $produk['gambar'] ?>" width="100"></p>
        <?php endif; ?>

        <button type="submit" name="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
