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
                <th>Action</th>
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
                            <!-- <input type="text" id="<?= $rows["id_kelurahan"] . "_" . $row["id_atribut"] ?>"> -->
                        </td>
                    <?php endforeach; ?>
                    <!-- <td><a href="editnilai.php?id_kelurahan=<?= $rows["id_kelurahan"]; ?>"><button onclick="edit(<?= $rows['id_kelurahan'] ?>)">Edit</button></a></td> -->
                    <td><a class="btn btn-sm btn-warning" href="editnilai.php?id_kelurahan=<?= $rows["id_kelurahan"]; ?>" onclick="edit(<?= $rows['id_kelurahan'] ?>) role=" button"><i class="fas fa-edit"></i></a>
                        <?php
                        // Pastikan $nilaikelurahan dan $rows terdefinisi dan memiliki nilai sebelum digunakan
                        if (isset($nilaikelurahan[0]['nilai']) && isset($rows["id_kelurahan"])) {
                            if ($nilaikelurahan[0]['nilai'] !== null && $nilaikelurahan[0]['nilai'] !== "") {
                                // echo '| <a class="btn btn-sm btn-danger id="sa-warning"" href="deletenilai.php?id_kelurahan=' . $rows["id_kelurahan"] . '" onclick="return confirm(\'Hapus Nilai?\');" role="button"><i class="fas fa-trash"></i</a>';
                                echo '| <a class="btn btn-sm btn-danger tombol-hapus" href="deletenilai.php?id_kelurahan=' . $rows["id_kelurahan"] . '" role="button"><i class="fas fa-trash"></i</a>';
                            } else {
                                echo "";
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>