<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"])) {
    header("Location: ../login");
    exit;
}
// require '../functions.php';
$id_atribut = $_GET["id_atribut"];

if (deleteAtribut($id_atribut) > 0) {
    echo "
    <script> 
        alert('Data Berhasil Dihapus');
        document.location.href = '../data_atribut';
    </script>
    ";
} else {
    echo "<script> 
    alert('Data Gagal Dihapus');
    document.location.href = '../data_atribut';
</script>
";
}
