<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"])) {
    header("Location:../login");
    exit;
}

// Ambil data kelurahan dan atribut
$kelurahan = query("SELECT * FROM kelurahan ORDER BY id_kelurahan");
$atribut = query("SELECT * FROM atribut ORDER BY id_atribut");
$cluster = query("SELECT * FROM cluster ORDER BY id_cluster");

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

// Ambil nilai Cluster untuk setiap atribut dan iterasi
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

// Default nilai K dan iterasi
$defaultIterations = 1000;
$maxIterations = isset($_POST['iterasi']) ? intval($_POST['iterasi']) : $defaultIterations;

// // Fungsi untuk menghitung jarak Euclidean
// function calculateDistance($point1, $point2)
// {
//     $sum = 0;
//     foreach ($point1 as $i => $value) {
//         $sum += pow($value - $point2[$i], 2);
//     }
//     return sqrt($sum);
// }

// // Fungsi untuk menjalankan algoritma K-Means
// function kmeans($data, $initialCentroids, $maxIterations)
// {
//     $centroids = $initialCentroids;
//     $history = [];
//     for ($i = 0; $i < $maxIterations; $i++) {
//         $clusters = [];
//         $distances = [];
//         foreach ($data as $dataIndex => $dataPoint) {
//             $minDistance = PHP_INT_MAX;
//             $closestCentroid = -1;
//             foreach ($centroids as $centroidIndex => $centroid) {
//                 $distance = calculateDistance($dataPoint, $centroid);
//                 if ($distance < $minDistance) {
//                     $minDistance = $distance;
//                     $closestCentroid = $centroidIndex;
//                 }
//                 $distances[$dataIndex][$centroidIndex] = $distance;
//             }
//             $clusters[$closestCentroid][] = $dataIndex;
//         }
//         $newCentroids = [];
//         foreach ($clusters as $clusterIndex => $cluster) {
//             $newCentroid = array_fill(0, count($data[0]), 0);
//             foreach ($cluster as $dataIndex) {
//                 foreach ($data[$dataIndex] as $attributeIndex => $value) {
//                     $newCentroid[$attributeIndex] += $value;
//                 }
//             }
//             foreach ($newCentroid as $attributeIndex => $sum) {
//                 $newCentroid[$attributeIndex] /= count($cluster);
//             }
//             $newCentroids[$clusterIndex] = $newCentroid;
//         }
//         ksort($newCentroids); // Urutkan centroid berdasarkan kunci
//         ksort($clusters); // Urutkan cluster berdasarkan kunci
//         $history[] = [
//             'iteration' => $i + 1,
//             'centroids' => $newCentroids,
//             'clusters' => $clusters,
//             'distances' => $distances
//         ];
//         if ($newCentroids == $centroids) {
//             break;
//         }
//         $centroids = $newCentroids;
//     }
//     return [
//         'centroids' => $centroids,
//         'clusters' => $clusters,
//         'history' => $history,
//     ];
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
                                            <button type="submit" name="submit" class="btn waves-effect waves-light btn-rounded btn-primary"><i class="fas fa-cogs"></i> Proses</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Hasil Clustering -->
                            <?php if (isset($_POST['submit'])) : ?>
                                <!-- Proses Iterasi -->
                                <?php foreach ($history as $iteration) : ?>
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Proses Iterasi <?= $iteration['iteration']; ?></h4>
                                                <h6 class="card-subbtitle">Centroid</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama Cluster</th>
                                                                <?php foreach ($atribut as $atr) : ?>
                                                                    <th><?= $atr['nama_atribut']; ?></th>
                                                                <?php endforeach; ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($iteration['centroids'] as $index => $centroid) : ?>
                                                                <tr>
                                                                    <td><?= $cluster[$index]['nama_cluster'] ?></td>
                                                                    <?php foreach ($centroid as $value) : ?>
                                                                        <td><?= number_format($value, 3) ?></td>
                                                                    <?php endforeach; ?>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <h6 class="card-subtitle mt-3">Hasil Proses Iterasi <?= $iteration['iteration']; ?></h6>
                                                <div class="table-responsive" style="overflow-y: hidden">
                                                    <table class="table table-bordered table-striped" data-bs-toggle="table" data-height="250" data-mobile-responsive="true">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama Kelurahan</th>
                                                                <?php foreach ($atribut as $atr) : ?>
                                                                    <th><?= $atr['nama_atribut']; ?></th>
                                                                <?php endforeach; ?>
                                                                <?php foreach ($cluster as $cls) : ?>
                                                                    <th><?= $cls['nama_cluster']; ?></th>
                                                                <?php endforeach; ?>
                                                                <th>Jarak Terdekat</th>
                                                                <th>Cluster</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($iteration['clusters'] as $clusterId => $clusterData) : ?>
                                                                <?php foreach ($clusterData as $dataIndex) : ?>
                                                                    <tr>
                                                                        <td><?= $kelurahan[$dataIndex]['nama_kelurahan'] ?></td>
                                                                        <?php foreach ($data[$dataIndex] as $value) : ?>
                                                                            <td><?= number_format($value) ?></td>
                                                                        <?php endforeach; ?>
                                                                        <?php
                                                                        $distances = [];
                                                                        foreach ($iteration['centroids'] as $centroid) {
                                                                            $distances[] = calculateDistance($data[$dataIndex], $centroid);
                                                                        }
                                                                        foreach ($distances as $distance) : ?>
                                                                            <td><?= number_format($distance, 3) ?></td>
                                                                        <?php endforeach; ?>
                                                                        <td><?= number_format(min($distances), 3) ?></td>
                                                                        <td>Cluster <?= array_search(min($distances), $distances) + 1 ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Tabel Hasil Clustering Akhir -->
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Hasil Clustering Akhir</h4>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Kelurahan</th>
                                                            <?php foreach ($atribut as $atr) : ?>
                                                                <th><?= $atr['nama_atribut']; ?></th>
                                                            <?php endforeach; ?>
                                                            <th>Cluster</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($clusters as $clusterId => $clusterData) : ?>
                                                            <?php foreach ($clusterData as $dataIndex) : ?>
                                                                <tr>
                                                                    <td><?= $kelurahan[$dataIndex]['nama_kelurahan'] ?></td>
                                                                    <?php foreach ($data[$dataIndex] as $value) : ?>
                                                                        <td><?= number_format($value) ?></td>
                                                                    <?php endforeach; ?>
                                                                    <td>Cluster <?= $clusterId + 1 ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
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