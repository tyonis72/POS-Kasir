<?php
require '../config/config.php';
require '../modul/mode-user.php';

if (isset($_GET['id'])){
  if (delete($_GET['id']) > 0){
    echo "<script>
    alert('user berhasil di hapus');
    document.location.href = 'daftar-user.php';
    </script>";
  }
}