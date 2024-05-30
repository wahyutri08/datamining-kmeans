<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"])) {
    header("Location:../login");
    exit;
}
// require_once '../functions.php';
$atr = query("SELECT * FROM atribut");

if (isset($_POST["search"])) {
    $atr = searchAtribut($_POST["keyword"]);
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
    <title>Data Atribut - Data Mining</title>
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
                        <h4 class="text-themecolor">Data Atribut</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
                                <li class="breadcrumb-item">Master data</li>
                                <li class="breadcrumb-item active">Data Atribut</li>
                            </ol>
                            <a href="add_atribut.php"><button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Tambah Atribut</button></a>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Data Atribut</h4>
                                        <form action="" method="POST">
                                            <div id="example23_filter" class="dataTables_filter">
                                                <label>Search :<input type="text" name="keyword" id="keyword" class="form-control-sm mb-4" placeholder="Keyword" aria-controls="example23"></label>
                                                <button type="submit" name="search" id="tombol-cari">Cari!</button>
                                            </div>
                                        </form>
                                        <div class="float-start">
                                            <a href="cetak.php" class="btn waves-effect waves-light btn-rounded btn-outline-danger"><i class="far fa-file-pdf"></i> Cetak PDF</a>
                                        </div>
                                        <!-- <h6 class="card-subtitle">Add class <code>.color-bordered-table .red-bordered-table</code></h6> -->
                                        <div class="table-responsive">
                                            <table class="table color-table red-table">
                                                <thead>
                                                    <tr>
                                                        <th>ID Atribut</th>
                                                        <th>Nama Atribut</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <?php foreach ($atr as $atribut) : ?>
                                                    <tbody>
                                                        <tr>
                                                            <td><?= $atribut["id_atribut"]; ?></td>
                                                            <td><?= $atribut["nama_atribut"]; ?></td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        Action
                                                                    </button>
                                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                        <li><a class="dropdown-item" href="edit_atribut.php?id_atribut=<?= $atribut["id_atribut"]; ?>">Edit</a></li>
                                                                        <li><a class="dropdown-item" href="delete_atribut.php?id_atribut=<?= $atribut["id_atribut"]; ?>" onclick="return confirm('Yakin ?');">Delete</a></li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                <?php endforeach; ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
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
    <script>
        $(document).ready(function() {
            // hilangkan tombol cari
            $('#tombol-cari').hide();

            // event ketika keyword ditulis
            $('#keyword').on('keyup', function() {
                // munculkan icon loading
                // $('.loader').show();

                // ajax menggunakan load
                // $('#container').load('ajax/mahasiswa.php?keyword=' + $('#keyword').val());

                // $.get()
                $.get('../ajax/data_atribut.php?keyword=' + $('#keyword').val(), function(data) {

                    $('.table-responsive').html(data);
                    // $('.loader').hide();

                });

            });

        });
    </script>
</body>

</html>