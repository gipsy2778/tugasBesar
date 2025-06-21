<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Ambil user dari database
    $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Gabungkan keranjang dari cookie ke database
        if (isset($_COOKIE['cart'])) {
            $cart = json_decode($_COOKIE['cart'], true);
            foreach ($cart as $produk_id => $jumlah) {
                $produk_id = (int)$produk_id;
                $jumlah = (int)$jumlah;

                // Cek apakah produk sudah ada di keranjang user
                $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id = {$user['id']} AND produk_id = $produk_id");
                if (mysqli_num_rows($cek) > 0) {
                    mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + $jumlah WHERE user_id = {$user['id']} AND produk_id = $produk_id");
                } else {
                    mysqli_query($conn, "INSERT INTO keranjang (user_id, produk_id, jumlah) VALUES ({$user['id']}, $produk_id, $jumlah)");
                }
            }

            // Hapus cookie
            setcookie('cart', '', time() - 3600, '/');
        }

        // Redirect sesuai role
        if ($user['role'] == 'admin') {
            header("Location: admin/index.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - onShop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <section class="login">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>

        <a href="index.php" class="btn-back">← Kembali</a>
    </section>
</body>
</html>
