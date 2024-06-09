<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../functions.php';

$kelurahan = query("SELECT * FROM kelurahan");
$atribut = query("SELECT * FROM atribut");
$id_laporan = $_GET['id'];

// Ambil Data Laporan
$laporan = query("SELECT laporan.id, users.nama, users.role, laporan.tanggal_laporan FROM laporan JOIN users ON laporan.user_id = users.id WHERE laporan.id = $id_laporan");
if (empty($laporan)) {
    die("Laporan tidak ditemukan.");
}

$hasil_akhir = query("SELECT 
                laporan_hasil_akhir.id, 
                laporan_hasil_akhir.nama_kelurahan, 
                laporan_hasil_akhir.nama_cluster, 
                laporan_hasil_akhir_atribut.nama_atribut, 
                laporan_hasil_akhir_atribut.nilai
                FROM laporan_hasil_akhir 
                JOIN laporan_hasil_akhir_atribut 
                ON laporan_hasil_akhir.id = laporan_hasil_akhir_atribut.id_laporan_hasil_akhir
                WHERE laporan_hasil_akhir.id_laporan = '$id_laporan'
                 ");


$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 20,
    'margin_bottom' => 20,
]);

// Start the HTML for the PDF
$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Hasil Akhir Perhitungan</title>
    <style>
        body {
            font-family: sans-serif;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        h4 {
            margin-bottom: 10px;
        }
        p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
   <h1>Laporan Hasil Akhir Perhitungan</h1>
   <h4 class="card-title">Detail Laporan</h4>
   <p class="card-subtitle">ID Laporan : ' . $id_laporan . '</p>
   <p class="card-subtitle">Nama User : ' . $laporan[0]['nama'] . '</p>
   <p class="card-subtitle">Role : ' . $laporan[0]['role'] . '</p>
   <p class="card-subtitle">Tanggal Laporan : ' . $laporan[0]['tanggal_laporan'] . '</p>
   <table>
       <tr>
           <th>Nama Kelurahan</th>';

// Ambil atribut unik
$atribut_unik = [];
foreach ($hasil_akhir as $item) {
    if (!in_array($item['nama_atribut'], $atribut_unik)) {
        $atribut_unik[] = $item['nama_atribut'];
    }
}
foreach ($atribut_unik as $nama_atribut) {
    $html .= '<th>' . $nama_atribut . '</th>';
}
$html .= '<th>Cluster</th>
       </tr>
       <tbody>';

// Organize data by kelurahan
$data_by_kelurahan = [];
foreach ($hasil_akhir as $data) {
    $data_by_kelurahan[$data['nama_kelurahan']]['cluster'] = $data['nama_cluster'];
    $data_by_kelurahan[$data['nama_kelurahan']]['atribut'][$data['nama_atribut']] = $data['nilai'];
}

// Display data
foreach ($data_by_kelurahan as $kelurahan => $data) {
    $html .= '<tr>
                <td>' . $kelurahan . '</td>';
    foreach ($atribut_unik as $atribut) {
        $html .= '<td>' . ($data['atribut'][$atribut] ?? '-') . '</td>';
    }
    $html .= '<td>' . $data['cluster'] . '</td>
            </tr>';
}

$html .= '</tbody>
   </table>    
</body>
</html>';

// Generate PDF
$mpdf->WriteHTML($html);
$mpdf->Output('laporan_hasil_akhir_perhitungan.pdf', 'I');
