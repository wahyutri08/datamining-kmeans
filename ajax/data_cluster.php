<?php
require_once '../functions.php';
$keyword = $_GET["keyword"];

$query = "SELECT * FROM cluster WHERE 
            nama_cluster LIKE '%$keyword%'";

$cluster = query($query);
?>

<table class="table color-table red-table">
    <thead>
        <tr>
            <th>ID Cluster</th>
            <th>Nama Cluster</th>
            <th>Action</th>
        </tr>
    </thead>
    <?php foreach ($cluster as $row) : ?>
        <tbody>
            <tr>
                <td><?= $row["id_cluster"]; ?></td>
                <td><?= $row["nama_cluster"]; ?></td>
                <td><a class="btn btn-sm btn-warning" href="edit_cluster.php?id_cluster=<?= $row["id_cluster"]; ?>" role="button"><i class="fas fa-edit"></i></a> | <a class="btn btn-sm btn-primary" href="delete_cluster.php?id_cluster=<?= $row["id_cluster"]; ?>" onclick="return confirm('Yakin ?');" role="button"><i class="fas fa-trash"></i></a></td>
            </tr>
        </tbody>
    <?php endforeach; ?>
</table>