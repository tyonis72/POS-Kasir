<?php
// filepath: c:\xampp\htdocs\usk-kasir\auth\reset-password.php
session_start();
require "../config/config.php";

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($connection, $_GET['token']);

    // Periksa apakah token valid
    $query = "SELECT * FROM tbl_reset_password WHERE token = '$token'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];

        if (isset($_POST['reset_password'])) {
            $password = mysqli_real_escape_string($connection, $_POST['password']);
            $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);

            // Validasi password
            if ($password === $confirm_password) {


                // Update password di tabel tbl_user
                $query_update = "UPDATE tbl_user SET password = '$hashed_password' WHERE email = '$email'";
                if (mysqli_query($connection, $query_update)) {
                    // Hapus token setelah digunakan
                    $query_delete = "DELETE FROM tbl_reset_password WHERE token = '$token'";
                    mysqli_query($connection, $query_delete);

                    echo "<script>
                        alert('Password berhasil diubah! Silakan login.');
                        window.location.href = 'login.php';
                    </script>";
                } else {
                    echo "<script>alert('Gagal mengubah password.');</script>";
                }
            } else {
                echo "<script>alert('Password dan konfirmasi password tidak cocok!');</script>";
            }
        }
    } else {
        echo "<script>
            alert('Token tidak valid atau telah digunakan!');
            window.location.href = 'forgot-password.php';
        </script>";
        exit;
    }
} else {
    echo "<script>
        alert('Token tidak ditemukan!');
        window.location.href = 'forgot-password.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | KASIR 71</title>

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
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Kasir</b>71</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Enter your new password</p>

                <form action="" method="post">
                    <div class="input-group mb-4">
                        <input type="password" class="form-control" name="password" placeholder="New Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="submit" name="reset_password" class="btn btn-primary btn-block">Reset Password</button>
                    </div>
                </form>

                <p class="mb-0 text-center">
                    <a href="login.php">Back to Login</a>
                </p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>

</html>