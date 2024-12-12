<?php
// Koneksi ke database
$db_koneksi = new mysqli("localhost", "root", "", "fp_kasir");
if ($db_koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $db_koneksi->connect_error);
}

session_start();
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari username
    $query = "SELECT * FROM db_karyawan WHERE Username = ?";
    $stmt = $db_koneksi->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah username ditemukan
    if ($result->num_rows >= 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if ($password === $user['Password']) {
            // Login berhasil
            $_SESSION['username'] = $username;  // Simpan username ke session
            // Tampilkan alert dan redirect ke dashboard
            echo "<script>
                    alert('Login berhasil! Selamat datang, " . htmlspecialchars($username) . "');
                    window.location.href = 'dashboard.php';
                  </script>";
            exit();
        } else {
            $_SESSION['msg'] = "Password salah! Coba lagi, sayang...";
        }
    } else {
        $_SESSION['msg'] = "Akun Anda tidak terdaftar, sayang...";
    }

    $stmt->close();
    // Redirect kembali ke login.php jika ada error
    header("Location: login.php");
    exit();
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
    <link rel="stylesheet" href="style/login.css">
    <title>Document</title>
</head>
<body>
    <section class="login">
            <div class="judul">
                <h1>Selamat Datang</h1>
                <h1>Silahkan Login/Register</h1>
            </div>

            <div class="Login-container">
                <form action="" method="post">
                    <div class="form-login">
                        <h1 for="username">Username</h1>
                        <input type="text" id="username" name="username" value="" placeholder="Masukkan Username....." required>
                    </div>
                    <div class="form-login">
                        <h1 for="password">Password</h1>
                        <input type="password" id="password" name="password" value="" placeholder="Masukkan Password....." required>
                    </div>
                    <div class="button-login">
                        <button type="submit" name="buttonlogin" class="button-reg">Login</button>
                        <div class="register-Log">
                            <a href="register.php">Register</a>
                            <p>Belum punya akun?</p>
                        </div>
                    </div>
                </form>
            </div>            
    </section>

    <?php
    if (!empty($_SESSION['msg'])):
    ?>
    <script>
        alert("<?php echo $_SESSION['msg']; ?>");
    </script>
    <?php 
        unset($_SESSION['msg']); 
    endif; 
    ?>

</body>
</html>