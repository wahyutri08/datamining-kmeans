<?php
require_once '../functions.php';
$keyword = $_GET["keyword"];

$query = "SELECT * FROM kelurahan WHERE 
            nama_kelurahan LIKE '%$keyword%'";

$kelurahan = query($query);
$atribut = query("SELECT * FROM atribut");

?>

<div class="table-responsive">
    <table class="table color-table red-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kelurahan</th>
                <?php foreach ($atribut as $row) : ?>
                    <th><?= $row["nama_atribut"]; ?></th>
                <?php endforeach; ?>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php $n = 1; ?>
            <?php foreach ($kelurahan as $rows) : ?>
                <tr>
                    <td><?= $n; ?></td>
                    <td><?= $rows["nama_kelurahan"]; ?></td>
                    <?php $n++; ?>
                    <?php foreach ($atribut as $row) : ?>
                        <td>
                            <?php
                            $nilaikelurahan = query("SELECT * FROM nilai_kelurahan WHERE id_kelurahan = " . $rows['id_kelurahan'] . " AND id_atribut = " . $row['id_atribut']);
                            if ($nilaikelurahan) {
                                echo $nilaikelurahan[0]['nilai'];
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
                                <li><a class="dropdown-item" href="editnilai.php?id_kelurahan=<?= $rows["id_kelurahan"]; ?>" onclick="edit(<?= $rows['id_kelurahan'] ?>)">Edit</a></li>
                                <?php
                                // Pastikan $nilaikelurahan dan $rows terdefinisi dan memiliki nilai sebelum digunakan
                                if (isset($nilaikelurahan[0]['nilai']) && isset($rows["id_kelurahan"])) {
                                    if ($nilaikelurahan[0]['nilai'] !== null && $nilaikelurahan[0]['nilai'] !== "") {
                                        echo '<li><a class="dropdown-item tombol-hapus" href="deletenilai.php?id_kelurahan=' . $rows["id_kelurahan"] . '">Delete</a></li>';
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