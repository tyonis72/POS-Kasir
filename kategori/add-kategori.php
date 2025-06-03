<?php

require "../config/config.php";

$title = "Add Kategori";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";
require "../modul/model-kategori.php";

if (isset($_POST['simpan'])) {
  $nama_kategori = strtolower(mysqli_real_escape_string($connection, $_POST['nama_kategori']));
  $cek = mysqli_query($connection, "SELECT * FROM tbl_kategori WHERE nama_kategori = '$nama_kategori'");
  if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Nama kategori sudah terdaftar, silakan gunakan nama lain!'); window.location.href = 'add-kategori.php';</script>";
    exit;
  }
  if (insert($_POST) > 0) {
    echo "<script>
        alert('Kategori berhasil ditambahkan');
        document.location.href = 'daftar-kategori.php';
        </script>";
  } else {
    echo "<script>
        alert('Kategori gagal ditambahkan');
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
          <h1 class="m-0">Kategori</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $main_url ?>kategori/daftar-kategori.php">Kategori</a></li>
            <li class="breadcrumb-item active">Add Kategori</li>
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
              <h3 class="card-title">Add Kategori</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST">
              <div class="card-body">
                <div class="form-group">
                  <label for="namaKategori">Nama Kategori</label>
                  <input type="text" class="form-control" id="namaKategori" placeholder="Enter kategori name" name="nama_kategori" required>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" name="simpan" class="btn btn-primary btn-sm">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php

require "../template/footer.php";

?>