<?php
// filepath: c:\xampp\htdocs\usk-kasir\auth\register.php
session_start();
require "../config/config.php";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $fullname = mysqli_real_escape_string($connection, $_POST['fullname']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $level = 3; // Default level untuk operator

    // Validasi password
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan Konfirmasi Password tidak cocok!');</script>";
    } else {
        // Hash password
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Periksa apakah username sudah ada
        $query_check = "SELECT * FROM tbl_user WHERE username = '$username'";
        $result_check = mysqli_query($connection, $query_check);

        if (mysqli_num_rows($result_check) > 0) {
            echo "<script>alert('Username sudah digunakan!');</script>";
        } else {
            // Simpan data ke database
            $query_insert = "INSERT INTO tbl_user (username, fullname, password, addres, level, email) 
                             VALUES ('$username', '$fullname', '$password', '$address', $level, '$email')";
            if (mysqli_query($connection, $query_insert)) {
                echo "<script>
                    alert('Registrasi berhasil! Silakan login.');
                    window.location.href = 'login.php';
                </script>";
            } else {
                echo "<script>alert('Registrasi gagal!');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>REGISTER | KASIR 71</title>

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
                <p class="login-box-msg">Register a new account</p>

                <form action="" method="post">
                    <div class="input-group mb-4">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user-circle"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
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
                    <div class="input-group mb-4">
                        <input type="text" class="form-control" name="address" placeholder="Address" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-map-marker-alt"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                    </div>
                </form>

                <p class="mb-0 text-center">
                    <a href="login.php">Already have an account? Login here</a>
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