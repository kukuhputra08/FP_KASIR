<?php
$konek_DB = new mysqli("localhost", "root", "", "fp_kasir");

if ($konek_DB->connect_error) {
    die("Koneksi gagal: " . $konek_DB->connect_error);
}

// Ambil data dari tabel pesanan
$result = mysqli_query($konek_DB, "SELECT * FROM pesanan");

if (!$result) {
    echo "Query gagal: " . mysqli_error($konek_DB);
}

// Handle form submission to update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    if ($action === 'increment') {
        $query = "UPDATE pesanan SET jumlah = jumlah + 1 WHERE id = ?";
    } elseif ($action === 'decrement') {
        $query = "UPDATE pesanan SET jumlah = GREATEST(jumlah - 1, 0) WHERE id = ?";
    }

    $stmt = $konek_DB->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    // Redirect to refresh the page after update
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle payment confirmation and display receipt
if (isset($_POST['total_belanja']) && isset($_POST['uang_pembayaran'])) {
    $totalBelanja = $_POST['total_belanja'];
    $uangMasuk = $_POST['uang_pembayaran'];
    $kembalian = $uangMasuk - $totalBelanja;

    // Insert into jumlah_pesanan table
    $insertQuery = "INSERT INTO jumlah_pesanan (total_belanja, uang_pembayaran, kembalian) 
                    VALUES (?, ?, ?)";
    $stmt = $konek_DB->prepare($insertQuery);
    $stmt->bind_param("iii", $totalBelanja, $uangMasuk, $kembalian);
    $stmt->execute();
    $stmt->close();

    // Get the last inserted ID
    $lastInsertedId = $konek_DB->insert_id;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap -->

    <link rel="stylesheet" href="style/payment.css">
</head>
<body>
    <nav class="navbar sticky-top">
        <div class="container d-flex justify-content-end">
          <a class="navbar-brand " href="#">
            <img class="snavcont" src="assets/person-circle.svg" alt="Bootstrap" width="40" height="34">
          </a>
        </div>
    </nav>

    <div class="d-flex flex-row justify-content-between">
        <div class="container-fluid startnav">
            <div class="d-flex justify-content-center">
            </div>
        </div>
        <div class="container-fluid startnav fixnav">
            <div class="d-flex justify-content-center">
                <div class="d-flex flex-column row-gap-5 mt-5">
                    <a class="icon-link" href="dashboard.php">
                        <img src="assets/bookmarks-fill.svg" alt="bookmark" class="snavcont">
                    </a>
                    <a class="icon-link" href="#">
                        <img src="assets/clock-history.svg" alt="bookmark" class="snavcont">
                    </a>
                    <a class="icon-link" href="payment.php">
                        <img src="assets/cart-fill.svg" alt="bookmark" class="snavcont">
                    </a>
                </div>
            </div>
        </div>

        <div class="flex-fill ms-5">
            <div class="d-flex align-items-center justify-content-center vh-100">
                <div class="d-flex flex-column align-items-center justify-content-between">
                    <!-- Menampilkan data pesanan -->
                    <?php if (mysqli_num_rows($result) > 0): $sumTot=0;?>
                        <?php while ($row = mysqli_fetch_assoc($result)): 
                                $totalHarga=$row['jumlah']*$row['harga_menu'];
                                $sumTot+=$totalHarga;
                            ?>
                            <div class="cards rounded-5 d-flex align-items-center " style="justify-content:space-between">
                                <div class="ms-3 me-3">
                                    <?php echo htmlspecialchars($row['nama_menu']); ?>
                                </div>
                                <div class="ms-3 me-3">
                                    <!-- Form untuk increment dan decrement -->
                                    <form method="POST" action="" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-secondary" name="action" value="decrement">-</button>
                                        <span id="jumlah-<?php echo $row['id']; ?>"> <?php echo $row['jumlah']; ?> </span>
                                        <button type="submit" class="btn btn-secondary" name="action" value="increment">+</button>
                                    </form>
                                </div>
                                <div class="ms-3 me-3">
                                    Rp. <?php echo number_format($totalHarga, 0, ',', '.'); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Tidak ada pesanan.</p>
                    <?php endif; ?>
                    <div class="mb-3 linee"></div>
                    <div class="mb-3 coCont">
                        <div class="d-flex justify-content-around">
                            <div>
                            <button type="button" class="btn text-light ubuntu-medium" data-bs-toggle="modal" data-bs-target="#paymentModal">Payment</button>

                            </div>
                            <div>
                                <!-- Tampilkan total harga -->
                                <?php ?>
                                <button type="button" class="btn text-dark ubuntu-medium" style="background-color: #F9B989;">
                                    Rp. <?php echo number_format($sumTot, 0, ',', '.'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

<!-- modalPayment -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Total Belanja: <strong>Rp. <span id="totalBelanja"><?php echo number_format($sumTot, 0, ',', '.'); ?></span></strong></p>
        <div class="mb-3">
            <label for="uangMasuk" class="form-label">Uang Masuk</label>
            <input type="number" class="form-control" id="uangMasuk" placeholder="Masukkan jumlah uang">
        </div>
        <p id="kembalian" class="text-success" style="display: none;">Kembalian: <span></span></p>
        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <form method="POST" action="simpan_pembayaran.php">
            <input type="hidden" name="total_belanja" value="<?php echo $sumTot; ?>">
            <input type="hidden" id="uangPembayaranInput" name="uang_pembayaran">
            <input type="hidden" id="kembalianInput" name="kembalian">
            <button type="submit" class="btn btn-primary">Konfirmasi</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal for receipt -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Thank you for your payment!</p>
                <p>Total Belanja: Rp. <span id="receiptTotal"><?php echo number_format($sumTot, 0, ',', '.'); ?></span></p>
                <p>Uang Masuk: Rp. <span id="receiptPaid"><?php echo number_format($uangMasuk, 0, ',', '.'); ?></span></p>
                <p>Kembalian: Rp. <span id="receiptChange"><?php echo number_format($kembalian, 0, ',', '.'); ?></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>

<?php if (isset($totalBelanja)): ?>
    var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    paymentModal.hide();
    var receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
    receiptModal.show();
<?php endif; ?>


  const uangMasukInput = document.getElementById('uangMasuk');
  const totalBelanja = document.getElementById('totalBelanja').textContent.replace(/\./g, ''); // Remove dots for number calculation
  const kembalianText = document.getElementById('kembalian');

  uangMasukInput.addEventListener('input', () => {
    const uangMasuk = parseInt(uangMasukInput.value, 10);
    if (!isNaN(uangMasuk) && uangMasuk >= totalBelanja) {
      const kembalian = uangMasuk - totalBelanja;
      kembalianText.style.display = 'block';
      kembalianText.querySelector('span').textContent = 'Rp. ' + kembalian.toLocaleString();
      document.getElementById('uangPembayaranInput').value = uangMasuk;
      document.getElementById('kembalianInput').value = kembalian;
    } else {
      kembalianText.style.display = 'none';
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
