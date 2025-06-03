<?php

require "../config/config.php";

$title = "Daftar Member";
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
          <h1 class="m-0">Member</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $main_url ?>member/daftar-member.php">Member</a></li>
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
              <h3 class="card-title">Daftar Member</h3>
              <a href="add-member.php" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Add Member</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nama Member</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Tanggal Daftar</th>
                    <th>Poin</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = "SELECT * FROM tbl_member";
                  $result = mysqli_query($connection, $query);
                  while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                    <tr>
                      <td><?= $row['member_id'] ?></td>
                      <td><?= $row['nama_member'] ?></td>
                      <td><?= $row['no_hp'] ?></td>
                      <td><?= $row['alamat'] ?></td>
                      <td><?= $row['tanggal_daftar'] ?></td>
                      <td><?= isset($row['poin']) ? $row['poin'] : 0 ?></td>
                      <td><?= isset($row['status']) ? htmlspecialchars($row['status']) : '-' ?></td>
                      <td>
                        <a href="edit-member.php?id=<?= $row['member_id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="delete-member.php?id=<?= $row['member_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this member?');"><i class="fas fa-trash-alt"></i></a>
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


  <?php

  require "../template/footer.php";

  ?>