<?php
$db = mysqli_connect("localhost", "root", "", "dev-datamining");

function query($query)
{
    global $db;
    $result = mysqli_query($db, $query);
    $rows = [];

    // Periksa apakah query berhasil dieksekusi
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // Loop melalui hasil query
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row; // Menambahkan baris hasil ke dalam array $rows
            }
        }
    } else {
        echo "Error: " . mysqli_error($db);
    }

    return $rows;
}

function register($data)
{
    global $db;

    $username = strtolower(stripcslashes($data["username"]));
    $nama = ucfirst(stripslashes($data["nama"]));
    $email = strtolower(stripslashes($data["email"]));
    $password = mysqli_real_escape_string($db, $data["password"]);
    $password2 = mysqli_real_escape_string($db, $data["password2"]);
    $role = htmlspecialchars($data["role"]);

    //  Upload Gambar
    $avatar = upload();
    if (!$avatar) {
        return -3;
    }

    $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        // Jika Nama Username Sudah Ada
        return -1;
    }

    if ($password !== $password2) {
        // Password 1 tidak sesuai dengan password 2
        return -2;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($db, "INSERT INTO users VALUES('', '$username','$nama', '$email', '$password', '$role', '$avatar')");
    return mysqli_affected_rows($db);
}

function upload()
{

    $namaFile = $_FILES['avatar']['name'];
    $ukuranFiles = $_FILES['avatar']['size'];
    $error = $_FILES['avatar']['error'];
    $tmpName = $_FILES['avatar']['tmp_name'];

    // Cek apakah yang diupload adalah gambar
    $ekstensiAvatarValid = ['', 'jpg', 'jpeg', 'png'];
    $ekstensiAvatar = explode('.', $namaFile);
    $ekstensiAvatar = strtolower(end($ekstensiAvatar));
    if (!in_array($ekstensiAvatar, $ekstensiAvatarValid)) {
        // Jika Avatar Bukan Gambar
        return -1;
    }

    if ($ukuranFiles > 10000000) {
        // Cek jika ukuran terlalu besar
        return -2;
    }

    // Gambar Siap Upload
    // generate nama gambar baru

    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiAvatar;

    move_uploaded_file($tmpName, '../assets/images/users/' . $namaFileBaru);

    return $namaFileBaru;
}

function ubahPassword($data)
{
    global $db;
    $id = ($data["id"]);
    $password = mysqli_real_escape_string($db, $data["password"]);
    $password2 = mysqli_real_escape_string($db, $data["password2"]);

    if ($password !== $password2) {
        return -1;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET 
    password = '$password' WHERE id = $id";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function editProfile($data)
{
    global $db;
    $id = ($data["id"]);
    $nama = ucfirst(stripcslashes($data["nama"]));
    $email = strtolower(stripslashes($data["email"]));
    $avatarLama = htmlspecialchars($data["avatarLama"]);

    // Cek apakah user pilih avatar baru atau tidak
    if ($_FILES['avatar']['error'] === 4) {
        $avatar = $avatarLama;
    } else {
        $avatar = upload();
        if ($avatar === -1) {
            // Kesalahan Jika Bukan Gambar
            return -1;
        } elseif ($avatar === -2) {
            // Kesalahan Jika Ukuran Terlalu Besar
            return -2;
        }
    }

    $query = "UPDATE users SET 
        nama = '$nama',  
        email = '$email',
        avatar = '$avatar' WHERE id = $id";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function editUsers($data)
{
    global $db;
    $id = ($data["id"]);
    $username = strtolower(stripslashes($data["username"]));
    $nama = ucfirst(stripcslashes($data["nama"]));
    $email = strtolower(stripslashes($data["email"]));
    $password = mysqli_real_escape_string($db, $data["password"]);
    $avatarLama = htmlspecialchars($data["avatarLama"]);
    $role = htmlspecialchars($data["role"]);
    // $usernameLama = htmlspecialchars($data["username"]);

    // Cek apakah user pilih avatar baru atau tidak
    if ($_FILES['avatar']['error'] === 4) {
        $avatar = $avatarLama;
    } else {
        $avatar = upload();
        if ($avatar === -1) {
            // Kesalahan Jika Bukan Gambar
            return -1;
        } elseif ($avatar === -2) {
            // Kesalahan Ukuran Terlalu Besar
            return -2;
        }
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET 
        username = '$username', 
        nama = '$nama', 
        email = '$email',
        password = '$password',
        role = '$role',
        avatar = '$avatar' WHERE id = $id";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function deleteUsers($id)
{
    global $db;
    mysqli_query($db, "DELETE FROM users WHERE id = $id");
    return mysqli_affected_rows($db);
}

function addAtribut($data)
{
    global $db;
    $id_atribut = htmlspecialchars($data["id_atribut"]);
    $nama_atribut = htmlspecialchars($data["nama_atribut"]);

    $query = "SELECT * FROM atribut WHERE nama_atribut = '$nama_atribut' AND id_atribut != $id_atribut";
    $result = mysqli_query($db, $query);
    if (mysqli_fetch_assoc($result)) {
        return -1;
    }

    // Periksa apakah ID atribut sudah ada
    $query_id = "SELECT * FROM atribut WHERE id_atribut = $id_atribut";
    $result_id = mysqli_query($db, $query_id);

    if (mysqli_fetch_assoc($result_id)) {
        // ID atribut tidak ditemukan
        return -2;
    }

    $query = "INSERT INTO atribut VALUES 
    ('$id_atribut', '$nama_atribut')";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function editAtribut($data)
{
    global $db;
    $id_atribut = $data["id_atribut"];
    $nama_atribut = htmlspecialchars($data["nama_atribut"]);
    $nama_atribut = mysqli_real_escape_string($db, $nama_atribut);

    // Periksa apakah nama atribut sudah ada, tetapi abaikan baris yang sedang diedit
    $query = "SELECT * FROM atribut WHERE nama_atribut = '$nama_atribut' AND id_atribut != $id_atribut";
    $result = mysqli_query($db, $query);

    if (mysqli_fetch_assoc($result)) {
        return -1;
    }

    // Jika nama atribut tidak ada yang duplikat, lakukan update
    $query = "UPDATE atribut SET nama_atribut = '$nama_atribut' WHERE id_atribut = $id_atribut";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}


function deleteAtribut($id_atribut)
{
    global $db;
    mysqli_query($db, "DELETE FROM atribut WHERE id_atribut = $id_atribut");
    return mysqli_affected_rows($db);
}


function addKelurahan($data)
{
    global $db;
    $id_kelurahan = htmlspecialchars($data["id_kelurahan"]);
    $nama_kelurahan = htmlspecialchars($data["nama_kelurahan"]);

    $query = "SELECT * FROM kelurahan WHERE nama_kelurahan = '$nama_kelurahan' AND id_kelurahan != $id_kelurahan";
    $result = mysqli_query($db, $query);
    if (mysqli_fetch_assoc($result)) {
        return -1;
    }

    // Periksa apakah ID atribut sudah ada
    $query_id = "SELECT * FROM kelurahan WHERE id_kelurahan = $id_kelurahan";
    $result_id = mysqli_query($db, $query_id);

    if (mysqli_fetch_assoc($result_id)) {
        // ID atribut tidak ditemukan
        return -2;
    }

    $query = "INSERT INTO kelurahan VALUES 
    ('$id_kelurahan', '$nama_kelurahan')";

    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function editKelurahan($data)
{
    global $db;
    $id_kelurahan = ($data["id_kelurahan"]);
    $nama_kelurahan = htmlspecialchars($data["nama_kelurahan"]);

    // Periksa apakah nama atribut sudah ada, tetapi abaikan baris yang sedang diedit
    $query = "SELECT * FROM kelurahan WHERE nama_kelurahan = '$nama_kelurahan' AND id_kelurahan != $id_kelurahan";
    $result = mysqli_query($db, $query);

    if (mysqli_fetch_assoc($result)) {
        return -1;
    }

    $query = "UPDATE kelurahan SET 
        nama_kelurahan = '$nama_kelurahan' WHERE id_kelurahan = $id_kelurahan";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function deleteKelurahan($id_kelurahan)
{
    global $db;
    mysqli_query($db, "DELETE FROM kelurahan WHERE id_kelurahan = $id_kelurahan");
    return mysqli_affected_rows($db);
}

function addCluster($data)
{
    global $db;
    $id_cluster = htmlspecialchars($data["id_cluster"]);
    $nama_cluster = htmlspecialchars($data["nama_cluster"]);

    $query = "SELECT * FROM cluster WHERE nama_cluster = '$nama_cluster' AND id_cluster != $id_cluster";
    $result = mysqli_query($db, $query);
    if (mysqli_fetch_assoc($result)) {
        return -1;
    }

    // Periksa apakah ID atribut sudah ada
    $query_id = "SELECT * FROM cluster WHERE id_cluster = $id_cluster";
    $result_id = mysqli_query($db, $query_id);

    if (mysqli_fetch_assoc($result_id)) {
        // ID atribut tidak ditemukan
        return -2;
    }


    $query = "INSERT INTO cluster VALUES 
    ('$id_cluster', '$nama_cluster')";

    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function editCluster($data)
{
    global $db;
    $id_cluster = ($data["id_cluster"]);
    $nama_cluster = htmlspecialchars($data["nama_cluster"]);

    // Periksa apakah nama atribut sudah ada, tetapi abaikan baris yang sedang diedit
    $query = "SELECT * FROM cluster WHERE nama_cluster = '$nama_cluster' AND id_cluster != $id_cluster";
    $result = mysqli_query($db, $query);

    if (mysqli_fetch_assoc($result)) {
        return -1;
    }

    $query = "UPDATE cluster SET 
        nama_cluster = '$nama_cluster' WHERE id_cluster = $id_cluster";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function deleteCluster($id_cluster)
{
    global $db;
    mysqli_query($db, "DELETE FROM cluster WHERE id_cluster = $id_cluster");
    return mysqli_affected_rows($db);
}


function dataPostKelurahan($postData, $getData)
{
    foreach ($postData as $key => $value) {
        if ($key == 'id_kelurahan' || $key == 'submit') {
            continue;
        }

        // Tambahkan nama variabel ke dalam array
        $querySelect = query("SELECT * FROM nilai_kelurahan WHERE id_atribut = " . $key . " AND id_kelurahan = " . $getData['id_kelurahan']);

        if (count($querySelect) > 0) {
            editnilaiKelurahan($postData, $getData, $key);
        } else {
            tambahnilaiKelurahan($postData, $getData, $key);
        }
    }
}


function editnilaiKelurahan($post, $get, $key)
{
    global $db;
    $query = "UPDATE nilai_kelurahan SET 
    nilai = " . $post[$key] . " WHERE id_atribut = " . $key . " AND id_kelurahan = " . $get['id_kelurahan'];
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function tambahnilaiKelurahan($post, $get, $key)
{
    global $db;

    $query = "INSERT INTO nilai_kelurahan VALUES 
    (
      " . $key . ", 
       '',
       " . $get['id_kelurahan'] . ",
       $post[$key]
    )";

    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function deletenilaiKelurahan($id_kelurahan)
{
    global $db;
    mysqli_query($db, "DELETE FROM nilai_kelurahan WHERE id_kelurahan = $id_kelurahan");
    return mysqli_affected_rows($db);
}

function dataPostCluster($postData, $getData)
{
    foreach ($postData as $key => $value) {
        if ($key == 'id_cluster' || $key == 'submit') {
            continue;
        }

        // Tambahkan nama variabel ke dalam array
        $querySelect = query("SELECT * FROM nilai_cluster WHERE id_atribut = " . $key . " AND id_cluster = " . $getData['id_cluster']);

        if (count($querySelect) > 0) {
            editnilaiCluster($postData, $getData, $key);
        } else {
            tambahnilaiCluster($postData, $getData, $key);
        }
    }
}

function editnilaiCluster($post, $get, $key)
{
    global $db;
    $query = "UPDATE nilai_cluster SET 
    nilai = " . $post[$key] . " WHERE id_atribut = " . $key . " AND id_cluster = " . $get['id_cluster'];
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function tambahnilaiCluster($post, $get, $key)
{
    global $db;

    $query = "INSERT INTO nilai_cluster VALUES 
    (
      " . $key . ", 
      " . $get['id_cluster'] . ",
       '',
       $post[$key]
    )";

    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function deletenilaiCluster($id_cluster)
{
    global $db;
    mysqli_query($db, "DELETE FROM nilai_cluster WHERE id_cluster = $id_cluster");
    return mysqli_affected_rows($db);
}

function searchAtribut($keyword)
{
    $query = "SELECT * FROM atribut WHERE
              nama_atribut LIKE '%$keyword%' OR
              id_atribut LIKE '%$keyword%'
            ";
    return query($query);
}

function searchCluster($keyword)
{
    $query = "SELECT * FROM cluster WHERE
                nama_cluster LIKE '%$keyword%' OR
                id_cluster LIKE '%$keyword%'
             ";
    return query($query);
}

function searchKelurahan($keyword)
{
    $query = "SELECT * FROM kelurahan WHERE
                nama_kelurahan LIKE '%$keyword%' OR
                id_kelurahan LIKE '%$keyword'
             ";
    return query($query);
}

function searchNilaiKelurahan($keyword)
{
    $query = "SELECT * FROM kelurahan WHERE
                nama_kelurahan LIKE '%$keyword%'
             ";
    return query($query);
}

function searchNilaiCluster($keyword)
{
    $query = "SELECT * FROM kelurahan WHERE
                nama_kelurahan LIKE '%$keyword%'
             ";
    return query($query);
}

function searchUsers($keyword)
{
    $query = "SELECT * FROM users WHERE
                id LIKE '%$keyword%' OR
                username LIKE '%$keyword%' OR
                nama LIKE '%$keyword%' OR
                email LIKE '%$keyword%' OR
                role LIKE '%$keyword%'
             ";
    return query($query);
}

function is_user_active($id)
{
    global $db;

    $result = mysqli_query($db, "SELECT COUNT(*) AS count FROM users WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $count = $row["count"];
        if ($count > 0) {
            return true;
        }
    } else {
        return false;
    }
}

function logout()
{
    // Hapus semua data sesi
    $_SESSION = array();

    // Hapus cookie sesi jika ada
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Hancurkan sesi
    session_destroy();

    // Alihkan ke halaman login
    header("Location: ../login"); // Sesuaikan dengan halaman login Anda
    exit;
}

function generatePagination($jumlahHalaman, $halamanAktif)
{
    $pagination = '<ul class="pagination justify-content-end">';
    $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . max(1, $halamanAktif - 1) . '">Previous</a></li>';

    for ($i = 1; $i <= $jumlahHalaman; $i++) {
        if ($i == $halamanAktif) {
            $pagination .= '<li class="page-item active"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        } else {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }
    }

    $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . min($jumlahHalaman, $halamanAktif + 1) . '">Next</a></li>';
    $pagination .= '</ul>';

    return $pagination;
}

// Rumus Algoritma K-Means

function calculateDistance($point1, $point2)
{
    $sum = 0;
    foreach ($point1 as $i => $value) {
        $sum += pow($value - $point2[$i], 2);
    }
    return sqrt($sum);
}

// Fungsi untuk menjalankan algoritma K-Means
function kmeans($data, $initialCentroids, $maxIterations)
{
    $centroids = $initialCentroids;
    $history = [];
    for ($i = 0; $i < $maxIterations; $i++) {
        $clusters = [];
        $distances = [];
        foreach ($data as $dataIndex => $dataPoint) {
            $minDistance = PHP_INT_MAX;
            $closestCentroid = -1;
            foreach ($centroids as $centroidIndex => $centroid) {
                $distance = calculateDistance($dataPoint, $centroid);
                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestCentroid = $centroidIndex;
                }
                $distances[$dataIndex][$centroidIndex] = $distance;
            }
            $clusters[$closestCentroid][] = $dataIndex;
        }
        $newCentroids = [];
        foreach ($clusters as $clusterIndex => $cluster) {
            $newCentroid = array_fill(0, count($data[0]), 0);
            foreach ($cluster as $dataIndex) {
                foreach ($data[$dataIndex] as $attributeIndex => $value) {
                    $newCentroid[$attributeIndex] += $value;
                }
            }
            foreach ($newCentroid as $attributeIndex => $sum) {
                $newCentroid[$attributeIndex] /= count($cluster);
            }
            $newCentroids[$clusterIndex] = $newCentroid;
        }
        ksort($newCentroids); // Urutkan centroid berdasarkan kunci
        ksort($clusters); // Urutkan cluster berdasarkan kunci
        $history[] = [
            'iteration' => $i + 1,
            'centroids' => $newCentroids,
            'clusters' => $clusters,
            'distances' => $distances
        ];
        if ($newCentroids == $centroids) {
            break;
        }
        $centroids = $newCentroids;
    }
    return [
        'centroids' => $centroids,
        'clusters' => $clusters,
        'history' => $history,
        'iteration' => $i + 1,
    ];
}

// Fungsi untuk mendapatkan hasil clustering awal sebelum iterasi pertama
function getInitialClusters($data, $initialCentroids)
{
    $clusters = [];
    $distances = [];
    foreach ($data as $dataIndex => $dataPoint) {
        $minDistance = PHP_INT_MAX;
        $closestCentroid = -1;
        foreach ($initialCentroids as $centroidIndex => $centroid) {
            $distance = calculateDistance($dataPoint, $centroid);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestCentroid = $centroidIndex;
            }
            $distances[$dataIndex][$centroidIndex] = $distance;
        }
        $clusters[$closestCentroid][] = $dataIndex;
    }
    ksort($clusters); // Urutkan cluster berdasarkan kunci
    return [
        'clusters' => $clusters,
        'distances' => $distances,
    ];
}


function simpanhasilakhir($centroids, $clusters, $history, $id_user, $dateReport, $kelurahan, $data, $atribut, $actualIterations)
{
    global $db;

    // Masukkan data ke tabel laporan
    $query = "INSERT INTO laporan (user_id, tanggal_laporan, jumlah_iterasi) VALUES ('$id_user', '$dateReport', '$actualIterations')";
    if (mysqli_query($db, $query)) {
        $id_laporan = mysqli_insert_id($db);

        // Masukkan data ke tabel laporan_hasil_akhir
        foreach ($clusters as $clusterId => $clusterData) {
            foreach ($clusterData as $dataIndex) {
                $nama_kelurahan = $kelurahan[$dataIndex]['nama_kelurahan'];
                $nama_cluster = 'Cluster ' . ($clusterId + 1);

                $query = "INSERT INTO laporan_hasil_akhir (id_laporan, nama_kelurahan, nama_cluster) VALUES ('$id_laporan', '$nama_kelurahan', '$nama_cluster')";
                if (mysqli_query($db, $query)) {
                    $id_laporan_hasil_akhir = mysqli_insert_id($db);

                    // Masukkan data ke tabel laporan_hasil_akhir_atribut
                    foreach ($data[$dataIndex] as $attrIndex => $value) {
                        $nama_atribut = $atribut[$attrIndex]['nama_atribut'];
                        $nilai = number_format($value);

                        $query = "INSERT INTO laporan_hasil_akhir_atribut (id_laporan_hasil_akhir, nama_atribut, nilai) VALUES ('$id_laporan_hasil_akhir', '$nama_atribut', '$nilai')";
                        if (!mysqli_query($db, $query)) {
                            echo "Error inserting into laporan_hasil_akhir_atribut: " . mysqli_error($db) . "<br>";
                            exit;
                        }
                    }
                } else {
                    echo "Error: " . mysqli_error($db) . "<br>";
                }
            }
        }

        // echo "Data berhasil disimpan dengan ID laporan: " . $id_laporan . "<br>";
    } else {
        echo "Error: " . mysqli_error($db) . "<br>";
    }
}

function deleteReport($id)
{
    global $db;
    mysqli_query($db, "DELETE FROM laporan WHERE id = $id");
    return mysqli_affected_rows($db);
}
