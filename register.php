<?php
$konek_DB = new mysqli("localhost", "root", "", "fp_kasir");

if ($konek_DB->connect_error) {
    die("Koneksi ke database gagal: " . $konek_DB->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['buttonregister'])) {
    $username_db = $_POST['username'];
    $password_db = $_POST['password'];
    $confirm_pw = $_POST['confirm_password'];

    // Validasi password
    if ($password_db !== $confirm_pw) {
        echo "
                <script>
                alert('Password != Confirm Password, Ulangi')
                window.location.href='register.php';
                </script>
                ";
        die("Password dan Confirm Password tidak cocok.");
    }
  
    $q1 = "INSERT INTO db_karyawan (Username, Password, Confirm_password) VALUES ('$username_db', '$password_db', '$confirm_pw')";

    if ($konek_DB->query($q1)) {
        echo "
        <script>
        alert('Anda Berhasil Registrasi, silahkan login...')
        window.location.href='login.php';
        </script>
        ";
        exit;
    } else {
        die("Error: " . $konek_DB->error);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Keania+One&family=Knewave&family=Luckiest+Guy&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="style/register.css"> -->
     <link rel="stylesheet" href="style/style.css">
    <title>Register</title>
</head>
<body>
    <section class="register">
        <div class="judul">
            <h1>Selamat Datang</h1>
            <h1>Silahkan Login/Register</h1>
        </div>

        <div class="register-container">
            <form action="" method="post">
                <div class="form-register">
                    <h1 for="username">Username</h1>
                    <input type="text" id="username" name="username" value="" placeholder="Masukkan Username....." required>
                </div>
                <div class="form-register">
                    <h1 for="password">Password</h1>
                    <input type="password" id="password" name="password" value="" placeholder="Masukkan Password....." required>
                </div>
                <div class="form-register">
                    <h1 for="confirmPassword">Confirm Password</h1>
                    <input type="password" id="confirm_password" name="confirm_password" value="" placeholder="Confirm Password....." required>
                </div>
                <button type="submit" name="buttonregister" class="button-reg">Register</button>
            </form>
        </div>

        <div class="button-register">
            <a href="login.php">Login</a>
        </div>
    </section>
</body>
</html>