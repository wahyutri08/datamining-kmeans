<!-- <?php
        // require_once '../functions.php';
        // $keyword = $_GET["keyword"];

        // $query = "SELECT * FROM kelurahan WHERE 
        //             nama_kelurahan LIKE '%$keyword%'";

        // $kelurahan = query($query);
        require_once '../functions.php';
        $keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;

        $jumlahDataPerHalaman = 10;
        $awalData = ($jumlahDataPerHalaman * $page) - $jumlahDataPerHalaman;

        $query = "SELECT * FROM kelurahan WHERE 
            nama_kelurahan LIKE '%$keyword%'
            LIMIT $awalData, $jumlahDataPerHalaman";

        $kelurahan = query($query);

        // Query untuk menghitung jumlah data total
        $queryTotal = "SELECT COUNT(*) AS jumlah FROM kelurahan WHERE nama_kelurahan LIKE '%$keyword%'";
        $resultTotal = query($queryTotal);
        $jumlahData = $resultTotal[0]['jumlah'];

        // Menghitung jumlah halaman
        $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

        // Mengonversi data ke dalam format JSON
        $response = array(
            "kelurahan" => $kelurahan,
            "pagination" => generatePagination($jumlahHalaman, $page) // Memanggil fungsi generatePagination
        );

        echo json_encode($response);
        ?>

<div class="table-responsive">
    <table class="table color-bordered-table red-bordered-table">
        <thead>
            <tr>
                <th>Id Kelurahan</th>
                <th>Nama Kelurahan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kelurahan as $row) : ?>
                <tr>
                    <td><?= $row["id_kelurahan"]; ?></td>
                    <td><?= $row["nama_kelurahan"]; ?></td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="edit_kelurahan.php?id_kelurahan=<?= $row["id_kelurahan"]; ?>" role="button"><i class="fas fa-edit"></i></a> |
                        <a class="btn btn-sm btn-primary" href="delete_kelurahan.php?id_kelurahan=<?= $row["id_kelurahan"]; ?>" onclick="return confirm('Yakin ?');" role="button"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php echo $response["pagination"]; ?> -->

<?php
require_once '../functions.php';
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

$jumlahDataPerHalaman = 10;
$awalData = ($jumlahDataPerHalaman * $page) - $jumlahDataPerHalaman;

$query = "SELECT * FROM kelurahan WHERE 
            nama_kelurahan LIKE '%$keyword%'
            LIMIT $awalData, $jumlahDataPerHalaman";

$kelurahan = query($query);

// Query untuk menghitung jumlah data total
$queryTotal = "SELECT COUNT(*) AS jumlah FROM kelurahan WHERE nama_kelurahan LIKE '%$keyword%'";
$resultTotal = query($queryTotal);
$jumlahData = $resultTotal[0]['jumlah'];

// Menghitung jumlah halaman
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

// Mendefinisikan tautan pagination secara langsung
$pagination = '<ul class="pagination justify-content-end">';
$pagination .= '<li class="page-item"><a class="page-link" href="?page=' . max(1, $page - 1) . '">Previous</a></li>';

for ($i = 1; $i <= $jumlahHalaman; $i++) {
    if ($i == $page) {
        $pagination .= '<li class="page-item active"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    } else {
        $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }
}

$pagination .= '<li class="page-item"><a class="page-link" href="?page=' . min($jumlahHalaman, $page + 1) . '">Next</a></li>';
$pagination .= '</ul>';
?>

<div class="table-responsive">
    <table class="table color-table red-table">
        <thead>
            <tr>
                <th>ID Kelurahan</th>
                <th>Nama Kelurahan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kelurahan as $row) : ?>
                <tr>
                    <td><?= $row["id_kelurahan"]; ?></td>
                    <td><?= $row["nama_kelurahan"]; ?></td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="edit_kelurahan.php?id_kelurahan=<?= $row["id_kelurahan"]; ?>" role="button"><i class="fas fa-edit"></i></a> |
                        <a class="btn btn-sm btn-primary" href="delete_kelurahan.php?id_kelurahan=<?= $row["id_kelurahan"]; ?>" onclick="return confirm('Yakin ?');" role="button"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Tampilkan pagination -->
<?= $pagination ?>