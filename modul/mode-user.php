<?php

function insert($data)
{

    global $connection;

    $username = strtolower(mysqli_real_escape_string($connection, $data["username"]));
    $fullname = mysqli_real_escape_string($connection, $data["fullname"]);
    $password = mysqli_real_escape_string($connection, $data["password"]);
    $password2 = mysqli_real_escape_string($connection, $data["password2"]);
    $level = mysqli_real_escape_string($connection, $data["level"]);
    $address = mysqli_real_escape_string($connection, $data["address"]);
    $gambar = mysqli_real_escape_string($connection, $_FILES["image"]["name"]);
    $email = mysqli_real_escape_string($connection, $data["email"]);

    if ($password !== $password2) {
        echo "<script>
        alert('Password tidak sesuai');
        </script>";
        return false;
    }

    $pass = $password;

    $cekusername = mysqli_query($connection, "SELECT username FROM tbl_user WHERE username = '$username'");
    if (mysqli_num_rows($cekusername) > 0) {
        echo "<script>
        alert('Username sudah terdaftar');
        </script>";
        return false;
    }

    if ($gambar) {
        $gambar = uploadimg();
    } else {
        $gambar = 'default.jpg';
    }

    //gambar tidak seusai validasi
    if (!$gambar) {
        return false;
    }

    $sqluser = "INSERT INTO tbl_user (username, fullname, password, addres, level, foto, email) 
                VALUES ('$username', '$fullname', '$pass', '$address', '$level', '$gambar', '$email')";
    mysqli_query($connection, $sqluser);

    return mysqli_affected_rows($connection);
}

function delete($id)
{
    global $connection;
    // Cek apakah user masih punya transaksi
    $cekTransaksi = mysqli_query($connection, "SELECT 1 FROM tbl_transaksi WHERE user_id = $id LIMIT 1");
    if (mysqli_num_rows($cekTransaksi) > 0) {
        echo "<script>alert('User tidak bisa dihapus karena masih memiliki transaksi!');window.location.href='../user/daftar-user.php';</script>";
        return false;
    }
    mysqli_query($connection, "DELETE FROM tbl_user WHERE userid = $id");
    return mysqli_affected_rows($connection);
}



function selectuser1($level)
{
    $result = null;
    if ($level == '1') {
        $result = 'selected';
    }
    return $result;
}

function selectuser2($level)
{
    $result = null;
    if ($level == '2') {
        $result = 'selected';
    }
    return $result;
}

function selectuser3($level)
{
    $result = null;
    if ($level == '3') {
        $result = 'selected';
    }
    return $result;
}

function update($data)
{

    global $connection;

    $iduser =   mysqli_real_escape_string($connection, $data["id"]);
    $username = strtolower(mysqli_real_escape_string($connection, $data["username"]));
    $fullname = mysqli_real_escape_string($connection, $data["fullname"]);
    $level = mysqli_real_escape_string($connection, $data["level"]);
    $address = mysqli_real_escape_string($connection, $data["address"]);
    $gambar = mysqli_real_escape_string($connection, $_FILES["image"]["name"]);
    $fotolama = mysqli_real_escape_string($connection, $data["oldImg"]);

    //cek username sekarang
    $queryUsername = mysqli_query($connection, "SELECT username FROM tbl_user WHERE userid = $iduser");
    $dataUsername = mysqli_fetch_assoc($queryUsername);
    $curUsername = $dataUsername['username'];

    //cek username baru
    $newUsername = mysqli_query($connection, "SELECT username FROM tbl_user WHERE username = '$username'");
    if ($username !== $curUsername) {
        if (mysqli_num_rows($newUsername) > 0) {
            echo "<script>
            alert('Username sudah terdaftar');
            </script>";
            return false;
        }
    }

    //cek gambar
    if ($gambar != null) {
        $url = "data-user.php";
        $imgUser = uploadimg($url);
        if ($fotolama != 'default.png') {
            @unlink('../asset/image' . $fotolama);
        }
    } else {
        $imgUser = $fotolama;
    }

    mysqli_query($connection, "UPDATE tbl_user SET username = '$username', fullname = '$fullname', addres = '$address', level = '$level', foto = '$imgUser' WHERE userid = $iduser");

    return mysqli_affected_rows($connection);
}
