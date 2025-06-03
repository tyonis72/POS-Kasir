<?php

require "config/config.php";

$title = "Dashboard";
require "template/header.php";
require "template/navbar.php";
require "template/sidebar.php";

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-primary">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-gradient-info shadow-lg">
            <div class="inner">
              <?php
              $query = "SELECT COUNT(*) AS total_member FROM tbl_member";
              $result = mysqli_query($connection, $query);
              $data = mysqli_fetch_assoc($result);
              ?>
              <h3><?= $data['total_member'] ?></h3>
              <p>Member</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
            <a href="../member/daftar-member.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-gradient-success shadow-lg">
            <div class="inner">
              <?php
              $query = "SELECT COUNT(*) AS total_kategori FROM tbl_kategori";
              $result = mysqli_query($connection, $query);
              $data = mysqli_fetch_assoc($result);
              ?>
              <h3><?= $data['total_kategori'] ?></h3>
              <p>Kategori</p>
            </div>
            <div class="icon">
              <i class="fas fa-truck"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-gradient-danger shadow-lg">
            <div class="inner">
              <?php
              $query = "SELECT COUNT(*) AS total_barang FROM tbl_produk";
              $result = mysqli_query($connection, $query);
              $data = mysqli_fetch_assoc($result);
              ?>
              <h3><?= $data['total_barang'] ?></h3>
              <p>Item Barang</p>
            </div>
            <div class="icon">
              <i class="fas fa-boxes"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- Grafik Penjualan dengan Filter Bulan & Tanggal (Hanya Satu Grafik) -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
              <h5 class="card-title mb-0">Grafik Penjualan</h5>
              <label class="mb-0">Tanggal:</label>
              <input type="date" name="tanggal" value="<?= isset($_GET['tanggal']) ? htmlspecialchars($_GET['tanggal']) : '' ?>" class="form-control form-control-sm" style="width: 150px;">
              <button type="submit" class="btn btn-light btn-sm">Tampilkan</button>
              </form>
            </div>
            <div class="card-body">
              <canvas id="salesChart" height="100"></canvas>
            </div>
          </div>
        </div>
      </div>
      <?php
      // Ambil data penjualan berdasarkan filter bulan/tanggal
      $salesData = [];
      $salesLabels = [];
      $where = [];
      if (!empty($_GET['bulan'])) {
        $bulan = mysqli_real_escape_string($connection, $_GET['bulan']);
        $where[] = "DATE_FORMAT(tanggal, '%Y-%m') = '$bulan'";
      }
      if (!empty($_GET['tanggal'])) {
        $tanggal = mysqli_real_escape_string($connection, $_GET['tanggal']);
        $where[] = "DATE(tanggal) = '$tanggal'";
      }
      if (!empty($where)) {
        $whereSql = 'WHERE ' . implode(' AND ', $where);
        $query = "SELECT DATE(tanggal) AS tgl, SUM(total_harga) AS total FROM tbl_transaksi $whereSql GROUP BY tgl ORDER BY tgl ASC";
      } else {
        $query = "SELECT DATE_FORMAT(tanggal, '%Y-%m') AS tgl, SUM(total_harga) AS total FROM tbl_transaksi GROUP BY tgl ORDER BY tgl ASC LIMIT 12";
      }
      $result = mysqli_query($connection, $query);
      while ($row = mysqli_fetch_assoc($result)) {
        $salesLabels[] = (!empty($_GET['bulan']) || !empty($_GET['tanggal'])) ? date('d M Y', strtotime($row['tgl'])) : date('M Y', strtotime($row['tgl'] . '-01'));
        $salesData[] = (int)$row['total'];
      }
      ?>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
        // Hapus chart sebelumnya jika ada
        if (window.salesChartObj) {
          window.salesChartObj.destroy();
        }
        const ctx = document.getElementById('salesChart').getContext('2d');
        window.salesChartObj = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: <?= json_encode($salesLabels) ?>,
            datasets: [{
              label: 'Total Penjualan',
              data: <?= json_encode($salesData) ?>,
              backgroundColor: 'rgba(54, 162, 235, 0.7)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                display: false
              },
              title: {
                display: false
              }
            },
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      </script>
    </div>
  </div>
  <!-- /.content -->

  <?php
  require "template/footer.php";
  ?>