<?php
$host = "localhost";
$user = "root";
$pass = ""; // ganti jika password MySQL kamu tidak kosong
$db   = "onShop";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
