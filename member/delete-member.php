<?php
require "../config/config.php";

if (!isset($_GET['id'])) {
    echo "<script>alert('ID member tidak ditemukan!'); window.location.href='daftar-member.php';</script>";
    exit;
}
$member_id = intval($_GET['id']);

// Hapus member dari tabel member
$query = "DELETE FROM tbl_member WHERE member_id = $member_id";
if (mysqli_query($connection, $query)) {
    echo "<script>alert('Member berhasil dihapus!'); window.location.href='daftar-member.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus member!'); window.location.href='daftar-member.php';</script>";
}
