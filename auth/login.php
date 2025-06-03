<?php
session_start();
require "../config/config.php";

if(isset($_SESSION['login']) && $_SESSION['login'] == true) {
  header("Location: ../dashboard.php");
  exit();
}

if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($connection, $_POST['username']);
  $password = mysqli_real_escape_string($connection, $_POST['password']);

  $querylogin = mysqli_query($connection, "SELECT * FROM tbl_user WHERE username = '$username'");

  if (mysqli_num_rows($querylogin) === 1) {
    $row = mysqli_fetch_assoc($querylogin);

    if ($row['status'] == 'online') {
      echo "<script>
      alert('Akun sudah login di perangkat lain');
      </script>";

      header("Location: login.php"); // Redirect ke halaman login
      exit();
    }

    if ($row['password'] == $password) {

      // update status di datbase
      $update = mysqli_query($connection, "UPDATE tbl_user SET status = 'online' WHERE userid = '" . $row['userid'] . "'");
      if ($update) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['level'] = $row['level'];
        $_SESSION['user_id'] = $row['userid'];
        header("Location: ../dashboard.php");
        exit();
      }
    } else {
      echo "<script>
      alert('Password Salah');
      </script>";
    }
  } else {
    echo "<script>
    alert('Username Salah');
    </script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LOGIN | KASIR 71</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/dist/css/adminlte.min.css">
  <!-- Favicon -->
  <link rel="shorcut icon" href="<?= $main_url ?>asset/image/cart.png" type="image/x-icon">
  <link rel="stylesheet" href="style.css">

</head>

<body class="hold-transition login-page">
  <div class="login-box slide-down" style="margin-top: -70px;">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="#" class="h1"><b>Kasir</b>71</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="" method="post">
          <div class="input-group mb-4">
            <input type="text" class="form-control" name="username" placeholder="Username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-4">
            <input type="password" class="form-control" name="password" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>

          <div class="mb-4">
            <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <div class="text-center">
            <a href="forgot-password.php" class="d-block mb-2">Forgot your password?</a>
          </div>
          <!-- /.col -->
        </form>


        <p class="my-3 text-center">
          <strong>Copyright &copy; 2023 <span class="text-info">Danish Raihan</span></strong>
        </p>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>

</html>