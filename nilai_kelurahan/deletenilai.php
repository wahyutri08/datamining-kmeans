<?php
session_start();
include_once("../auth_check.php");

if (!isset($_SESSION["login"])) {
    echo json_encode(['status' => 'redirect']);
    exit;
}

// Pastikan fungsi deletenilaiKelurahan sudah didefinisikan dan bekerja dengan benar
$id_kelurahan = $_GET["id_kelurahan"];

if (deletenilaiKelurahan($id_kelurahan) > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
exit;
