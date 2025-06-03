<?php

function getMembers() {
    global $connection;
    $query = "SELECT * FROM tbl_member";
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

    $nama_member = mysqli_real_escape_string($connection, $data["nama_member"]);
    $no_hp = mysqli_real_escape_string($connection, $data["no_hp"]);
    $alamat = mysqli_real_escape_string($connection, $data["alamat"]);

    $query = "INSERT INTO tbl_member (nama_member, no_hp, alamat) 
              VALUES ('$nama_member', '$no_hp', '$alamat')";

    mysqli_query($connection, $query);

    return mysqli_affected_rows($connection);
}

function deleteMember($id)
{
    global $connection;
    mysqli_query($connection, "DELETE FROM tbl_member WHERE member_id = $id");
    return mysqli_affected_rows($connection);
}

function updateMember($data)
{
    global $connection;

    $member_id = mysqli_real_escape_string($connection, $data["member_id"]);
    $nama_member = mysqli_real_escape_string($connection, $data["nama_member"]);
    $no_hp = mysqli_real_escape_string($connection, $data["no_hp"]);
    $alamat = mysqli_real_escape_string($connection, $data["alamat"]);

    $query = "UPDATE tbl_member 
              SET nama_member = '$nama_member', 
                  no_hp = '$no_hp', 
                  alamat = '$alamat'
              WHERE member_id = $member_id";

    mysqli_query($connection, $query);

    return mysqli_affected_rows($connection);
}