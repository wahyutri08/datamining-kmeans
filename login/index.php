<?php
session_start();
require_once '../functions.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Cegah akses ke halaman ini jika pengguna sudah login
if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: ../dashboard");
    exit;
}

$error = '';

if (isset($_POST["login"])) {
    $usernameOremail = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$usernameOremail' OR email = '$usernameOremail'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['avatar'] = $row['avatar'];
            $_SESSION['role'] = $row['role'];
            header("Location: ../dashboard");
            exit;
        } else {
            $error = 'Password Salah.';
        }
    } else {
        $error = 'Username Atau Email Tidak Ditemukan.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/logo/logo_mms.jpeg">
    <title>Login</title>

    <!-- page css -->
    <link href="../assets/dist/css/pages/login-register-lock.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/dist/css/style.min.css" rel="stylesheet">
    <!--  -->
    <link href="../assets/dist/css/costum.css" rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="skin-default card-no-border" id="bg">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Data Mining</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper">
        <div class="login-register">
            <div class="login-box card">
                <div class="card-body">
                    <?php if ($error) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>
                    <form class="form-horizontal" id="loginform" action="" method="POST">
                        <div class="text-center">
                            <a href="../login" class="db"><img src="../assets/images/logo/logo-mms-removebg (1).png" style="width: 260px; height: 55px" alt="Home" /><br /></a>
                        </div>
                        <h3 class="text-center m-t-10 m-b-20">Log In</h3>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username atau Email" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required="" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <button class="btn btn-secondary btn-info text-white" name="login" type="submit"><i class="fas fa-sign-in-alt"></i> Log In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--Custom JavaScript -->
    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip()
        });
        // ============================================================== 
        // Login and Recover Password 
        // ============================================================== 
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });

        // Cegah pengguna kembali ke halaman login dengan tombol back browser
        window.history.forward();

        function noBack() {
            window.history.forward();
        }
        window.onpageshow = function(evt) {
            if (evt.persisted) noBack();
        }
    </script>

</body>

</html>