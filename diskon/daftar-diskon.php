<?php

require "../config/config.php";

$title = "Daftar Diskon";
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
                    <h1 class="m-0">Diskon</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Diskon</li>
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
                            <h3 class="card-title">Daftar Diskon</h3>
                            <a href="add-diskon.php" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Add Diskon</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Diskon</th>
                                        <th>Tipe Diskon</th>
                                        <th>Nilai</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Aktif</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM tbl_diskon ORDER BY tanggal_mulai DESC";
                                    $result = mysqli_query($connection, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?= $row['diskon_id'] ?></td>
                                            <td><?= htmlspecialchars($row['nama_diskon']) ?></td>
                                            <td><?= htmlspecialchars($row['tipe_diskon']) ?></td>
                                            <td>
                                                <?php
                                                if ($row['tipe_diskon'] === 'persen') {
                                                    echo intval($row['nilai']) . ' %';
                                                } else {
                                                    echo 'Rp ' . number_format($row['nilai'], 0, ',', '.');
                                                }
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($row['tanggal_mulai']) ?></td>
                                            <td><?= htmlspecialchars($row['tanggal_selesai']) ?></td>
                                            <td><?= $row['aktif'] ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Nonaktif</span>' ?></td>
                                            <td>
                                                <a href="edit-diskon.php?id=<?= $row['diskon_id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                                <a href="delete-diskon.php?id=<?= $row['diskon_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this diskon?');"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
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