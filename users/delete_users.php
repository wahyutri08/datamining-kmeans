<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"])) {
    header("Location: ../login");
    exit;
}
if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../dashboard");
    exit;
}
// require '../functions.php';
$id = $_GET["id"];

if (deleteUsers($id) > 0) {
    echo "
    <script> 
        alert('Data Berhasil Dihapus');
        document.location.href = '../users';
    </script>
    ";
} else {
    echo "<script> 
    alert('Data Gagal Dihapus');
    document.location.href = '../users';
</script>
";
}
