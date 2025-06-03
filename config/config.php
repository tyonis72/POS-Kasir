<?php

date_default_timezone_set('asia/jakarta');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbnmae = 'kaisr';

$connection = mysqli_connect($host, $user, $pass, $dbnmae);

// if (mysqli_connect_errno()) {
//       echo "Koneksi database gagal";
//     exit();
//  }else{
//       echo "Koneksi database berhasil";
// }

$main_url = 'http://localhost/usk-kasir/';

?>