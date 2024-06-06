<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"])) {
    header("Location:../login");
    exit;
}


// // Simpan hasil ke database
// if (isset($_POST['save_report'])) {
//     $userId = $_SESSION['id']; // Asumsikan user ID disimpan di session
//     $reportDate = date('Y-m-d H:i:s');

//     // Simpan metadata laporan
//     $insertReport = "INSERT INTO laporan (user_id, tanggal_laporan) VALUES ($userId, '$reportDate')";
//     if (mysqli_query($db, $insertReport)) {
//         $reportId = mysqli_insert_id($db);

//         // Simpan centroid awal
//         foreach ($initialCentroids as $index => $centroid) {
//             $values = implode(',', array_map('floatval', $centroid));
//             mysqli_query($db, "INSERT INTO report_initial_centroids (report_id, cluster_id, centroid_values) VALUES ($reportId, $index + 1, '$values')");
//         }

//         // Simpan riwayat clustering
//         foreach ($history as $iteration) {
//             $iterationNumber = $iteration['iteration'];
//             foreach ($iteration['centroids'] as $index => $centroid) {
//                 $values = implode(',', array_map('floatval', $centroid));
//                 mysqli_query($db, "INSERT INTO report_history (report_id, iteration, cluster_id, history_values) VALUES ($reportId, $iterationNumber, $index + 1, '$values')");
//             }
//         }
//     } else {
//         echo "Gagal menyimpan laporan: " . mysqli_error($db);
//     }
// }


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Laporan Hasil Proses Perhitungan - Data Mining</title>
    <link href="../assets/dist/css/style.min.css" rel="stylesheet">
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" />
</head>

<body class="skin-red-dark fixed-layout">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Data Mining</p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php require_once '../partials/header.php'; ?>
        <?php require_once '../partials/sidebar.php'; ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Laporan Hasil Perhitungan</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
                                <li class="breadcrumb-item">Laporan Hasil Perhitungan</li>
                                <li class="breadcrumb-item active">Laporan</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Laporan Hasil Perhitungan </h4>
                                        <h6 class="card-subtitle">Data Laporan</h6>
                                        <div class="table-responsive">
                                            <table class="table color-table red-table">
                                                <thead>
                                                    <tr>
                                                        <th>ID Laporan</th>
                                                        <th>Nama User</th>
                                                        <th>Role</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <!-- <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td><a href=""></a></td>
                                                        <td>
                                                            <div class="label label-table label-success"></div>
                                                        </td>
                                                        <td><span class="text-muted"><i class="fa fa-clock-o"></i></span> </td>
                                                        <td><button class="btn btn-primary btn-sm"></button></td>
                                                    </tr>
                                                </tbody> -->
                                                <tbody>
                                                    <?php
                                                    $reports = query("SELECT laporan.id, users.nama, users.role, laporan.tanggal_laporan FROM laporan JOIN users ON laporan.user_id = users.id");
                                                    foreach ($reports as $report) :
                                                    ?>
                                                        <tr>
                                                            <td><?= $report['id'] ?></td>
                                                            <td><a href="view_report.php?id=<?= $report['id'] ?>"><?= $report['nama'] ?></a></td>
                                                            <td>
                                                                <?php
                                                                if ($report['role'] == 'Admin') {
                                                                    echo '<div class="label label-table label-success">' . $report['role'] . '</div>';
                                                                } else {
                                                                    echo '<div class="label label-table label-info">' . $report['role'] . '</div>';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><span class="text-muted"><i class="fa fa-clock-o"></i> <?= $report['tanggal_laporan'] ?></span></td>
                                                            <td><a href="view_report.php?id=<?= $report['id'] ?>" class="btn btn-primary btn-sm">Lihat Laporan</a></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
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
    <script src="../assets/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/dist/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/dist/js/waves.js"></script>
    <script src="../assets/dist/js/sidebarmenu.js"></script>
    <script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <script src="../assets/dist/js/custom.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
</body>

</html>