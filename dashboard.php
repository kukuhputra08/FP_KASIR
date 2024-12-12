<?php
    $konek_DB = new mysqli("localhost", "root", "", "fp_kasir");

    if ($konek_DB->connect_error) {
        die("Koneksi gagal: " . $konek_DB->connect_error);
    }

    $result = mysqli_query($konek_DB, "SELECT * FROM menu_makanan");

    if (!$result) {
        echo "Query gagal: " . mysqli_error($konek_DB);
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/dashboard.css">
</head>
<body>
    <nav class="navbar sticky-top">
        <div class="container d-flex justify-content-end">
          <a class="navbar-brand" href="#">
            <img class="snavcont" src="assets/person-circle.svg" alt="Bootstrap" width="40" height="34">
          </a>
        </div>
    </nav>

    <div class="d-flex flex-row justify-content-between">
        <div class="container-fluid startnav">
            <div class="d-flex justify-content-center">
                <div class="d-flex flex-column snavcont">
                    <a class="icon-link" href="#">
                        <img src="assets/bookmarks-fill.svg" alt="bookmark">
                    </a>
                </div>
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

        <div class="flex-fill d-flex justify-content-center">
            <div class="d-flex flex-column flex-fill">
                <div class="d-flex justify-content-between bar">
                    <input type="email" class="ms-5 rounded-3" id="exampleInputEmail1" style="border:2px solid white; " >
                    <a href="#" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <img class="me-5 stockicn" src="assets/box-arrow-in-down.svg" alt="Bootstrap">
                    </a>
                </div>

                <div class="d-flex justify-content-center bar">
                    <div class="linee rounded"></div>
                </div>

                <div class="container rowcont d-flex justify-content-center">
                    <div class="row d-flex justify-content-center">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="d-flex col-sm flex-column align-items-center" style=" justify-content:space-evenly; " >
                            <img src="assets/<?php echo $row['gambar'] ?>" width="200px" height="200px"/>
                                <span><?= $row['nama'] ?></span>
                                    <button 
                                        type="button" 
                                        class="btn text-light" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#menuModal" 
                                        data-nama="<?= $row['nama'] ?>" 
                                        data-harga="<?= $row['harga'] ?>"
                                    >
                                    <?php echo "Rp. " . number_format($row['harga'], 0, ',', '.') ?> 
                                    </button>
                               
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PopUP upload image -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Data Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="upload.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Produk</label>
                            <input type="file" class="form-control" id="foto" name="foto" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga Produk</label>
                            <input type="number" class="form-control" id="harga" name="harga" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuModalLabel">Konfirmasi Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- formPostDb -->
            <form action="proses_pemesanan.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="nama_menu" id="hiddenNama">
                <input type="hidden" name="harga_menu" id="hiddenHarga">
                <input type="hidden" name="jumlah" id="hiddenJumlah">

                <div class="modal-body">
                    <p style="font-weight:bold; font-size:1.5rem;">Nama Produk :   <span id="menuNama"></span></p>
                    <p style="font-weight:bold; font-size:1.5rem;">Harga:    Rp. <span id="menuHarga"></span></p>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="decreaseCount">-</button>
                        <span id="itemCount">1</span>
                        <button type="button" class="btn btn-secondary" id="increaseCount">+</button>
                    </div>
                    <span style="font-size:1rem; font-weight:bold;">Total : <span id="total"></span>  </span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="confirmOrder">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

    


    <script src="js/dasboard.js"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
