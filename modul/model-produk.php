<?php

function getKategori()
{
    global $connection;
    $query = "SELECT * FROM tbl_kategori";
    $result = mysqli_query($connection, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function insert($data)
{
    global $connection;

    $nama_produk = mysqli_real_escape_string($connection, $data["nama_produk"]);
    $harga_beli = mysqli_real_escape_string($connection, $data["harga_beli"]);
    $harga_jual = mysqli_real_escape_string($connection, $data["harga_jual"]);
    $stok = mysqli_real_escape_string($connection, $data["stok"]);
    $satuan = mysqli_real_escape_string($connection, $data["satuan"]);
    $kategori_id = mysqli_real_escape_string($connection, $data["kategori_id"]);

    $gambar = uploadimg();

    if (!$gambar) {
        return false;
    }


    $query = "INSERT INTO tbl_produk (nama_produk, harga_beli, harga_jual, stok, satuan, kategori_id, gambar_produk) 
              VALUES ('$nama_produk', '$harga_beli', '$harga_jual', '$stok', '$satuan', '$kategori_id', '$gambar')";

    mysqli_query($connection, $query);

    return mysqli_affected_rows($connection);
}

function delete($id)
{
    global $connection;
    // Cek stok barang
    $cek = mysqli_query($connection, "SELECT stok FROM tbl_produk WHERE produk_id = $id");
    $row = mysqli_fetch_assoc($cek);
    if ($row && $row['stok'] > 0) {
        echo "<script>alert('Barang tidak bisa dihapus karena masih memiliki stok!'); window.location.href='daftar-barang.php';</script>";
        return 0;
    }
    mysqli_query($connection, "DELETE FROM tbl_detail_transaksi WHERE produk_id = $id");
    mysqli_query($connection, "DELETE FROM tbl_produk WHERE produk_id = $id");
    return mysqli_affected_rows($connection);
}

function updateBarang($data)
{
    global $connection;

    $produk_id = mysqli_real_escape_string($connection, $data["produk_id"]);
    $nama_produk = mysqli_real_escape_string($connection, $data["nama_produk"]);
    $harga_beli = mysqli_real_escape_string($connection, $data["harga_beli"]);
    $harga_jual = mysqli_real_escape_string($connection, $data["harga_jual"]);
    $stok = mysqli_real_escape_string($connection, $data["stok"]);
    $satuan = mysqli_real_escape_string($connection, $data["satuan"]);
    $kategori_id = mysqli_real_escape_string($connection, $data["kategori_id"]);
    $gambar = mysqli_real_escape_string($connection, $_FILES["image"]["name"]);
    $fotolama = mysqli_real_escape_string($connection, $data["oldImg"]);

    // Cek nama produk sudah dipakai produk lain
    $cek = mysqli_query($connection, "SELECT * FROM tbl_produk WHERE nama_produk = '$nama_produk' AND produk_id != '$produk_id'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Nama produk sudah digunakan barang lain!'); window.location.href = '../barang/edit-barang.php?id=$produk_id';</script>";
        return 0;
    }

    // Validasi data
    if (empty($produk_id) || empty($nama_produk) || empty($harga_beli) || empty($harga_jual) || empty($stok) || empty($kategori_id)) {
        die("Data tidak lengkap. Pastikan semua field diisi.");
    }

    // Upload gambar baru jika ada
    if ($gambar != null) {
        $url = "data-user.php";
        $imgProduk = uploadimg($url);
        if (!$imgProduk) {
            $imgProduk = $fotolama;
        }
        if ($fotolama != 'default.png') {
            @unlink('../asset/image/' . $fotolama);
        }
    } else {
        $imgProduk = $fotolama;
    }

    // Query update
    $query = "UPDATE tbl_produk 
              SET nama_produk = '$nama_produk', 
                  harga_beli = '$harga_beli', 
                  harga_jual = '$harga_jual', 
                  stok = '$stok', 
                  satuan = '$satuan', 
                  kategori_id = '$kategori_id',
                  gambar_produk = '$imgProduk'
              WHERE produk_id = '$produk_id'";

    mysqli_query($connection, $query);

    return mysqli_affected_rows($connection);
}

// Fungsi untuk mendapatkan diskon aktif pada produk tertentu
function getActiveDiskonByProduk($produk_id)
{
    global $connection;
    $today = date('Y-m-d');
    $query = "SELECT d.* 
              FROM tbl_diskon d
              INNER JOIN tbl_produk p ON p.diskon_id = d.diskon_id
              WHERE p.produk_id = '$produk_id' 
                AND d.aktif = 1 
                AND (d.tanggal_mulai IS NULL OR d.tanggal_mulai <= '$today') 
                AND (d.tanggal_selesai IS NULL OR d.tanggal_selesai >= '$today')
              LIMIT 1";
    $result = mysqli_query($connection, $query);
    return mysqli_fetch_assoc($result);
}

// Contoh penggunaan pada proses transaksi atau tampilan harga produk:
// $diskon = getActiveDiskonByProduk($produk_id);
// if ($diskon) {
//     if ($diskon['tipe_diskon'] == 'persen') {
//         $harga_diskon = $harga_jual - ($harga_jual * $diskon['nilai'] / 100);
//     } else {
//         $harga_diskon = $harga_jual - $diskon['nilai'];
//     }
// } else {
//     $harga_diskon = $harga_jual;
// }
