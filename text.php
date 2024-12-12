<!-- File: index.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <title>Upload Produk</title>
</head>
<body>
  <!-- Tombol Upload -->
  <a href="#" data-bs-toggle="modal" data-bs-target="#uploadModal">
    <img class="me-5 stockicn" src="assets/box-arrow-in-down.svg" alt="Bootstrap">
  </a>

  <!-- Modal Upload -->
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
</body>
</html>
