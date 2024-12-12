<?php
    $konek_DB = new mysqli("localhost", "root", "", "fp_kasir");

    if ($konek_DB->connect_error) {
        die("Koneksi gagal: " . $konek_DB->connect_error);
    }

    // Cek apakah form disubmit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil data dari form
        $nama_menu = $_POST['nama_menu'];
        $harga_menu = $_POST['harga_menu'];
        $jumlah = $_POST['jumlah'];

        // Query untuk memasukkan data pesanan
        $query = "INSERT INTO pesanan (nama_menu, harga_menu, jumlah) VALUES ('$nama_menu', '$harga_menu', '$jumlah')";

        if ($konek_DB->query($query) === TRUE) {
            echo "<script>
                    alert('Berhasil memasukkan " . htmlspecialchars($nama_menu) . "');
                    window.location.href = 'dashboard.php';
                  </script>";
        } else {
            echo "Error: " . $query . "<br>" . $konek_DB->error;
        }

        // Tutup koneksi
        $konek_DB->close();
    }
?>
