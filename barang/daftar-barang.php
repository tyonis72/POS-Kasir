<?php

require "../config/config.php";

$title = "Daftar Barang";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Barang</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $main_url ?>barang/data-barang.php">Barang</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Barang List</h3>
                        <a href="add-barang.php" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Add Barang</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Gambar</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Kategori</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = "SELECT p.*, k.nama_kategori FROM tbl_produk p 
                                LEFT JOIN tbl_kategori k ON p.kategori_id = k.kategori_id";
                          $result = mysqli_query($connection, $query);
                          while ($row = mysqli_fetch_assoc($result)) {
                          ?>
                            <tr>
                              <td><?= $row['produk_id'] ?></td>
                              <td><?= $row['nama_produk'] ?></td>
                              <td>
                                <img src="<?= $main_url . 'asset/image/' . $row['gambar_produk'] ?>" alt="<?= $row['nama_produk'] ?>" width="50">
                              </td>
                              <td><?= number_format($row['harga_beli'], 2) ?></td>
                              <td><?= number_format($row['harga_jual'], 2) ?></td>
                              <td><?= $row['stok'] ?></td>
                              <td><?= $row['satuan'] ?></td>
                              <td><?= $row['nama_kategori'] ?></td>
                              <td>
                                <a href="edit-barang.php?id=<?= $row['produk_id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="delete-barang.php?id=<?= $row['produk_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');"><i class="fas fa-trash-alt"></i></a>
                                <button class="btn btn-info btn-sm" onclick="return showBarcodeModal('<?= $row['barcode'] ?>')"><i class="fas fa-barcode"></i></button>
                              </td>
                            </tr>
                          <?php
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->

<div id="barcodeModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
    <div class="modal-dialog" style="background: white; border-radius: 10px; width: 90%; max-width: 500px; padding: 20px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
            <h5 class="modal-title" style="margin: 0; font-size: 1.25rem;">Barcode Produk</h5>
            <button type="button" class="close" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" style="padding: 20px; text-align: center;">
            <div id="barcodeContainer">
                <canvas id="barcode"></canvas>
            </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: flex-end; border-top: 1px solid #ddd; padding-top: 10px;">
            <button type="button" class="btn-close" style="background: #ccc; color: #333; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;" onclick="closeModal()">Tutup</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
    function showBarcodeModal(barcode) {
        // Generate the barcode using JsBarcode
        JsBarcode("#barcode", barcode, {
            format: "CODE128",
            lineColor: "#000",
            width: 2,
            height: 100,
            displayValue: true
        });

        // Show the modal
        document.getElementById('barcodeModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('barcodeModal').style.display = 'none';
        // Clear the barcode canvas
        var canvas = document.getElementById('barcode');
        if (canvas && canvas.getContext) {
            var ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }

    // Optional: Close modal when clicking outside the modal dialog
    window.onclick = function(event) {
        var modal = document.getElementById('barcodeModal');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>


  <?php

  require "../template/footer.php";

  ?>