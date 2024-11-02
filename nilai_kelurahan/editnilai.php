<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

if (isset($_GET["id_kelurahan"]) && is_numeric($_GET["id_kelurahan"])) {
    $id_kelurahan = $_GET["id_kelurahan"];
} else {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}
$kelurahan = query("SELECT * FROM kelurahan WHERE id_kelurahan = $id_kelurahan");
if (empty($kelurahan)) {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}
$kelurahan = $kelurahan[0];

$atribut = query("SELECT * FROM atribut");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pengecekan apakah ada data yang diinput
    if (empty($_POST)) {
        echo json_encode(["status" => "error", "message" => "Tidak ada data yang diinput"]);
        exit;
    }

    // Memanggil fungsi jika ada data yang diinput
    dataPostKelurahan($_POST, $_GET);
    echo json_encode(["status" => "success", "message" => "Data Berhasil Diubah"]);
    exit;
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
    <title>Edit Nilai Kelurahan - Data Mining</title>
    <!-- Custom CSS -->
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="../assets/dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="skin-megna fixed-layout">
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
                        <h4 class="text-themecolor">Edit Nilai Kelurahan</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
                                <li class="breadcrumb-item">Nilai Data</li>
                                <li class="breadcrumb-item">Nilai Kelurahan</li>
                                <li class="breadcrumb-item">Edit Nilai Kelurahan</li>
                                <li class="breadcrumb-item active"><?= $kelurahan["nama_kelurahan"] ?></li>
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
                                <h4 class="card-title">Edit Nilai Kelurahan</h4>
                                <h6 class="card-subtitle"><?= $kelurahan["nama_kelurahan"]; ?></h6>
                                <form class="form-horizontal p-t-20" method="POST" action="" enctype="multipart/form-data" id="myForm">
                                    <input type="hidden" name="id_kelurahan" value="<?= $kelurahan["id_kelurahan"]; ?>">
                                    <?php foreach ($atribut as $row) : ?>
                                        <div class="form-group row">
                                            <label for="<?= $row["id_atribut"]; ?>" class="col-sm-3 control-label"><?= $row["nama_atribut"]; ?><span class="text-danger">*</span></label>
                                            <?php $nilaikelurahan = query("SELECT * FROM nilai_kelurahan WHERE id_kelurahan = " . $kelurahan['id_kelurahan'] . " AND id_atribut = " . $row['id_atribut']); ?>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <!-- <span class="input-group-text"></span> -->
                                                    <?php if ($nilaikelurahan) {
                                                        echo '<input type="number" class="form-control" id="' . $row["id_atribut"] . '" name="' . $row["id_atribut"] . '" placeholder="Nilai" value="' . $nilaikelurahan[0]["nilai"] . '">';
                                                    } else {
                                                        echo '<input type="number" class="form-control" id="' . $row["id_atribut"] . '" name="' . $row["id_atribut"] . '" placeholder="Nilai" value="">';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
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
    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            Swal.fire({
                                title: 'Success',
                                text: res.message,
                                type: 'success'
                            }).then(() => {
                                window.location.href = '../nilai_kelurahan';
                            });
                        } else {
                            if (res.message === 'Tidak ada data yang diinput') {
                                Swal.fire('Error', res.message, 'error');
                            } else {
                                Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                            }
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                    }
                });
            });
        });
    </script>

</body>

</html>