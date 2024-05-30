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
            <th></th>
        </tr>
    </thead>
    <?php foreach ($cluster as $row) : ?>
        <tbody>
            <tr>
                <td><?= $row["id_cluster"]; ?></td>
                <td><?= $row["nama_cluster"]; ?></td>
                <td>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="edit_cluster.php?id_cluster=<?= $row["id_cluster"]; ?>">Edit</a></li>
                            <li><a class="dropdown-item" href="delete_cluster.php?id_cluster=<?= $row["id_cluster"]; ?>" onclick="return confirm('Yakin ?');">Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        </tbody>
    <?php endforeach; ?>
</table>