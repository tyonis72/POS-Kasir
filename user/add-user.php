<?php

require "../config/config.php";
require "../config/function.php";
require "../modul/mode-user.php";

$title = "Add User";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

if ($_SESSION['level'] != 1) { // 1 = Administrator
  echo "<script>alert('Akses ditolak! Hanya Administrator yang dapat menambah user.'); window.location.href = '../dashboard.php';</script>";
  exit;
}

if (isset($_POST['simpan'])) {
  $_POST['email'] = isset($_POST['email']) ? $_POST['email'] : '';
  $username = mysqli_real_escape_string($connection, $_POST['username']);
  $cek = mysqli_query($connection, "SELECT * FROM tbl_user WHERE username = '$username'");
  if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Username sudah terdaftar, silakan gunakan username lain!'); window.location.href = 'add-user.php';</script>";
    exit;
  }
  // Jika level 2, set nama level menjadi kasir
  if (isset($_POST['level']) && $_POST['level'] == '2') {
    $_POST['level_nama'] = 'kasir';
  } else if (isset($_POST['level']) && $_POST['level'] == '1') {
    $_POST['level_nama'] = 'admin';
  }
  if (insert($_POST) > 0) {
    echo "<script>
    alert('user berhasil diregistrasi');
    document.location.href = 'daftar-user.php';
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
          <h1 class="m-0">Users</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $main_url ?>user/data-user.php">Users</a></li>
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
                  <label for="exampleInputEmail1">Username</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter username" name="username">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                  <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword2">Confirm Password</label>
                  <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" name="password2">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Full Name</label>
                  <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Full Name" name="fullname">
                </div>
                <div class="form-group
                ">
                  <label for="exampleInputPassword1">Level</label>
                  <select name="level" class="form-control">
                    <option value="">-- Level User --</option>
                    <option value="1">Admin</option>
                    <option value="2">kasir</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="Address">Address</label>
                  <textarea class="form-control" id="Address" cols="" rows="3" class="form-control" placeholder="Masukan Alamat User" name="address" required></textarea>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail">Email</label>
                  <input type="email" class="form-control" id="exampleInputEmail" placeholder="Email" name="email" required>
                </div>
                <div class="col-lg-4-text-center">
                  <img src="<?= $main_url ?>asset/image/default.png" class="profile-user-img imb-circle-3" style="width: 80px; height
                : 80px;" alt="User Image">
                  <input type="file" name="image">
                  <span class="text-muted">Type file gambar JPG | PNG </span><br>
                  <span class="text-sm" width=height></span></br>
                </div>
                <button type="submit" name="simpan" class="btn btn-primary btn-sm">Submit</button>
                <!-- /.card-body -->
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>


  <?php

  require "../template/footer.php";

  ?>