<?php
// filepath: c:\xampp\htdocs\usk-kasir\transaksi\hapus-item.php
session_start();

// Periksa apakah parameter 'id' ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Periksa apakah keranjang ada di sesi
    if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
        // Cari item berdasarkan ID dan hapus dari keranjang
        foreach ($_SESSION['keranjang'] as $key => $item) {
            if ($item['id'] == $id) {
                unset($_SESSION['keranjang'][$key]);
                break;
            }
        }

        // Reindex array keranjang setelah item dihapus
        $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
    }
}

// Redirect kembali ke halaman keranjang
header("Location: keranjang.php");
exit;
