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