<?php
require "../config/config.php";
require "../config/function.php";
// require "../modul/model-diskon.php"; // Uncomment if you have a model for diskon

$title = "Add Diskon";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

// Handle form submit (pseudo, sesuaikan dengan model/logic insert Anda)
if (isset($_POST['simpan'])) {
    $nama_diskon      = mysqli_real_escape_string($connection, $_POST['nama_diskon']);
    $tipe_diskon      = mysqli_real_escape_string($connection, $_POST['tipe_diskon']);
    $nilai            = floatval($_POST['nilai']);
    $tanggal_mulai    = mysqli_real_escape_string($connection, $_POST['tanggal_mulai']);
    $tanggal_selesai  = mysqli_real_escape_string($connection, $_POST['tanggal_selesai']);
    $aktif            = intval($_POST['aktif']);

    // Cek nama diskon sudah ada
    $cek = mysqli_query($connection, "SELECT * FROM tbl_diskon WHERE nama_diskon = '$nama_diskon'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Nama diskon sudah terdaftar, silakan gunakan nama lain!'); window.location.href = 'add-diskon.php';</script>";
        exit;
    }

    $query = "INSERT INTO tbl_diskon (nama_diskon, tipe_diskon, nilai, tanggal_mulai, tanggal_selesai, aktif)
              VALUES ('$nama_diskon', '$tipe_diskon', $nilai, '$tanggal_mulai', '$tanggal_selesai', $aktif)";

    if (mysqli_query($connection, $query)) {
        echo "<script>
            alert('Diskon berhasil ditambahkan!');
            window.location.href = '../diskon/daftar-diskon.php';
        </script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menambah diskon: " . mysqli_error($connection) . "</div>";
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
                    <h1 class="m-0">Diskon</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>diskon/daftar-diskon.php">Diskon</a></li>
                        <li class="breadcrumb-item active">Add Diskon</li>
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
                            <h3 class="card-title">Add Diskon</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama_diskon">Nama Diskon</label>
                                    <input type="text" class="form-control" id="nama_diskon" name="nama_diskon" placeholder="Masukkan Nama Diskon" required>
                                </div>
                                <div class="form-group">
                                    <label for="tipe_diskon">Tipe Diskon</label>
                                    <select name="tipe_diskon" class="form-control" id="tipe_diskon" required>
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="persen">Persen (%)</option>
                                        <option value="nominal">Nominal (Rp)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nilai">Nilai Diskon</label>
                                    <input type="number" step="0.01" class="form-control" id="nilai" name="nilai" placeholder="Masukkan Nilai Diskon" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_selesai">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                                </div>
                                <div class="form-group">
                                    <label for="aktif">Status</label>
                                    <select name="aktif" class="form-control" id="aktif" required>
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                </div>
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