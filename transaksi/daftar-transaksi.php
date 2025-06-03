<?php

require "../config/config.php";

$title = "Daftar Transaksi";
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
                    <h1 class="m-0">Transaksi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>transaksi/daftar-transaksi.php">Transaksi</a></li>
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
                            <h3 class="card-title">Daftar Transaksi</h3>
                            <a href="keranjang.php" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Add Transaksi</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Kasir</th>
                                        <th>Member</th>
                                        <th>Total Harga</th>
                                        <th>Bayar</th>
                                        <th>Kembalian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query untuk mengambil data dari tabel tbl_transaksi
                                    $query = "
                                        SELECT t.*, u.fullname AS kasir, m.nama_member 
                                        FROM tbl_transaksi t
                                        LEFT JOIN tbl_user u ON t.user_id = u.userid
                                        LEFT JOIN tbl_member m ON t.member_id = m.member_id
                                        ORDER BY t.tanggal DESC";
                                    $result = mysqli_query($connection, $query);

                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['kode_transaksi']); ?></td>
                                            <td><?= date('d F Y, H:i', strtotime($row['tanggal'])); ?></td>
                                            <td><?= htmlspecialchars($row['kasir']); ?></td>
                                            <td><?= htmlspecialchars($row['nama_member'] ?? '-'); ?></td>
                                            <td>Rp <?= number_format($row['total_harga'], 2, ',', '.'); ?></td>
                                            <td>Rp <?= number_format($row['bayar'], 2, ',', '.'); ?></td>
                                            <td>Rp <?= number_format($row['kembalian'], 2, ',', '.'); ?></td>
                                            <td>
                                                <a href="invoice.php?kode=<?= urlencode($row['kode_transaksi']); ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Lihat</a>
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