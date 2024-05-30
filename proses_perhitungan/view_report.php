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

$iterations = [];
foreach ($history as $entry) {
    $iterations[$entry['iteration']][$entry['cluster_id']] = explode(',', $entry['history_values']);
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
</head>

<body class="skin-red-dark fixed-layout">
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
                                <li class="breadcrumb-item"><a href="../report">Laporan</a></li>
                                <li class="breadcrumb-item active">Detail Laporan</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Detail Laporan -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Detail Laporan</h4>
                                        <p class="card-subtitle">ID Laporan: <?= $reportId ?></p>
                                        <p class="card-subtitle">Nama User: <?= $report[0]['nama'] ?></p>
                                        <p class="card-subtitle">Role: <?= $report[0]['role'] ?></p>
                                        <p class="card-subtitle">Tanggal Laporan: <?= $report[0]['tanggal_laporan'] ?></p>

                                        <!-- Tabel Centroid Awal -->
                                        <h5 class="card-title">Centroid Awal</h5>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>ID Cluster</th>
                                                        <th>Nilai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($initialCentroids as $centroid) : ?>
                                                        <tr>
                                                            <td><?= $centroid['cluster_id'] ?></td>
                                                            <td><?= $centroid['centroid_values'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Tabel Riwayat Clustering -->
                                        <h5 class="card-title">Riwayat Clustering</h5>
                                        <?php foreach ($iterations as $iterationNumber => $centroids) : ?>
                                            <h6>Iterasi <?= $iterationNumber ?></h6>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>ID Cluster</th>
                                                            <th>Nilai</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($centroids as $clusterId => $values) : ?>
                                                            <tr>
                                                                <td><?= $clusterId ?></td>
                                                                <td><?= implode(', ', $values) ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php require_once '../partials/footer.php'; ?>
            </div>
        </div>
    </div>
    <script src="../assets/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/dist/js/app-style-switcher.js"></script>
    <script src="../assets/dist/js/waves.js"></script>
    <script src="../assets/dist/js/sidebarmenu.js"></script>
    <script src="../assets/dist/js/custom.js"></script>
</body>

</html>