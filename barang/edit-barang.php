<?php

require "../config/config.php";
require "../config/function.php";
require "../modul/model-produk.php";

$title = "Edit Barang";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$id = $_GET['id'];
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$sqlEdit = "SELECT * FROM tbl_produk WHERE produk_id = $id";
$barang = getData($sqlEdit)[0];
$kategori_id = $barang['kategori_id'];

if (isset($_POST['update'])) {
  if (updateBarang($_POST) > 0) {
    // Jika user memilih diskon, update juga diskon_id di tbl_produk
    if (!empty($_POST['diskon_id'])) {
      $diskon_id = intval($_POST['diskon_id']);
      $produk_id = intval($_POST['produk_id']);
      $sql_diskon = "UPDATE tbl_produk SET diskon_id = $diskon_id WHERE produk_id = $produk_id";
      mysqli_query($connection, $sql_diskon);
    } else if (isset($_POST['diskon_id'])) {
      // Jika user memilih kosongkan diskon
      $produk_id = intval($_POST['produk_id']);
      $sql_diskon = "UPDATE tbl_produk SET diskon_id = NULL WHERE produk_id = $produk_id";
      mysqli_query($connection, $sql_diskon);
    }
    echo "<script>
        alert('Data berhasil diupdate');
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
            <li class="breadcrumb-item"><a href="<?= $main_url ?>barang/daftar-barang.php">Barang</a></li>
            <li class="breadcrumb-item active">Edit Barang</li>
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
              <h3 class="card-title">Edit Barang</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="produk_id" value="<?= $barang['produk_id'] ?>">
              <div class="card-body">
                <div class="form-group">
                  <label for="nama_produk">Nama Produk</label>
                  <input type="text" class="form-control" id="nama_produk" placeholder="Masukkan Nama Produk" name="nama_produk" value="<?= $barang['nama_produk'] ?>" required>
                </div>
                <div class="form-group">
                  <label for="harga_beli">Harga Beli</label>
                  <input type="number" step="0.01" class="form-control" id="harga_beli" placeholder="Masukkan Harga Beli" name="harga_beli" value="<?= $barang['harga_beli'] ?>" required>
                </div>
                <div class="form-group">
                  <label for="harga_jual">Harga Jual</label>
                  <input type="number" step="0.01" class="form-control" id="harga_jual" placeholder="Masukkan Harga Jual" name="harga_jual" value="<?= $barang['harga_jual'] ?>" required>
                </div>
                <div class="form-group">
                  <label for="stok">Stok</label>
                  <input type="number" class="form-control" id="stok" placeholder="Masukkan Stok" name="stok" value="<?= $barang['stok'] ?>" required>
                </div>
                <div class="form-group">
                  <label for="satuan">Satuan</label>
                  <input type="text" class="form-control" id="satuan" placeholder="Masukkan Satuan" name="satuan" value="<?= $barang['satuan'] ?>">
                </div>
                <div class="form-group">
                  <label for="kategori_id">Kategori</label>
                  <select name="kategori_id" class="form-control" id="kategori_id">
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    $kategori = getKategori(); // Assuming a function to fetch categories
                    foreach ($kategori as $kat) {
                      $selected = $kat['kategori_id'] == $kategori_id ? "selected" : "";
                      echo "<option value='{$kat['kategori_id']}' $selected>{$kat['nama_kategori']}</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="diskon_id">Diskon (Opsional)</label>
                  <select name="diskon_id" class="form-control" id="diskon_id">
                    <option value="">-- Tidak Ada Diskon --</option>
                    <?php
                    $qDiskon = mysqli_query($connection, "SELECT * FROM tbl_diskon WHERE aktif = 1 AND tanggal_mulai <= CURDATE() AND tanggal_selesai >= CURDATE()");
                    while ($d = mysqli_fetch_assoc($qDiskon)) {
                      $selected = isset($barang['diskon_id']) && $barang['diskon_id'] == $d['diskon_id'] ? 'selected' : '';
                      $label = $d['nama_diskon'] . ' (';
                      if ($d['tipe_diskon'] == 'persen') {
                        $label .= $d['nilai'] . '%';
                      } else {
                        $label .= 'Rp ' . number_format($d['nilai'], 0, ',', '.');
                      }
                      $label .= ')';
                      echo "<option value='{$d['diskon_id']}' $selected>$label</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="image">Gambar Produk</label>
                  <input type="hidden" name="oldImg" value="<?= $barang['gambar_produk'] ?>">
                  <img src="<?= $main_url ?>asset/image/<?= $barang['gambar_produk'] ?>" class="img-thumbnail" style="width: 80px; height: 80px;" alt="Gambar Produk">
                  <input type="file" name="image" class="form-control" id="image">
                  <span class="text-muted">Tipe file gambar JPG | PNG</span>
                </div>
              </div>
              <button type="submit" name="update" class="btn btn-primary btn-sm">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php

  require "../template/footer.php";

  ?>