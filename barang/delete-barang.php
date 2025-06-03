<?php
require '../config/config.php';
require '../modul/model-produk.php';

if (isset($_GET['id'])){
  if (delete($_GET['id']) > 0){
    echo "<script>
    alert('produk berhasil di hapus');
    document.location.href = 'daftar-barang.php';
    </script>";
  }
}