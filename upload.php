<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "fp_kasir");

session_start();
$msg="";
// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Periksa apakah form telah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];

    // Proses file yang diupload
    $targetDir = "assets/"; // Folder untuk menyimpan file
    $fileName = basename($_FILES["foto"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFilePath)) {
        // Simpan data ke database
        $sql = "INSERT INTO menu_makanan (nama, harga, gambar) VALUES ('$nama', '$harga', '$fileName')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Data BErhasil Disimpan, " . htmlspecialchars($username) . "');
                    window.location.href = 'dashboard.php';
                  </script>";
            exit();
           
        } else {
            $_SESSION['msg']="Data Gagal Disimpan";
        }
    } else {
        $_SESSION['msg']="Data Gagal Disimpan";
    }
}
$conn->close();
header("Location: login.php");
exit();
?>
