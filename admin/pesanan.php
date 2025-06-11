<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

$pesanan = mysqli_query($conn, "SELECT p.*, u.username FROM pesanan p JOIN user u ON p.user_id = u.id ORDER BY p.tanggal DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pesanan</title>
    <link rel="stylesheet" href="../assets/css/style_admin.css">  
</head>
<body>
<div class="kelola-pesanan-container">
    <h2>Kelola Pesanan</h2>
    <a href="index.php" class="back-link">← Kembali ke Dashboard</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($pesanan)) : ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['username']; ?></td>
                <td><?= $row['tanggal']; ?></td>
                <td>Rp<?= number_format($row['total'], 0, ',', '.'); ?></td>
                <td><?= $row['status']; ?></td>
                <td class="action-link">
                    <?php
                        if ($row['status'] == 'Menunggu Konfirmasi') {
                            echo '<a href="pesanan_proses.php?id=' . $row['id'] . '&aksi=konfirmasi">Konfirmasi</a> | ';
                            echo '<a href="pesanan_proses.php?id=' . $row['id'] . '&aksi=tolak" onclick="return confirm(\'Yakin tolak pesanan ini?\')">Tolak</a>';
                        } elseif ($row['status'] == 'proses') {
                            echo '<a href="pesanan_proses.php?id=' . $row['id'] . '&aksi=dikirim">Kirim</a>';
                        } elseif ($row['status'] == 'dikirim') {
                            echo '<a href="pesanan_proses.php?id=' . $row['id'] . '&aksi=selesai">Selesai</a>';
                        } else {
                            echo '-';
                        }
                    ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
