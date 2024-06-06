<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"])) {
    header("Location:../login");
    exit;
}

$id_laporan = $_GET['id'];

// Ambil Data Lpaoran
$laporan = query("SELECT laporan.id, users.nama, users.role, laporan.tanggal_laporan FROM laporan JOIN users ON laporan.user_id = users.id WHERE laporan.id = $id_laporan");
if (empty($laporan)) {
    die("Laporan tidak ditemukan.");
}

$hasil_akhir = query("SELECT 
                laporan_hasil_akhir.id, 
                laporan_hasil_akhir.nama_kelurahan, 
                laporan_hasil_akhir.nama_cluster, 
                laporan_hasil_akhir_atribut.nama_atribut, 
                laporan_hasil_akhir_atribut.nilai
                FROM laporan_hasil_akhir 
                JOIN laporan_hasil_akhir_atribut 
                ON laporan_hasil_akhir.id = laporan_hasil_akhir_atribut.id_laporan_hasil_akhir
                WHERE laporan_hasil_akhir.id_laporan = '$id_laporan'
                 ");

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
                                        <h4 class="card-title">Detail Laporan</h4>
                                        <p class="card-subtitle">ID Laporan : <?= $id_laporan ?></p>
                                        <p class="card-subtitle">Nama User : <?= $laporan[0]['nama'] ?></p>
                                        <p class="card-subtitle">Role : <?= $laporan[0]['role'] ?></p>
                                        <p class="card-subtitle">Tanggal Laporan : <?= $laporan[0]['tanggal_laporan'] ?></p>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Kelurahan</th>
                                                        <?php
                                                        // Ambil atribut unik
                                                        $atribut_unik = [];
                                                        foreach ($hasil_akhir as $atribut) {
                                                            if (!in_array($atribut['nama_atribut'], $atribut_unik)) {
                                                                $atribut_unik[] = $atribut['nama_atribut'];
                                                            }
                                                        }
                                                        foreach ($atribut_unik as $nama_atribut) : ?>
                                                            <th><?= $nama_atribut; ?></th>
                                                        <?php endforeach; ?>
                                                        <th>Cluster</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Organize data by kelurahan
                                                    $data_by_kelurahan = [];
                                                    foreach ($hasil_akhir as $data) {
                                                        $data_by_kelurahan[$data['nama_kelurahan']]['cluster'] = $data['nama_cluster'];
                                                        $data_by_kelurahan[$data['nama_kelurahan']]['atribut'][$data['nama_atribut']] = $data['nilai'];
                                                    }

                                                    // Display data
                                                    foreach ($data_by_kelurahan as $kelurahan => $data) : ?>
                                                        <tr>
                                                            <td><?= $kelurahan; ?></td>
                                                            <?php foreach ($atribut_unik as $atribut) : ?>
                                                                <td><?= $data['atribut'][$atribut] ?? '-'; ?></td>
                                                            <?php endforeach; ?>
                                                            <td><?= $data['cluster']; ?></td>
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