<!-- edit-member.php -->
<?php
require "../config/config.php";
require "../config/function.php";
require "../modul/model-member.php";

$title = "Edit Member";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$id = $_GET['id'];
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$sqlEdit = "SELECT * FROM tbl_member WHERE member_id = $id";
$member = getData($sqlEdit)[0];

if (isset($_POST['update'])) {
    $nama_member = mysqli_real_escape_string($connection, $_POST['nama_member']);
    $member_id = intval($_POST['member_id']);
    // Cek nama_member sudah dipakai member lain
    $cek = mysqli_query($connection, "SELECT * FROM tbl_member WHERE nama_member = '$nama_member' AND member_id != $member_id");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('nama member sudah digunakan member lain!'); window.location.href = 'edit-member.php?id=$member_id';</script>";
        exit;
    }
    if (updateMember($_POST) > 0) {
        echo "<script>alert('Data member berhasil diupdate'); document.location.href = 'daftar-member.php';</script>";
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
                    <h1 class="m-0">Edit Member</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>member/daftar-member.php">Member</a></li>
                        <li class="breadcrumb-item active">Edit Member</li>
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
                            <h3 class="card-title">Edit Member</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="member_id" value="<?= $member['member_id'] ?>">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama_member">Nama Member</label>
                                    <input type="text" class="form-control" id="nama_member" placeholder="Masukkan Nama Member" name="nama_member" value="<?= htmlspecialchars($member['nama_member']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp">No HP</label>
                                    <input type="text" class="form-control" id="no_hp" placeholder="Masukkan No HP" name="no_hp" value="<?= htmlspecialchars($member['no_hp']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($member['alamat']) ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_daftar">Tanggal Daftar</label>
                                    <input type="date" class="form-control" id="tanggal_daftar" name="tanggal_daftar" value="<?= htmlspecialchars($member['tanggal_daftar']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="poin">Poin</label>
                                    <input type="number" class="form-control" id="poin" name="poin" value="<?= htmlspecialchars($member['poin']) ?>" required>
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