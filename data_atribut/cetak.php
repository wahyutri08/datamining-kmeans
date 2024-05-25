<?php
require_once __DIR__ . '/../vendor/autoload.php';

require_once '../functions.php';
$atribut = query("SELECT * FROM atribut");

$mpdf = new \Mpdf\Mpdf();

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Atribut</title>
    <link rel="stylesheet" href="../assets/dist/css/print.css">
</head>
<body>
   <h1>Data Atribut</h1>
   <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>ID Atribut</th>
            <th>Nama Atribut</th>
        </tr>';
foreach ($atribut as $row) {
    $html .= '<tr>
            <td>' . $row["id_atribut"] . ' </td>
            <td>' . $row["nama_atribut"] . '</td>
        </tr>';
}

$html .= '</table>    
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output('data_atribut.pdf', 'I');
