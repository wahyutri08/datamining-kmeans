<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"])) {
    header("Location: ../login");
    exit;
}
// require '../functions.php';
$id_cluster = $_GET["id_cluster"];

if (deletenilaiCluster($id_cluster) > 0) {
    echo "
    <script> 
        alert('Data Berhasil Dihapus');
        document.location.href = '../nilai_cluster';
    </script>
    ";
} else {
    echo "<script> 
    alert('Data Gagal Dihapus');
    document.location.href = '../nilai_cluster';
</script>
";
}
