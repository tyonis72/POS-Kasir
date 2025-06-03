<?php
require '../config/config.php';
require '../modul/model-kategori.php';

if (isset($_GET['id'])){
  if (delete($_GET['id']) > 0){
    echo "<script>
    alert('kategori berhasil di hapus');
    document.location.href = 'daftar-kategori.php';
    </script>";
  }
}