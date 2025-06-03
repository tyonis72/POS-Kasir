<?php

require "../config/config.php";
require "../config/function.php";
require "../modul/model-member.php";

$title = "Add Member";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

if (isset($_POST['simpan'])) {
  $nama_member = mysqli_real_escape_string($connection, $_POST['nama_member']);
  $cek = mysqli_query($connection, "SELECT * FROM tbl_member WHERE nama_member = '$nama_member'");
  if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Nama member sudah terdaftar, silakan gunakan nama lain!'); window.location.href = 'add-member.php';</script>";
    exit;
  }

  $_POST['status'] = 'Aktif'; // Pastikan status otomatis diset ke Aktif

  if (insert($_POST) > 0) {
    echo "<script>
        alert('Member berhasil ditambah');
        document.location.href = 'daftar-member.php';
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
          <h1 class="m-0">Member</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $main_url ?>member/daftar-member.php">Member</a></li>
            <li class="breadcrumb-item active">Add Member</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add Member</h3>
            </div>
            <!-- form start -->
            <form role="form" method="POST">
              <div class="card-body">
                <div class="form-group">
                  <label for="nama_member">Nama Member</label>
                  <input type="text" class="form-control" id="nama_member" placeholder="Masukkan Nama Member" name="nama_member" required>
                </div>
                <div class="form-group">
                  <label for="no_hp">No HP</label>
                  <input type="text" class="form-control" id="no_hp" placeholder="Masukkan No HP" name="no_hp">
                </div>
                <div class="form-group">
                  <label for="alamat">Alamat</label>
                  <textarea class="form-control" id="alamat" placeholder="Masukkan Alamat" name="alamat"></textarea>
                </div>
                <input type="hidden" name="status" value="Aktif">
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
