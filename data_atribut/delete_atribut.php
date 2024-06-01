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
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
exit;
