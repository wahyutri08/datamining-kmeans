<?php
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

if (isset($_POST["submit"])) {
    if (register($_POST) > 0) {
        echo "
        <script>
        alert('User Berhasil Ditambahkan');
        document.location.href = '../users'
        </script>";
    } else {
        echo mysqli_error($db);
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
    <title>Register - Data Mining</title>
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
                        <h4 class="text-themecolor">Add User</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
                                <li class="breadcrumb-item">Users Management</li>
                                <li class="breadcrumb-item active">Add User</li>
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
                                <h4 class="card-title">Tambah Akun</h4>
                                <h6 class="card-subtitle">Register</h6>
                                <form class="form-horizontal p-t-20" method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label for="username" class="col-sm-3 control-label">Username<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti-user"></i></span>
                                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nama" class="col-sm-3 control-label">Nama<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti-user"></i></span>
                                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-3 control-label">Email<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti-email"></i></span>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
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
                                        <label for="password2" class="col-sm-3 control-label">Re Password<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti-lock"></i></span>
                                                <input type="password" class="form-control" id="password2" name="password2" placeholder="Re Enter Password" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-month-input2" class="col-sm-3 col-form-label">Role<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <select class="form-select col-sm-9" id="role" name="role" required>
                                                <option selected disabled value="">Choose...</option>
                                                <option value="Admin">Admin</option>
                                                <option value="User">User</option>
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
                                            <button type="submit" name="submit" class="btn btn-success waves-effect waves-light text-white">Submit</button>
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
    <!-- <script>
        // $(document).ready(function() {
        //     // Basic
        //     $('.dropify').dropify();

        //     // Translated
        //     $('.dropify-fr').dropify({
        //         messages: {
        //             default: 'Glissez-déposez un fichier ici ou cliquez',
        //             replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
        //             remove: 'Supprimer',
        //             error: 'Désolé, le fichier trop volumineux'
        //         }
        //     });

        //     // Used events
        //     var drEvent = $('#input-file-events').dropify();

        //     drEvent.on('dropify.beforeClear', function(event, element) {
        //         return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        //     });

        //     drEvent.on('dropify.afterClear', function(event, element) {
        //         alert('File deleted');
        //     });

        //     drEvent.on('dropify.errors', function(event, element) {
        //         console.log('Has Errors');
        //     });

        //     var drDestroy = $('#input-file-to-destroy').dropify();
        //     drDestroy = drDestroy.data('dropify')
        //     $('#toggleDropify').on('click', function(e) {
        //         e.preventDefault();
        //         if (drDestroy.isDropified()) {
        //             drDestroy.destroy();
        //         } else {
        //             drDestroy.init();
        //         }
        //     })
        // });
        
    </script> -->
    <!-- <script>
        // Setelah halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen input file
            var avatarInput = document.getElementById('avatar');
            // Tentukan URL gambar default
            var defaultImageUrl = '../assets/images/users/1.jpg'; // Ganti dengan URL gambar default yang sesuai

            // Set nilai default pada input file
            avatarInput.addEventListener('change', function() {
                // Jika pengguna tidak memilih file, tetapkan nilai default
                if (!avatarInput.value) {
                    avatarInput.value = defaultImageUrl;
                }
            });
        });
    </script> -->
</body>

</html>