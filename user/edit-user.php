<?php

require "../config/config.php";
require "../config/function.php";
require "../modul/mode-user.php";

$title = "Update User";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$id = $_GET['id'];

$sqlEdit = "SELECT * FROM tbl_user WHERE userid = $id";
$user = getData($sqlEdit)[0];
$level = $user['level'];

if (isset($_POST['koreksi'])) {
  $username = mysqli_real_escape_string($connection, $_POST['username']);
  $userid = intval($_POST['id']);
  // Cek username sudah dipakai user lain
  $cek = mysqli_query($connection, "SELECT * FROM tbl_user WHERE username = '$username' AND userid != $userid");
  if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Username sudah digunakan user lain!'); window.location.href = 'edit-user.php?id=$userid';</script>";
    exit;
  }
  if (update($_POST) > 0) {
    echo "<script>
    alert('Data Berhasil diupdate');
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
            <li class="breadcrumb-item"><a href="<?= $main_url ?>user/daftar-user.php">Users</a></li>
            <li class="breadcrumb-item active">Edit User</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <input type="hidden" name="id" value="<?= $user['userid'] ?>" name="id">
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add User</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?= $user['userid'] ?>">
              <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Username</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter username" name="username" value="<?= $user['username'] ?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Full Name</label>
                  <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Full Name" name="fullname" value="<?= $user['fullname'] ?>">
                </div>
                <div class="form-group
                ">
                  <label for="exampleInputPassword1">Level</label>
                  <select name="level" class="form-control">
                    <option value="">-- Level User --</option>
                    <option value="1" <?= selectUser1($level) ?>>Administrator</option>
                    <option value="2" <?= selectUser2($level) ?>>Supervisor</option>
                    <option value="3" <?= selectUser3($level) ?>>Operator</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="Address">Address</label>
                  <textarea class="form-control" id="Address" cols="" rows="3" class="form-control" placeholder="Masukan Alamat User" name="address" required><?= $user['addres'] ?></textarea>
                </div>
                <div class="col-lg-4-text-center">
                  <input type="hidden" name="oldImg" value="<?= $user['foto'] ?>">
                  <img src="<?= $main_url ?>asset/image/<?= $user['foto'] ?>" class="profile-user-img imb-circle-3" style="width: 80px; height
                : 80px;" alt="User Image">
                  <input type="file" name="image">
                  <span class="text-muted">Type file gambar JPG | PNG </span><br>
                  <span class="text-sm" width=height></span></br>
                </div>
                <button type="submit" name="koreksi" class="btn btn-primary btn-sm">Submit</button>
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