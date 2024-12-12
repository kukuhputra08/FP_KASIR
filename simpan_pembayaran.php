<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $totalBelanja = $_POST['total_belanja'];
    $uangMasuk = $_POST['uang_pembayaran'];
    $kembalian = $_POST['kembalian'];

    // Koneksi ke database
    $konek_DB = new mysqli("localhost", "root", "", "fp_kasir");

    if ($konek_DB->connect_error) {
        die("Koneksi gagal: " . $konek_DB->connect_error);
    }

    // Query untuk menyimpan pembayaran
    $insertQuery = "INSERT INTO jumlah_pesanan (total_belanja, uang_pembayaran, kembalian) 
                    VALUES (?, ?, ?)";
    $stmt = $konek_DB->prepare($insertQuery);
    $stmt->bind_param("iii", $totalBelanja, $uangMasuk, $kembalian);
    if ($stmt->execute()) {
        echo "<script>
                    alert('Pembayaran Berhasil " . htmlspecialchars($nama_menu) . "');
                    window.location.href = 'dashboard.php';
                </script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $konek_DB->close();
}
?>
