<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    // Validasi
    if ($password1 != $password2) {
        $error = "Password tidak cocok!";
    } else {
        // Cek username
        $cek = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $hash = password_hash($password1, PASSWORD_DEFAULT);
            $query = mysqli_query($conn, "INSERT INTO user (username, password) VALUES ('$username', '$hash')");

            if ($query) {
                header("Location: login.php?msg=registered");
                exit;
            } else {
                $error = "Registrasi gagal, coba lagi!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - onShop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <section class="login">
        <h2>Daftar Akun</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password1" placeholder="Password" required><br>
            <input type="password" name="password2" placeholder="Konfirmasi Password" required><br>
            <button type="submit" name="register">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        
        <a href="index.php" class="btn-back">← Kembali</a>
    </section>
</body>
</html>
