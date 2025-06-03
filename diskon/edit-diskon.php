<?php
require "../config/config.php";

$title = "Edit Diskon";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<script>alert('ID diskon tidak ditemukan!'); window.location.href='daftar-diskon.php';</script>";
    exit;
}

// Ambil data diskon
$query = "SELECT * FROM tbl_diskon WHERE diskon_id = $id";
$result = mysqli_query($connection, $query);
$diskon = mysqli_fetch_assoc($result);
if (!$diskon) {
    echo "<script>alert('Diskon tidak ditemukan!'); window.location.href='daftar-diskon.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $nama_diskon = mysqli_real_escape_string($connection, $_POST['nama_diskon']);
    $tipe_diskon = mysqli_real_escape_string($connection, $_POST['tipe_diskon']);
    $nilai = floatval($_POST['nilai']);
    $tanggal_mulai = mysqli_real_escape_string($connection, $_POST['tanggal_mulai']);
    $tanggal_selesai = mysqli_real_escape_string($connection, $_POST['tanggal_selesai']);
    $aktif = intval($_POST['aktif']);

    // Cek nama diskon sudah dipakai diskon lain
    $cek = mysqli_query($connection, "SELECT * FROM tbl_diskon WHERE nama_diskon = '$nama_diskon' AND diskon_id != $id");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Nama diskon sudah digunakan diskon lain!'); window.location.href = 'edit-diskon.php?id=$id';</script>";
        exit;
    }

    $query_update = "UPDATE tbl_diskon SET nama_diskon='$nama_diskon', tipe_diskon='$tipe_diskon', nilai=$nilai, tanggal_mulai='$tanggal_mulai', tanggal_selesai='$tanggal_selesai', aktif=$aktif WHERE diskon_id=$id";
    if (mysqli_query($connection, $query_update)) {
        echo "<script>alert('Diskon berhasil diupdate!'); window.location.href='daftar-diskon.php';</script>";
    } else {
        echo "<script>alert('Gagal update diskon!'); window.location.href='edit-diskon.php?id=$id';</script>";
    }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Diskon</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>diskon/daftar-diskon.php">Diskon</a></li>
                        <li class="breadcrumb-item active">Edit Diskon</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Diskon</h3>
                        </div>
                        <form role="form" method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama_diskon">Nama Diskon</label>
                                    <input type="text" class="form-control" id="nama_diskon" name="nama_diskon" value="<?= htmlspecialchars($diskon['nama_diskon']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tipe_diskon">Tipe Diskon</label>
                                    <select name="tipe_diskon" class="form-control" id="tipe_diskon" required>
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="persen" <?= $diskon['tipe_diskon'] == 'persen' ? 'selected' : '' ?>>Persen (%)</option>
                                        <option value="nominal" <?= $diskon['tipe_diskon'] == 'nominal' ? 'selected' : '' ?>>Nominal (Rp)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nilai">Nilai Diskon</label>
                                    <input type="number" step="0.01" class="form-control" id="nilai" name="nilai" value="<?= htmlspecialchars($diskon['nilai']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?= htmlspecialchars($diskon['tanggal_mulai']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_selesai">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="<?= htmlspecialchars($diskon['tanggal_selesai']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="aktif">Status</label>
                                    <select name="aktif" class="form-control" id="aktif" required>
                                        <option value="1" <?= $diskon['aktif'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                        <option value="0" <?= $diskon['aktif'] == 0 ? 'selected' : '' ?>>Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require "../template/footer.php"; ?>
</div>