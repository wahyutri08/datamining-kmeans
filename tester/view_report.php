<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"])) {
    header("Location:../login");
    exit;
}

$reportId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($reportId === 0) {
    die("ID laporan tidak valid.");
}

// Ambil detail laporan
$report = query("SELECT laporan.id, users.nama, users.role, laporan.tanggal_laporan FROM laporan JOIN users ON laporan.user_id = users.id WHERE laporan.id = $reportId");
if (empty($report)) {
    die("Laporan tidak ditemukan.");
}

// Ambil centroid awal
$initialCentroids = query("SELECT cluster_id, centroid_values FROM report_initial_centroids WHERE report_id = $reportId");

// Ambil riwayat clustering
$history = query("SELECT iteration, cluster_id, history_values FROM report_history WHERE report_id = $reportId ORDER BY iteration, cluster_id");

// Ambil data kelurahan dan atribut
$kelurahan = query("SELECT * FROM kelurahan ORDER BY id_kelurahan");
$atribut = query("SELECT * FROM atribut ORDER BY id_atribut");
$cluster = query("SELECT * FROM cluster ORDER BY id_cluster");

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

// Parsing centroids dan riwayat dari database
$parsedInitialCentroids = [];
foreach ($initialCentroids as $centroid) {
    $parsedInitialCentroids[$centroid['cluster_id']] = explode(',', $centroid['centroid_values']);
}

$parsedHistory = [];
foreach ($history as $entry) {
    $parsedHistory[$entry['iteration']][$entry['cluster_id']] = explode(',', $entry['history_values']);
}

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
    <title>Detail Laporan - Data Mining</title>
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
                        <h4 class="text-themecolor">Detail Laporan</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="../laporan">Laporan</a></li>
                                <li class="breadcrumb-item active">Detail Laporan</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Hasil Clustering Sebelum Iterasi Pertama -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Hasil Clustering Sebelum Iterasi Pertama</h4>
                                <h6 class="card-subtitle">Centroid Awal</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Cluster</th>
                                                <?php foreach ($atribut as $atr) : ?>
                                                    <th><?= $atr['nama_atribut']; ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($parsedInitialCentroids as $index => $centroid) : ?>
                                                <tr>
                                                    <td>Cluster <?= $index ?></td>
                                                    <?php foreach ($centroid as $value) : ?>
                                                        <td><?= number_format($value, 3) ?></td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Proses Iterasi -->
                <?php foreach ($parsedHistory as $iterationNumber => $iteration) : ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Proses Iterasi <?= $iterationNumber ?></h4>
                                    <h6 class="card-subtitle">Centroid</h6>
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
                                                <?php foreach ($iteration as $index => $centroid) : ?>
                                                    <tr>
                                                        <td>Cluster <?= $index ?></td>
                                                        <?php foreach ($centroid as $value) : ?>
                                                            <td><?= number_format($value, 3) ?></td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <h6 class="card-subtitle mt-3">Hasil Proses Iterasi <?= $iterationNumber ?></h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
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
                                                <?php foreach ($iteration as $clusterId => $centroid) : ?>
                                                    <?php foreach ($data as $dataIndex => $row) : ?>
                                                        <?php
                                                        $distances = [];
                                                        foreach ($parsedHistory[$iterationNumber] as $centroid) {
                                                            $distances[] = calculateDistance($row, $centroid);
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?= $kelurahan[$dataIndex]['nama_kelurahan'] ?></td>
                                                            <?php foreach ($row as $value) : ?>
                                                                <td><?= number_format($value, 3) ?></td>
                                                            <?php endforeach; ?>
                                                            <?php foreach ($distances as $distance) : ?>
                                                                <td><?= number_format($distance, 3) ?></td>
                                                            <?php endforeach; ?>
                                                            <td><?= number_format(min($distances), 3) ?></td>
                                                            <td>Cluster <?= array_search(min($distances), $distances) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Hasil Clustering Akhir -->
                <div class="row">
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
                                                <?php foreach ($cluster as $cls) : ?>
                                                    <th><?= $cls['nama_cluster']; ?></th>
                                                <?php endforeach; ?>
                                                <th>Jarak Terdekat</th>
                                                <th>Cluster</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data as $dataIndex => $row) : ?>
                                                <?php
                                                $distances = [];
                                                foreach (end($parsedHistory) as $centroid) {
                                                    $distances[] = calculateDistance($row, $centroid);
                                                }
                                                ?>
                                                <tr>
                                                    <td><?= $kelurahan[$dataIndex]['nama_kelurahan'] ?></td>
                                                    <?php foreach ($row as $value) : ?>
                                                        <td><?= number_format($value, 3) ?></td>
                                                    <?php endforeach; ?>
                                                    <?php foreach ($distances as $distance) : ?>
                                                        <td><?= number_format($distance, 3) ?></td>
                                                    <?php endforeach; ?>
                                                    <td><?= number_format(min($distances), 3) ?></td>
                                                    <td>Cluster <?= array_search(min($distances), $distances) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php require_once '../partials/footer.php'; ?>
        </div>
    </div>

    <script src="../assets/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/dist/js/app.min.js"></script>
</body>

</html>