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
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
exit;
