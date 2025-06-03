<?php

require "../config/config.php";
require "../config/function.php";
require "../modul/model-produk.php";

$title = "Add Produk";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

if (isset($_POST['simpan'])) {
  $nama_produk = mysqli_real_escape_string($connection, $_POST['nama_produk']);
  $cek = mysqli_query($connection, "SELECT * FROM tbl_produk WHERE nama_produk = '$nama_produk'");
  if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Nama produk sudah terdaftar, silakan gunakan nama lain!'); window.location.href = 'add-barang.php';</script>";
    exit;
  }
  if (insert($_POST) > 0) {
    echo "<script>
    alert('produk berhasil ditambah');
    document.location.href = 'daftar-barang.php';
    </script>";
  }
}

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
            <li class="breadcrumb-item active">Add User</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add User</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                <div class="form-group">
                  <label for="nama_produk">Nama Produk</label>
                  <input type="text" class="form-control" id="nama_produk" placeholder="Masukkan Nama Produk" name="nama_produk" required>
                </div>
                <div class="form-group">
                  <label for="harga_beli">Harga Beli</label>
                  <input type="number" step="0.01" class="form-control" id="harga_beli" placeholder="Masukkan Harga Beli" name="harga_beli" required>
                </div>
                <div class="form-group">
                  <label for="harga_jual">Harga Jual</label>
                  <input type="number" step="0.01" class="form-control" id="harga_jual" placeholder="Masukkan Harga Jual" name="harga_jual" required>
                </div>
                <div class="form-group">
                  <label for="stok">Stok</label>
                  <input type="number" class="form-control" id="stok" placeholder="Masukkan Stok" name="stok" required>
                </div>
                <div class="form-group">
                  <label for="satuan">Satuan</label>
                  <input type="text" class="form-control" id="satuan" placeholder="Masukkan Satuan" name="satuan">
                </div>
                <div class="form-group">
                  <label for="kategori_id">Kategori</label>
                  <select name="kategori_id" class="form-control" id="kategori_id">
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    $kategori = getKategori(); // Assuming a function to fetch categories
                    foreach ($kategori as $kat) {
                      echo "<option value='{$kat['kategori_id']}'>{$kat['nama_kategori']}</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="image">Gambar Produk</label>
                  <input type="file" name="image" class="form-control" id="image">
                  <span class="text-muted">Tipe file gambar JPG | PNG</span>
                </div>
              </div>
              <button type="submit" name="simpan" class="btn btn-primary btn-sm">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>


  <?php

  require "../template/footer.php";

  ?>