<?php
// filepath: c:\xampp\htdocs\usk-kasir\auth\forgot-password.php
session_start();
require "../config/config.php";

// Pastikan Anda sudah menginstal PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/PHPMailer-6.10.0/src/Exception.php';
require '../vendor/PHPMailer-6.10.0/src/PHPMailer.php';
require '../vendor/PHPMailer-6.10.0/src/SMTP.php';

if (isset($_POST['reset'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);

    // Periksa apakah email ada di database
    $query = "SELECT * FROM tbl_user WHERE email = '$email'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) === 1) {
        // Generate token unik
        $token = bin2hex(random_bytes(32));

        // Simpan token ke tabel tbl_reset_password
        $query_insert = "INSERT INTO tbl_reset_password (email, token) VALUES ('$email', '$token')";
        mysqli_query($connection, $query_insert);

        // Kirim email reset password
        $reset_link = "http://localhost/usk-kasir/auth/reset-password.php?token=$token";

        // Konfigurasi PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io'; // Mailtrap SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = '05620977ed8d44'; // Ganti dengan username Mailtrap Anda
            $mail->Password = '0dc4615771a2cd'; // Ganti dengan password Mailtrap Anda
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('noreply@usk-kasir.com', 'Kasir 71');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password';
            $mail->Body = "Klik link berikut untuk mereset password Anda: <a href='$reset_link'>$reset_link</a>";

            $mail->send();
            echo "<script>
                alert('Link reset password telah dikirim ke email Anda.');
                window.location.href = 'login.php';
            </script>";
        } catch (Exception $e) {
            echo "<script>alert('Gagal mengirim email. Error: {$mail->ErrorInfo}');</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | KASIR 71</title>

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
                <p class="login-box-msg">Enter your email to reset your password</p>

                <form action="" method="post">
                    <div class="input-group mb-4">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="submit" name="reset" class="btn btn-primary btn-block">Send Reset Link</button>
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