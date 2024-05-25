<?php
require_once '../functions.php';
$keyword = $_GET["keyword"];

$query = "SELECT * FROM atribut WHERE 
            nama_atribut LIKE '%$keyword%'";

$atribut = query($query);
?>

<div class="table-responsive">
    <table class="table color-table red-table">
        <thead>
            <tr>
                <th>ID Atribut</th>
                <th>Nama Atribut</th>
                <th>Action</th>
            </tr>
        </thead>
        <?php foreach ($atribut as $row) : ?>
            <tbody>
                <tr>
                    <td><?= $row["id_atribut"]; ?></td>
                    <td><?= $row["nama_atribut"]; ?></td>
                    <td><a class="btn btn-sm btn-warning" href="edit_atribut.php?id_atribut=<?= $row["id_atribut"]; ?>" role="button"><i class="fas fa-edit"></i></a> | <a class="btn btn-sm btn-primary" href="delete_atribut.php?id_atribut=<?= $row["id_atribut"]; ?>" onclick="return confirm('Yakin ?');" role="button"><i class="fas fa-trash"></i></a></td>
                </tr>
            </tbody>
        <?php endforeach; ?>
    </table>
</div>