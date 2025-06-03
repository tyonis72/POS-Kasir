<?php
// Contoh di file lain
require_once "../config/function.php";

function uploadimg($url = null)
{
    $namafile = $_FILES['image']['name'];
    $ukuran = $_FILES['image']['size'];
    $tmp = $_FILES['image']['tmp_name'];

    //validasi file gambar yang boleh
    $eksetensiGambarValid = ['jpg', 'jpeg', 'png'];
    $eksetensiGambar = explode('.', $namafile);
    $eksetensiGambar = strtolower(end($eksetensiGambar));

    if (!in_array($eksetensiGambar, $eksetensiGambarValid)) {
        if ($url != null) {
            echo '<script>
            alert("File yang diupload bukan gambar , Data Gagal diupdate");
            document.location.href = "' . $url . '";
            </script>';
            die();
        } else {
            echo '<script>
        alert("File yang diupload bukan gambar, Data Gagal diupload");
        </script>';
            return false;
        }
    }

    //validasi ukuran gambar
    if ($ukuran > 1000000) {
        if ($url != null) {
            echo '<script>
            alert("ukuran gambar melebihi 1mb , Data Gagal diupdate");
            document.location.href = "' . $url . '";
            </script>';
            die();
        } else {
            echo '<script>
            alert("Ukuran gambar terlalu besar, Data Gagal diupload");
            </script>';
            return false;
        }
    }

    $namafileBaru = rand(10, 1000) . '-' . $namafile;

    move_uploaded_file($tmp, '../asset/image/' . $namafileBaru);
    return $namafileBaru;
}

function getData($sql)
{
    global $connection;
    $result = mysqli_query($connection, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}
