<?php

require "../config/config.php";

$title = "Add User";
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
          <h1 class="m-0">Users</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $main_url ?>user/data-user.php">Users</a></li>
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
                    <h3 class="card-title">User List</h3>
                    <a href="add-user.php" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Add User</a>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Photo</th>
                          <th>Name</th>
                          <th>address</th>
                          <th>Email</th>
                          <th>Role</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $query = "SELECT * FROM tbl_user";
                        $result = mysqli_query($connection, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                          <tr>
                            <td><?= $row['userid'] ?></td>
                            <td><img src="<?= $main_url ?>asset/image/<?= $row['foto'] ?>" width="80px" class="img-circle elevation-2" alt=""></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['addres'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td>
                              <?php
                              if ($row['level'] == 1) {
                              echo "Admin";
                              } elseif ($row['level'] == 2) {
                              echo "Kasir";
                              } 
                              ?>
                            </td>
                            <td><?= $row['status'] ?></td>
                            <td>
                              <a href="edit-user.php?id=<?= $row['userid'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                              <a href="delete-user.php?id=<?= $row['userid'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash-alt"></i></a>
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