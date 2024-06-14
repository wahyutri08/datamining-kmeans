<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../functions.php';

$cluster = query("SELECT * FROM cluster");

$mpdf = new \Mpdf\Mpdf();

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Cluster</title>
    <link rel="stylesheet" href="../assets/dist/css/print.css">
</head>
<body>
   <h1>Data Cluster</h1>
   <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>ID Cluster</th>
            <th>Nama Cluster</th>
        </tr>';
foreach ($cluster as $row) {
    $html .= '<tr>
            <td>' . $row["id_cluster"] . ' </td>
            <td>' . $row["nama_cluster"] . '</td>
        </tr>';
}

$html .= '</table>    
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output('data_cluster.pdf', 'I');
