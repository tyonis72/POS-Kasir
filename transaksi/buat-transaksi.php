<?php
// filepath: c:\xampp\htdocs\usk-kasir\transaksi\buat-transaksi.php
session_start();
include '../config/config.php'; // Pastikan file konfigurasi database sudah di-include
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Periksa apakah keranjang tidak kosong
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang kosong!'); window.location.href='keranjang.php';</script>";
    exit;
}

// Proses transaksi jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan user login
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='../auth/login.php';</script>";
        exit;
    }
    $user_id = $_SESSION['user_id'];

    $member_id = !empty($_POST['member_id']) ? $_POST['member_id'] : 'NULL';
    $total_harga = 0;

    // Hitung total harga dari keranjang
    foreach ($_SESSION['keranjang'] as $item) {
        $total_harga += $item['harga'] * $item['jumlah'];
    }

    $bayar = $_POST['bayar'];
    $kembalian = $bayar - $total_harga;

    // Validasi pembayaran
    if ($bayar < $total_harga) {
        echo "<script>alert('Uang yang dibayarkan kurang!'); window.location.href='keranjang.php';</script>";
        exit;
    }

            // Ambil nomor member dari input
    $nomor_member = isset($_POST['nomor_member']) ? trim($_POST['nomor_member']) : '';
    $member_id = 'NULL';
    $poin_tambahan = $subtotal / 1000; // Misalnya 1 poin untuk setiap 1000 IDR

    if (!empty($nomor_member)) {
        // Cari member berdasarkan no_hp
        $q_member = mysqli_query($connection, "SELECT * FROM tbl_member WHERE no_hp = '" . mysqli_real_escape_string($connection, $nomor_member) . "'");
        if ($row_member = mysqli_fetch_assoc($q_member)) {
            $member_id = $row_member['member_id'];
        }
    }

    // Simpan data transaksi ke tabel `tbl_transaksi`
    $kode_transaksi = 'TRX-' . time(); // Generate kode transaksi unik
    $tanggal = date('Y-m-d H:i:s');
    $query_transaksi = "INSERT INTO tbl_transaksi (kode_transaksi, tanggal, total_harga, bayar, kembalian, user_id, member_id)
                        VALUES ('$kode_transaksi', '$tanggal', $total_harga, $bayar, $kembalian, $user_id, $member_id)";
    $connection->query($query_transaksi);

    // Ambil ID transaksi yang baru saja dibuat
    $transaksi_id = $connection->insert_id;

    $subtotal;

    // Simpan detail transaksi ke tabel `tbl_detail_transaksi`
    foreach ($_SESSION['keranjang'] as $item) {
        $produk_id = $item['id'];
        $jumlah = $item['jumlah'];
        $harga = $item['harga'];
        $subtotal = $harga * $jumlah;

        $query_detail = "INSERT INTO tbl_detail_transaksi (transaksi_id, produk_id, jumlah, harga, subtotal)
                         VALUES ($transaksi_id, $produk_id, $jumlah, $harga, $subtotal)";
        $connection->query($query_detail);

        // Kurangi stok produk di tabel `tbl_produk`
        $query_update_stok = "UPDATE tbl_produk SET stok = stok - $jumlah WHERE produk_id = $produk_id";
        $connection->query($query_update_stok);
    }

    if (!empty($nomor_member)) {
        mysqli_query($connection, "UPDATE tbl_member SET poin = poin + $poin_tambahan WHERE member_id = $member_id");
    }

    // Kosongkan keranjang
    unset($_SESSION['keranjang']);

    // Redirect ke halaman sukses
    echo "<script>alert('Transaksi berhasil!'); window.location.href='invoice.php?kode=$kode_transaksi';</script>";
    exit;
}
