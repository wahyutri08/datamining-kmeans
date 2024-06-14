<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../functions.php';

$kelurahan = query("SELECT * FROM kelurahan");

$mpdf = new \Mpdf\Mpdf();

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Kelurahan</title>
    <link rel="stylesheet" href="../assets/dist/css/print.css">
</head>
<body>
   <h1>Data Kelurahan</h1>
   <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>ID Kelurahan</th>
            <th>Nama Kelurahan</th>
        </tr>';
foreach ($kelurahan as $row) {
    $html .= '<tr>
            <td>' . $row["id_kelurahan"] . ' </td>
            <td>' . $row["nama_kelurahan"] . '</td>
        </tr>';
}

$html .= '</table>    
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output('data_kelurahan.pdf', 'I');
