<?php

function insert($data)
{
    global $connection;

    $nama_kategori = strtolower(mysqli_real_escape_string($connection, $data["nama_kategori"]));

    $query = "INSERT INTO tbl_kategori (nama_kategori) VALUES ('$nama_kategori')";
    $result = mysqli_query($connection, $query);
    return $result;
}

function delete($id)
{
    global $connection;
    // Cek apakah kategori masih dipakai di produk
    $cekProduk = mysqli_query($connection, "SELECT 1 FROM tbl_produk WHERE kategori_id = $id LIMIT 1");
    if (mysqli_num_rows($cekProduk) > 0) {
        echo "<script>alert('Kategori tidak bisa dihapus karena masih memiliki produk!');window.location.href='../kategori/daftar-kategori.php';</script>";
        return false;
    }
    mysqli_query($connection, "DELETE FROM tbl_kategori WHERE kategori_id = $id");
    return mysqli_affected_rows($connection);
}
function updateKategori($data)
{
    global $connection;

    $kategori_id = intval($data['kategori_id']);
    $nama_kategori = strtolower(mysqli_real_escape_string($connection, $data["nama_kategori"]));

    // Cek nama kategori sudah dipakai kategori lain
    $cek = mysqli_query($connection, "SELECT * FROM tbl_kategori WHERE nama_kategori = '$nama_kategori' AND kategori_id != $kategori_id");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Nama kategori sudah digunakan kategori lain!'); window.location.href = '../kategori/edit-kategori.php?id=$kategori_id';</script>";
        return false;
    }

    $query = "UPDATE tbl_kategori SET nama_kategori = '$nama_kategori' WHERE kategori_id = $kategori_id";
    $result = mysqli_query($connection, $query);
    return $result;
}
