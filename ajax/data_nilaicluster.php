<?php
require_once '../functions.php';
$keyword = $_GET["keyword"];

$query = "SELECT * FROM cluster WHERE 
            nama_cluster LIKE '%$keyword%'";

$cluster = query($query);
$atribut = query("SELECT * FROM atribut");

?>

<div class="table-responsive">
    <table class="table color-table red-table">
        <thead>
            <tr>
                <th>Nama Cluster</th>
                <?php foreach ($atribut as $row) : ?>
                    <th><?= $row["nama_atribut"]; ?></th>
                <?php endforeach; ?>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cluster as $rows) : ?>
                <tr>
                    <td><?= $rows["nama_cluster"]; ?></td>
                    <?php foreach ($atribut as $row) : ?>
                        <td>
                            <?php
                            $nilaicluster = query("SELECT * FROM nilai_cluster WHERE id_cluster = " . $rows['id_cluster'] . " AND id_atribut = " . $row['id_atribut']);
                            if ($nilaicluster) {
                                echo $nilaicluster[0]['nilai'];
                            } else {
                                echo " ";
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                    <td>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="editnilai.php?id_cluster=<?= $rows["id_cluster"]; ?>" onclick="edit(<?= $rows['id_cluster'] ?>)">Edit</a></li>
                                <?php
                                // Pastikan $nilaikelurahan dan $rows terdefinisi dan memiliki nilai sebelum digunakan
                                if (isset($nilaicluster[0]['nilai']) && isset($rows["id_cluster"])) {
                                    if ($nilaicluster[0]['nilai'] !== null && $nilaicluster[0]['nilai'] !== "") {
                                        echo '<li><a class="dropdown-item tombol-hapus" href="deletenilai.php?id_cluster=' . $rows["id_cluster"] . '">Delete</a></li>';
                                    } else {
                                        echo "";
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>