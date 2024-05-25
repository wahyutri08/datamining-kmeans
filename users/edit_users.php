<?php
// session_start();
// if (!isset($_SESSION["login"])) {
//     header("Location:../login");
//     exit;
// }
// require_once '../functions.php';

// $id = $_GET["id"];
// $users = query("SELECT * FROM users WHERE id = $id")[0];

// if (isset($_POST["submit"])) {
//     if (editUsers($_POST) > 0) {
//         echo "
//         <script>
//         alert('Data Berhasil Diubah');
//         document.location.href = '../users'
//         </script>";
//     } else {
//         echo "
//         <script>
//         alert('Data Gagal Diubah');
//         document.location.href = '../users';
//         </script>";
//     }
// }

session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"])) {
    header("Location:../login");
    exit;
}

if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../dashboard");
    exit;
}
// require_once '../functions.php';

$id = $_GET["id"];
$users = query("SELECT * FROM users WHERE id = $id")[0];

if (isset($_POST["submit"])) {
    // Ambil nilai yang dikirimkan untuk username baru
    $newUsername = $_POST["username"];

    // Lakukan pemeriksaan dengan database
    $query = "SELECT username FROM users WHERE username = '$newUsername'";
    $result = mysqli_query($db, $query);

    // Jika username yang dikirim sudah ada di database selain username saat ini, tampilkan pesan kesalahan
    if (mysqli_num_rows($result) > 0 && $newUsername !== $users["username"]) {
        echo "
        <script>
        alert('Username sudah ada. Silakan pilih username lain.');
        </script>";
    } else {
        // Lanjutkan dengan pembaruan data jika tidak ada masalah
        if (editUsers($_POST) > 0) {

            // Update session data dengan data baru
            $_SESSION['user_data']['username'] = $_POST['username'];
            $_SESSION['user_data']['nama'] = $_POST['nama'];
            $_SESSION['user_data']['email'] = $_POST['email'];
            $_SESSION['user_data']['role'] = $_POST['role'];

            echo "
            <script>
            alert('Data Berhasil Diubah');
            document.location.href = '../users'
            </script>";
        } else {
            echo "
            <script>
            alert('Data Gagal Diubah');
            document.location.href = '../users';
            </script>";
        }
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
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Edit Users - Data Mining</title>
    <!-- Custom CSS -->
    <link href="../assets/dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="skin-red-dark fixed-layout">
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
    <div id="main-wrapper">
        <!-- Topbar header - style you can find in pages.scss -->
        <?php require_once '../partials/header.php'; ?>
        <!-- End Topbar header -->

        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <?php require_once '../partials/sidebar.php'; ?>
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->

        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Edit User</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
                                <li class="breadcrumb-item">Edit User</li>
                                <li class="breadcrumb-item active"><?= $users["nama"]; ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Edit Users</h4>
                                <h6 class="card-subtitle"><?= $users["nama"]; ?></h6>
                                <form class="form-horizontal p-t-20" method="POST" action="" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $users["id"]; ?>">
                                    <input type="hidden" name="avatarLama" value="<?= $users["avatar"]; ?>">
                                    <div class="form-group row">
                                        <label for="username" class="col-sm-3 control-label">Username<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti-user"></i></span>
                                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?= $users["username"]; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nama" class="col-sm-3 control-label">Nama<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti-user"></i></span>
                                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="<?= $users["nama"]; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-3 control-label">Email<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti-email"></i></span>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?= $users["email"]; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-3 control-label">Password<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti-lock"></i></span>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-month-input2" class="col-sm-3 col-form-label">Role<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <select class="form-select col-sm-9" id="role" name="role" required>
                                                <option value="Admin" <?= ($users["role"] == "Admin") ? "selected" : "" ?>>Admin</option>
                                                <option value="User" <?= ($users["role"] == "User") ? "selected" : "" ?>>User</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="avatar" class="col-sm-3 control-label">Photo Profile</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti-file"></i></span>
                                                <input type="file" class="form-control" id="avatar" name="avatar" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row m-b-0">
                                        <div class="offset-sm-3 col-sm-9">
                                            <button type="submit" name="submit" class="btn btn-success waves-effect waves-light text-white">Submit Change</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <?php require_once '../partials/footer.php'; ?>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../assets/dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="../assets/dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../assets/dist/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="../assets/dist/js/custom.min.js"></script>
    <!-- jQuery file upload -->
    <script src="../assets/node_modules/dropify/dist/js/dropify.min.js"></script>
</body>

</html>