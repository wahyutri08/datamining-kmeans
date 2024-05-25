<?php
$db = mysqli_connect("localhost", "root", "", "dev-datamining");

function query($query)
{
    // var_dump($query);
    // exit();
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
        return false;
    }

    $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>
            alert('Username Already In Use');
            </script>";
        return false;
    }

    if ($password !== $password2) {
        echo "<script>
        alert('Password Tidak Sesuai');
        </script>";
        return false;
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

    // cek apakah tidak ada gambar yang diupload

    // if ($error === 4) {
    //     echo "<script>
    //             alert('Please Insert Image')
    //           </script>";
    //     return false;
    // }

    // Cek apakah yang diupload adalah gambar
    $ekstensiAvatarValid = ['', 'jpg', 'jpeg', 'png'];
    $ekstensiAvatar = explode('.', $namaFile);
    $ekstensiAvatar = strtolower(end($ekstensiAvatar));
    if (!in_array($ekstensiAvatar, $ekstensiAvatarValid)) {
        echo "<script>
                alert('Your File Not Image')
              </script>";
        return false;
    }

    // Cek jika ukuran terlalu besar
    if ($ukuranFiles > 2000000) {
        echo "<script>
        alert('Ukuran Gambar Terlalu Besar')
      </script>";
        return false;
    }

    // Gambar Siap Upload
    // generate nama gambar baru

    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiAvatar;

    move_uploaded_file($tmpName, '../assets/images/users/' . $namaFileBaru);

    return $namaFileBaru;
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
    }

    // $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
    // if (mysqli_fetch_assoc($result)) {
    //     echo "
    //     <script>
    //     alert('Username Alredy In Use');
    //     </script>
    //     ";
    //     return false;
    // }

    $query = "UPDATE users SET 
        nama = '$nama',  
        email = '$email',
        avatar = '$avatar' WHERE id = $id";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function ubahPassword($data)
{
    global $db;
    $id = ($data["id"]);
    $password = mysqli_real_escape_string($db, $data["password"]);
    $password2 = mysqli_real_escape_string($db, $data["password2"]);

    if ($password !== $password2) {
        echo "
        <script>
        alert('Password Tidak Sesuai');
        </script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET 
    password = '$password' WHERE id = $id";
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
    }

    // $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
    // if (mysqli_fetch_assoc($result)) {
    //     echo "
    //     <script>
    //     alert('Username Already In Use');
    //     </script>
    //     ";
    //     return false;
    // }

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

    $result = mysqli_query($db, "SELECT * FROM atribut WHERE nama_atribut = '$nama_atribut'");
    if (mysqli_fetch_assoc($result)) {
        echo "
        <script>
        alert('Nama Atribut Tidak Boleh Sama');
        </script>
        ";
        return false;
    }

    $query = "INSERT INTO atribut VALUES 
    ('$id_atribut', '$nama_atribut')";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function editAtribut($data)
{
    global $db;
    $id_atribut = ($data["id_atribut"]);
    $nama_atribut = htmlspecialchars($data["nama_atribut"]);

    $result = mysqli_query($db, "SELECT * FROM atribut WHERE nama_atribut = '$nama_atribut'");
    if (mysqli_fetch_assoc($result)) {
        echo "
        <script>
        alert('Nama Atribut Tidak Boleh Sama');
        </script>
        ";
        return false;
    }

    $query = "UPDATE atribut SET 
        nama_atribut = '$nama_atribut' WHERE id_atribut = $id_atribut";
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

    $result = mysqli_query($db, "SELECT * FROM kelurahan WHERE nama_kelurahan = '$nama_kelurahan'");
    if (mysqli_fetch_assoc($result)) {
        echo "
        <script>
        alert('Nama Kelurahan Tidak Boleh Sama');
        </script>";
        return false;
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

    $result = mysqli_query($db, "SELECT * FROM kelurahan WHERE nama_kelurahan = '$nama_kelurahan'");
    if (mysqli_fetch_assoc($result)) {
        echo "
        <script>
        alert('Nama Kelurahan Tidak Boleh Sama');
        </script>";
        return false;
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

    $result = mysqli_query($db, "SELECT * FROM cluster WHERE nama_cluster = '$nama_cluster'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>
            alert('Nama Cluster Tidak Boleh Sama');
            </script>";
        return false;
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

    $result = mysqli_query($db, "SELECT * FROM cluster WHERE nama_cluster = '$nama_cluster'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>
            alert('Nama Cluster Tidak Boleh Sama');
            </script>";
        return false;
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
              nama_atribut LIKE '%$keyword%' 
            ";
    return query($query);
}

function searchCluster($keyword)
{
    $query = "SELECT * FROM cluster WHERE
                nama_cluster LIKE '%$keyword%'
             ";
    return query($query);
}

function searchKelurahan($keyword)
{
    $query = "SELECT * FROM kelurahan WHERE
                nama_kelurahan LIKE '%$keyword%'
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

// Hasil oke
// function kmeans($data, $initialCentroids, $maxIterations = 1000)
// {
//     $centroids = $initialCentroids;
//     $clusters = [];
//     $iterations = 0;
//     $history = []; // Array untuk menyimpan sejarah iterasi

//     do {
//         $clusters = assignClusters($data, $centroids);
//         $oldCentroids = $centroids;
//         $centroids = calculateCentroids($data, $clusters, count($initialCentroids));
//         $iterations++;

//         // Simpan hasil sementara dari iterasi ini
//         $history[] = [
//             'iteration' => $iterations,
//             'centroids' => $centroids,
//             'clusters' => $clusters
//         ];
//     } while (!hasConverged($oldCentroids, $centroids) && $iterations < $maxIterations);

//     return ['centroids' => $centroids, 'clusters' => $clusters, 'history' => $history];
// }

// function assignClusters($data, $centroids)
// {
//     $clusters = [];
//     foreach ($data as $dataIndex => $dataPoint) {
//         $minDistance = PHP_INT_MAX;
//         $closestCentroid = 0;
//         foreach ($centroids as $centroidIndex => $centroid) {
//             $distance = calculateDistance($dataPoint, $centroid);
//             if ($distance < $minDistance) {
//                 $minDistance = $distance;
//                 $closestCentroid = $centroidIndex;
//             }
//         }
//         $clusters[$closestCentroid][] = $dataIndex;
//     }
//     return $clusters;
// }

// function calculateCentroids($data, $clusters, $k)
// {
//     $centroids = array_fill(0, $k, array_fill(0, count($data[0]), 0));
//     foreach ($clusters as $centroidIndex => $cluster) {
//         $clusterSize = count($cluster);
//         foreach ($cluster as $dataIndex) {
//             foreach ($data[$dataIndex] as $dim => $value) {
//                 $centroids[$centroidIndex][$dim] += $value;
//             }
//         }
//         if ($clusterSize > 0) {
//             foreach ($centroids[$centroidIndex] as $dim => $sum) {
//                 $centroids[$centroidIndex][$dim] /= $clusterSize;
//             }
//         }
//     }
//     return $centroids;
// }

// function calculateDistance($point1, $point2)
// {
//     $distance = 0;
//     foreach ($point1 as $dim => $value) {
//         $distance += pow($value - $point2[$dim], 2);
//     }
//     return sqrt($distance);
// }

// function hasConverged($oldCentroids, $newCentroids, $tolerance = 0.0001)
// {
//     foreach ($oldCentroids as $index => $oldCentroid) {
//         if (calculateDistance($oldCentroid, $newCentroids[$index]) > $tolerance) {
//             return false;
//         }
//     }
//     return true;
// }

// 4.1
// function calculateDistance($point1, $point2)
// {
//     $sum = 0;
//     foreach ($point1 as $i => $value) {
//         $sum += pow($value - $point2[$i], 2);
//     }
//     return sqrt($sum);
// }

// // Fungsi untuk menjalankan algoritma K-Means
// function kmeans($data, $initialCentroids, $maxIterations)
// {
//     $centroids = $initialCentroids;
//     $history = [];
//     for ($i = 0; $i < $maxIterations; $i++) {
//         $clusters = [];
//         $distances = [];
//         foreach ($data as $dataIndex => $dataPoint) {
//             $minDistance = PHP_INT_MAX;
//             $closestCentroid = -1;
//             foreach ($centroids as $centroidIndex => $centroid) {
//                 $distance = calculateDistance($dataPoint, $centroid);
//                 if ($distance < $minDistance) {
//                     $minDistance = $distance;
//                     $closestCentroid = $centroidIndex;
//                 }
//                 $distances[$dataIndex][$centroidIndex] = $distance;
//             }
//             $clusters[$closestCentroid][] = $dataIndex;
//         }
//         $newCentroids = [];
//         foreach ($clusters as $clusterIndex => $cluster) {
//             $newCentroid = array_fill(0, count($data[0]), 0);
//             foreach ($cluster as $dataIndex) {
//                 foreach ($data[$dataIndex] as $attributeIndex => $value) {
//                     $newCentroid[$attributeIndex] += $value;
//                 }
//             }
//             foreach ($newCentroid as $attributeIndex => $sum) {
//                 $newCentroid[$attributeIndex] /= count($cluster);
//             }
//             $newCentroids[$clusterIndex] = $newCentroid;
//         }
//         ksort($newCentroids); // Urutkan centroid berdasarkan kunci
//         ksort($clusters); // Urutkan cluster berdasarkan kunci
//         $history[] = [
//             'iteration' => $i + 1,
//             'centroids' => $newCentroids,
//             'clusters' => $clusters,
//             'distances' => $distances
//         ];
//         if ($newCentroids == $centroids) {
//             break;
//         }
//         $centroids = $newCentroids;
//     }
//     return [
//         'centroids' => $centroids,
//         'clusters' => $clusters,
//         'history' => $history,
//     ];
// }
