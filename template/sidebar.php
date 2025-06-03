<?php
// Ambil data user dari database berdasarkan sesi login
session_start(); // Pastikan file konfigurasi database sudah di-include

// Misalnya, session menyimpan username pengguna
$username = $_SESSION['username'] ?? null;

if ($username) {
  $query = "SELECT fullname, foto FROM tbl_user WHERE username = ?";
  $stmt = $connection->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
}
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(45deg, #6a11cb, #2575fc);">
  <!-- Brand Logo -->
  <a href="<?= $main_url ?>dashboard.php" class="brand-link text-center">
    <img src="<?= $main_url ?>asset/image/logo.png" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .9; width: 50px;">
    <span class="brand-text font-weight-bold text-white">KASIR.ID</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <?php if (!empty($user['foto'])): ?>
          <img src="<?= $main_url ?>asset/image/<?= htmlspecialchars($user['foto']); ?>" class="img-circle elevation-2" alt="User Image">
        <?php else: ?>
          <img src="<?= $main_url ?>dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="Default User Image">
        <?php endif; ?>
      </div>
      <div class="info">
        <a href="#" class="d-block text-white font-weight-bold"><?= htmlspecialchars($user['fullname'] ?? 'Admin'); ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="<?= $main_url ?>dashboard.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt text-white"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-folder text-white"></i>
            <p>
              Master
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= $main_url ?>barang/daftar-barang.php" class="nav-link">
                <i class="far fa-circle nav-icon text-white"></i>
                <p>Barang</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $main_url ?>kategori/daftar-kategori.php" class="nav-link">
                <i class="far fa-circle nav-icon text-white"></i>
                <p>Kategori</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $main_url ?>member/daftar-member.php" class="nav-link">
                <i class="far fa-circle nav-icon text-white"></i>
                <p>Member</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $main_url ?>diskon/daftar-diskon.php" class="nav-link">
                <i class="far fa-circle nav-icon text-white"></i>
                <p>Diskon</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header text-white">Transaksi</li>
        <li class="nav-item">
          <a href="<?= $main_url ?>transaksi/daftar-transaksi.php" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart text-white"></i>
            <p>
              Transaksi
            </p>
          </a>
        </li>
        <li class="nav-header text-white">Pengaturan</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cog text-white"></i>
            <p>
              Pengaturan
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= $main_url ?>user/daftar-user.php" class="nav-link">
                <i class="far fa-circle nav-icon text-white"></i>
                <p>User</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>