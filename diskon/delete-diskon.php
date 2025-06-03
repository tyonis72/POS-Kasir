<?php
require "../config/config.php";

if (!isset($_GET['id'])) {
    echo "<script>alert('ID diskon tidak ditemukan!'); window.location.href='daftar-diskon.php';</script>";
    exit;
}
$diskon_id = intval($_GET['id']);

// Unlink diskon dari produk yang memakai diskon ini
mysqli_query($connection, "UPDATE tbl_produk SET diskon_id = NULL WHERE diskon_id = $diskon_id");

// Hapus diskon dari tabel diskon
$query = "DELETE FROM tbl_diskon WHERE diskon_id = $diskon_id";
if (mysqli_query($connection, $query)) {
    echo "<script>alert('Diskon berhasil dihapus!'); window.location.href='daftar-diskon.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus diskon!'); window.location.href='daftar-diskon.php';</script>";
}
