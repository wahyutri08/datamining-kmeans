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
                <th></th>
            </tr>
        </thead>
        <?php foreach ($atribut as $row) : ?>
            <tbody>
                <tr>
                    <td><?= $row["id_atribut"]; ?></td>
                    <td><?= $row["nama_atribut"]; ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="edit_atribut.php?id_atribut=<?= $row["id_atribut"]; ?>">Edit</a></li>
                                <li><a class="dropdown-item tombol-hapus" href="delete_atribut.php?id_atribut=<?= $row["id_atribut"]; ?>">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        <?php endforeach; ?>
    </table>
</div>