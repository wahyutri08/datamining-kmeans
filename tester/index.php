<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"])) {
    header("Location:../login");
    exit;
}
// include_once '../functions.php';
// include_once '../kmeans.php';

// Ambil data kelurahan dan atribut
$kelurahan = query("SELECT * FROM kelurahan");
$atribut = query("SELECT * FROM atribut");
$cluster = query("SELECT * FROM cluster");

if (!$kelurahan || !$atribut || !$cluster) {
    die("Error fetching data from database.");
}

// Ambil nilai kelurahan untuk setiap atribut
$data = [];
foreach ($kelurahan as $kel) {
    $row = [];
    foreach ($atribut as $attr) {
        $nilai = query("SELECT nilai FROM nilai_kelurahan WHERE id_kelurahan = " . $kel['id_kelurahan'] . " AND id_atribut = " . $attr['id_atribut']);
        if ($nilai) {
            $row[] = $nilai[0]['nilai'];
        } else {
            $row[] = 0; // Handle missing values appropriately
        }
    }
    $data[] = $row;
}

// Default nilai K dan iterasi
$initialCentroids = [];
foreach ($cluster as $cls) {
    $row = [];
    foreach ($atribut as $attr) {
        $nilai = query("SELECT nilai FROM nilai_cluster WHERE id_cluster = " . $cls['id_cluster'] . " AND id_atribut = " . $attr['id_atribut']);
        if ($nilai) {
            $row[] = $nilai[0]['nilai'];
        } else {
            $row[] = 0; // Handle missing values appropriately
        }
    }
    $initialCentroids[] = $row;
}

// $maxIterations = 1000;

// Default nilai K dan iterasi
$defaultIterations = 1000;
$maxIterations = isset($_POST['iterasi']) ? intval($_POST['iterasi']) : $defaultIterations;

if (isset($_POST['iterasi'])) {
    $maxIterations = intval($_POST['iterasi']);
}

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $maxIterations = intval($_POST['iterasi']);
// }


// Jalankan algoritma K-Means
$result = kmeans($data, $initialCentroids, $maxIterations);
$centroids = $result['centroids'];
$clusters = $result['clusters'];
$history = $result['history'];
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
    <title>Proses Perhitungan - Data Mining</title>
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
                        <h4 class="text-themecolor">Proses Perhitungan</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
                                <li class="breadcrumb-item">Proses Perhitungan</li>
                                <li class="breadcrumb-item active">Iterasi</li>
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
                                        <h4 class="card-title">Proses Perhitungan</h4>
                                        <form class="mt-4" method="POST" action="">
                                            <div class="form-group col-sm-3">
                                                <label for="iterasi" class="form-label">Masukkan Iterasi</label>
                                                <input type="number" name="iterasi" class="form-control" id="iterasi" placeholder="Jumlah Iterasi" required>
                                            </div>
                                            <button type="submit" name="submit" class="btn waves-effect waves-light btn-rounded btn-primary">Proses</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Hasil Clustering -->
                            <?php if (isset($_POST['submit'])) : ?>
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Hasil Clustering</h4>
                                            <?php
                                            // Mengurutkan array clusters berdasarkan kunci (clusterId)
                                            ksort($clusters);

                                            foreach ($clusters as $clusterId => $clusterData) :
                                            ?>
                                                <h5>Cluster <?= $clusterId + 1 ?></h5>
                                                <ul>
                                                    <?php foreach ($clusterData as $dataIndex) : ?>
                                                        <li><?= $kelurahan[$dataIndex]['nama_kelurahan'] ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Proses Iterasi -->
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Proses Iterasi</h4>
                                            <?php foreach ($history as $iteration) : ?>
                                                <h5>Iterasi <?= $iteration['iteration'] ?></h5>
                                                <h6>Centroids</h6>
                                                <ul>
                                                    <?php foreach ($iteration['centroids'] as $centroid) : ?>
                                                        <li><?= implode(', ', $centroid) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                                <h6>Clusters</h6>
                                                <?php
                                                ksort($iteration['clusters']);
                                                foreach ($iteration['clusters'] as $clusterId => $clusterData) : ?>
                                                    <strong>Cluster <?= $clusterId + 1 ?></strong>
                                                    <ul>
                                                        <?php foreach ($clusterData as $dataIndex) : ?>
                                                            <li><?= $kelurahan[$dataIndex]['nama_kelurahan'] ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div> <!-- /.row -->
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