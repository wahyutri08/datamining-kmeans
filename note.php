<?php foreach ($history as $iteration) : ?>
    <h5>Iterasi <?= $iteration['iteration'] ?></h5>
    <h6>Centroids</h6>
    <ul>
        <?php foreach ($iteration['centroids'] as $centroid) : ?>
            <li>
                <?= implode(', ', array_map(function ($value) {
                    return number_format($value, 3);
                }, $centroid)) ?>
            </li>
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



<!-- Proses Iterasi -->
<?php foreach ($history as $iteration) : ?>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Proses Iterasi <?= $iteration['iteration']; ?></h4>
                <h6 class="card-subtitle">Centroid</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Kelurahan</th>
                                <?php foreach ($atribut as $atr) : ?>
                                    <th><?= $atr['nama_atribut']; ?></th>
                                <?php endforeach; ?>
                                <th>Cluster</th>
                                <th>Jarak Terdekat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($iteration['distanceData'] as $data) : ?>
                                <tr>
                                    <td><?= $kelurahan[$data['kelurahan']]['nama_kelurahan']; ?></td>
                                    <?php foreach ($data['kelurahan'] as $value) : ?>
                                        <td><?= number_format($value, 3); ?></td>
                                    <?php endforeach; ?>
                                    <td><?= $cluster[$data['cluster']]['nama_cluster']; ?></td>
                                    <td><?= number_format($data['jarak'], 3); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php

// function kmeans($data, $initialCentroids, $maxIterations)
// {
//     $centroids = $initialCentroids;
//     $history = [];
//     for ($iteration = 0; $iteration < $maxIterations; $iteration++) {
//         // Assign points to the closest centroids
//         $clusters = [];
//         $distanceData = [];
//         foreach ($data as $pointIndex => $point) {
//             $closestDistance = PHP_FLOAT_MAX;
//             $closestCentroidIndex = -1;
//             foreach ($centroids as $centroidIndex => $centroid) {
//                 $distance = calculateDistance($point, $centroid);
//                 if ($distance < $closestDistance) {
//                     $closestDistance = $distance;
//                     $closestCentroidIndex = $centroidIndex;
//                 }
//             }
//             $clusters[$closestCentroidIndex][] = $pointIndex;
//             $distanceData[$pointIndex] = [
//                 'kelurahan' => $pointIndex,
//                 'cluster' => $closestCentroidIndex,
//                 'jarak' => $closestDistance
//             ];
//         }

//         // Calculate new centroids
//         $newCentroids = [];
//         foreach ($clusters as $centroidIndex => $clusterPoints) {
//             $newCentroid = array_fill(0, count($data[0]), 0);
//             foreach ($clusterPoints as $pointIndex) {
//                 for ($i = 0; $i < count($data[$pointIndex]); $i++) {
//                     $newCentroid[$i] += $data[$pointIndex][$i];
//                 }
//             }
//             for ($i = 0; $i < count($newCentroid); $i++) {
//                 $newCentroid[$i] /= count($clusterPoints);
//             }
//             $newCentroids[$centroidIndex] = $newCentroid;
//         }

//         // Save history
//         $history[] = [
//             'iteration' => $iteration,
//             'centroids' => $newCentroids,
//             'distanceData' => $distanceData
//         ];

//         // Check for convergence
//         if ($newCentroids == $centroids) {
//             break;
//         }
//         $centroids = $newCentroids;
//     }

//     return [
//         'centroids' => $centroids,
//         'clusters' => $clusters,
//         'history' => $history
//     ];
// }
?>

<?php foreach ($history as $iteration) : ?>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Hasil Iterasi <?= $iteration['iteration'] ?></h4>
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

// Dapatkan hasil clustering awal sebelum iterasi pertama
$initialResult = getInitialClusters($data, $initialCentroids);

// Jalankan algoritma K-Means
$result = kmeans($data, $initialCentroids, $maxIterations);
$centroids = $result['centroids'];
$clusters = $result['clusters'];
$history = $result['history'];

// Simpan hasil ke database
if (isset($_POST['save_report'])) {
    $userId = $_SESSION['id']; // Asumsikan user ID disimpan di session
    $reportDate = date('Y-m-d H:i:s');

    // Simpan metadata laporan
    $insertReport = "INSERT INTO laporan (user_id, tanggal_laporan) VALUES ($userId, '$reportDate')";
    if (mysqli_query($db, $insertReport)) {
        $reportId = mysqli_insert_id($db);

        // Simpan hasil clustering akhir
        foreach ($clusters as $clusterId => $clusterData) {
            foreach ($clusterData as $dataIndex) {
                $kelurahanName = $kelurahan[$dataIndex]['nama_kelurahan'];
                foreach ($data[$dataIndex] as $attrIndex => $value) {
                    $attrName = $atribut[$attrIndex]['nama_atribut'];
                    $query = "INSERT INTO report_history (report_id, nama_kelurahan, nama_atribut, cluster, nilai) VALUES ($reportId, '$kelurahanName', '$attrName', " . ($clusterId + 1) . ", $value)";
                    mysqli_query($db, $query);
                }
            }
        }
        echo "
        <script>
        alert('Perhitungan Berhasil Ditambahkan');
        document.location.href = '../laporan'
        </script>";
    } else {
        echo "Gagal menyimpan laporan: " . mysqli_error($db);
    }
}


// if (isset($_POST["submit"])) {
//     // Ambil nilai yang dikirimkan untuk username baru
//     $newUsername = $_POST["username"];

//     // Lakukan pemeriksaan dengan database
//     $query = "SELECT username FROM users WHERE username = '$newUsername'";
//     $result = mysqli_query($db, $query);

//     // Jika username yang dikirim sudah ada di database selain username saat ini, tampilkan pesan kesalahan
//     if (mysqli_num_rows($result) > 0 && $newUsername !== $users["username"]) {
//         echo "
//         <script>
//         alert('Username sudah ada. Silakan pilih username lain.');
//         </script>";
//     } else {
//         // Lanjutkan dengan pembaruan data jika tidak ada masalah
//         if (editUsers($_POST) > 0) {

//             // Update session data dengan data baru
//             $_SESSION['user_data']['username'] = $_POST['username'];
//             $_SESSION['user_data']['nama'] = $_POST['nama'];
//             $_SESSION['user_data']['email'] = $_POST['email'];
//             $_SESSION['user_data']['role'] = $_POST['role'];

//             echo "
//             <script>
//             alert('Data Berhasil Diubah');
//             document.location.href = '../users'
//             </script>";
//         } else {
//             echo "
//             <script>
//             alert('Data Gagal Diubah');
//             document.location.href = '../users';
//             </script>";
//         }
//     }
// }

?>