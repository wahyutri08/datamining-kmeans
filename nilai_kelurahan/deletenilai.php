<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"])) {
    header("Location: ../login");
    exit;
}
// require '../functions.php';
$id_kelurahan = $_GET["id_kelurahan"];

if (deletenilaiKelurahan($id_kelurahan) > 0) {
    echo "
    <script> 
        document.location.href = '../nilai_kelurahan';
    </script>
    ";
} else {
    echo "<script> 
    alert('Data Gagal Dihapus');
    document.location.href = '../nilai_kelurahan';
</script>
";
}
