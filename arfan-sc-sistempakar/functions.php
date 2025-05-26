<?php
$db = mysqli_connect("localhost", "root", "", "dev-arfan");
date_default_timezone_set('Asia/Jakarta');

function query($query)
{
    global $db;
    $result = mysqli_query($db, $query);
    $rows = [];
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
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
    $status = htmlspecialchars($data["status"]);

    // //  Upload Gambar
    // $avatar = upload();
    // if (!$avatar) {
    //     return -3;
    // } elseif ($avatar === -1) {
    //     // Kesalahan Ukuran Terlalu Besar
    //     return -4;
    // }

    //  Upload Gambar
    $avatar = upload();
    if ($avatar === -1) {
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

    mysqli_query($db, "INSERT INTO users 
    (username, nama, email, password, role, status, avatar) 
    VALUES 
    ('$username','$nama', '$email', '$password', '$role', '$status', '$avatar')");
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
    $password2 = mysqli_real_escape_string($db, $data["password2"]);
    $avatarLama = htmlspecialchars($data["avatarLama"]);
    $role = htmlspecialchars($data["role"]);
    $status = htmlspecialchars($data["status"]);
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

    if ($password !== $password2) {
        // Password 1 tidak sesuai dengan password 2
        return -3;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    $updatedAt = date('Y-m-d H:i:s');
    $query = "UPDATE users SET 
        username = '$username', 
        nama = '$nama', 
        email = '$email',
        password = '$password',
        role = '$role',
        status = '$status',
        avatar = '$avatar',
        updated_at = '$updatedAt' WHERE id = $id";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function deleteUsers($id)
{
    global $db;
    mysqli_query($db, "DELETE FROM users WHERE id = $id");
    return mysqli_affected_rows($db);
}

function addPasien($data)
{
    global $db;

    $role = $_SESSION['role'];
    $user_id = $_SESSION['id'];

    $nama_pasien = ucfirst(stripcslashes($data["nama_pasien"]));
    $nik = htmlspecialchars($data["nik"]);
    $jenis_kelamin = htmlspecialchars($data["jenis_kelamin"]);
    $tanggal_lahir = htmlspecialchars($data["tanggal_lahir"]);
    $usia = htmlspecialchars($data["usia"]);
    $alamat = htmlspecialchars($data["alamat"]);
    $no_hp = htmlspecialchars($data["no_hp"]);

    // Periksa apakah NIK sudah ada di database
    $query = "SELECT * FROM pasien WHERE nik = '$nik'";
    $result = mysqli_query($db, $query);
    if (mysqli_fetch_assoc($result)) {
        // Jika NIK sudah ada, return -1
        return -1;
    }

    // Cek role pengguna
    if ($role == 'Perawat' || $role == 'Admin') {
        $query = "INSERT INTO pasien 
        (user_id, nama_pasien, nik, jenis_kelamin, tanggal_lahir, usia, alamat, no_hp)
        VALUES 
        ('$user_id', '$nama_pasien', '$nik', '$jenis_kelamin', '$tanggal_lahir', '$usia', '$alamat', '$no_hp')";

        mysqli_query($db, $query);
        return mysqli_affected_rows($db);
    }
}

function editPasien($data) {}

function deleteSiswa($id_pasien)
{
    global $db;
    mysqli_query($db, "DELETE FROM pasien WHERE id_pasien = $id_pasien");
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

    move_uploaded_file($tmpName, '../assets/static/images/' . $namaFileBaru);

    return $namaFileBaru;
}

function is_user_active($id)
{
    global $db;

    // Cek status pengguna berdasarkan ID
    $result = mysqli_query($db, "SELECT status FROM users WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);

    // Jika data ditemukan
    if ($row) {
        // Cek apakah statusnya 'Aktif'
        if ($row['status'] === 'Aktif') {
            return true;
        }
    }

    // Jika tidak aktif atau tidak ditemukan
    return false;
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
