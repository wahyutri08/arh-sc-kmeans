<?php

$db = mysqli_connect("localhost", "root", "", "dev-arh");

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


function addPC($data)
{
    global $db;
    $id_pc = htmlspecialchars($data["id_pc"]);
    $nama_pc = htmlspecialchars($data["nama_pc"]);

    $query = "SELECT * FROM nama_pc WHERE nama_pc = '$nama_pc' AND id_pc != $id_pc";
    $result = mysqli_query($db, $query);
    if (mysqli_fetch_assoc($result)) {
        return -1;
    }

    // Periksa apakah ID atribut sudah ada
    $query_id = "SELECT * FROM nama_pc WHERE id_pc = $id_pc";
    $result_id = mysqli_query($db, $query_id);

    if (mysqli_fetch_assoc($result_id)) {
        // ID atribut tidak ditemukan
        return -2;
    }

    $query = "INSERT INTO nama_pc VALUES 
    ('$id_pc', '$nama_pc')";

    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function editPC($data)
{
    global $db;
    $id_pc = ($data["id_pc"]);
    $nama_pc = htmlspecialchars($data["nama_pc"]);

    // Periksa apakah nama atribut sudah ada, tetapi abaikan baris yang sedang diedit
    $query = "SELECT * FROM nama_pc WHERE nama_pc = '$nama_pc' AND id_pc != $id_pc";
    $result = mysqli_query($db, $query);

    if (mysqli_fetch_assoc($result)) {
        return -1;
    }

    $query = "UPDATE nama_pc SET 
        nama_pc = '$nama_pc' WHERE id_pc = $id_pc";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function deletePC($id_pc)
{
    global $db;
    mysqli_query($db, "DELETE FROM nama_pc WHERE id_pc = $id_pc");
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

function dataPostnilaiPC($postData, $getData)
{
    global $db;
    $affected = 0;

    foreach ($postData as $key => $value) {
        if ($key == 'id_pc' || $key == 'submit') {
            continue;
        }

        $id_atribut = intval($key);
        $id_pc = intval($getData['id_pc']);
        $nilai = mysqli_real_escape_string($db, $value);

        $cek = mysqli_query($db, "SELECT * FROM nilai_pc WHERE id_atribut = $id_atribut AND id_pc = $id_pc");

        if (mysqli_num_rows($cek) > 0) {
            $query = "UPDATE nilai_pc SET nilai = '$nilai' WHERE id_atribut = $id_atribut AND id_pc = $id_pc";
        } else {
            $query = "INSERT INTO nilai_pc (id_atribut, id_pc, nilai) VALUES ($id_atribut, $id_pc, '$nilai')";
        }

        mysqli_query($db, $query) or die("Query Error: " . mysqli_error($db));
        $affected += mysqli_affected_rows($db);
    }

    return $affected;
}

// function dataPostnilaiPC($postData, $getData)
// {
//     foreach ($postData as $key => $value) {
//         if ($key == 'id_atribut' || $key == 'submit') {
//             continue;
//         }

//         // Gunakan prepared statement jika memungkinkan (opsional)
//         $querySelect = query("SELECT * FROM nilai_pc WHERE id_atribut = " . intval($key) . " AND id_pc = " . intval($getData['id_pc']));

//         if (count($querySelect) > 0) {
//             editnilaiPC($postData, $getData, $key);
//         } else {
//             tambahnilaiPC($postData, $getData, $key);
//         }
//     }
// }

// function editnilaiPC($post, $get, $key)
// {
//     global $db;

//     $nilai = mysqli_real_escape_string($db, $post[$key]);

//     $query = "UPDATE nilai_pc SET 
//         nilai = '" . $nilai . "' 
//         WHERE id_atribut = " . intval($key) . " 
//         AND id_pc = " . intval($get['id_pc']);

//     mysqli_query($db, $query);

//     return mysqli_affected_rows($db);
// }

// function tambahnilaiPC($post, $get, $key)
// {
//     global $db;

//     $nilai = mysqli_real_escape_string($db, $post[$key]);

//     $query = "INSERT INTO nilai_pc (id_atribut, kolom_lain, id_pc, nilai) VALUES (
//         " . intval($key) . ",
//         '',
//         " . intval($get['id_pc']) . ",
//         '" . $nilai . "'
//     )";

//     mysqli_query($db, $query);

//     return mysqli_affected_rows($db);
// }


function deletenilaiPC($id_pc)
{
    global $db;
    mysqli_query($db, "DELETE FROM nilai_pc WHERE id_pc = $id_pc");
    return mysqli_affected_rows($db);
}

function dataPostnilaiCluster($postData, $getData)
{
    global $db;
    $affected = 0;

    foreach ($postData as $key => $value) {
        if ($key == 'id_cluster' || $key == 'submit') {
            continue;
        }

        $id_atribut = intval($key);
        $id_cluster = intval($getData['id_cluster']);
        $nilai = mysqli_real_escape_string($db, $value);

        $cek = mysqli_query($db, "SELECT * FROM nilai_cluster WHERE id_atribut = $id_atribut AND id_cluster = $id_cluster");

        if (mysqli_num_rows($cek) > 0) {
            $query = "UPDATE nilai_cluster SET nilai = '$nilai' WHERE id_atribut = $id_atribut AND id_cluster = $id_cluster";
        } else {
            $query = "INSERT INTO nilai_cluster (id_atribut, id_cluster, nilai) VALUES ($id_atribut, $id_cluster, '$nilai')";
        }

        mysqli_query($db, $query) or die("Query Error: " . mysqli_error($db));
        $affected += mysqli_affected_rows($db);
    }

    return $affected;
}

// function dataPostCluster($postData, $getData)
// {
//     foreach ($postData as $key => $value) {
//         if ($key == 'id_cluster' || $key == 'submit') {
//             continue;
//         }

//         // Tambahkan nama variabel ke dalam array
//         $querySelect = query("SELECT * FROM nilai_cluster WHERE id_atribut = " . $key . " AND id_cluster = " . $getData['id_cluster']);

//         if (count($querySelect) > 0) {
//             editnilaiCluster($postData, $getData, $key);
//         } else {
//             tambahnilaiCluster($postData, $getData, $key);
//         }
//     }
// }

// function editnilaiCluster($post, $get, $key)
// {
//     global $db;
//     $query = "UPDATE nilai_cluster SET 
//     nilai = " . $post[$key] . " WHERE id_atribut = " . $key . " AND id_cluster = " . $get['id_cluster'];
//     mysqli_query($db, $query);

//     return mysqli_affected_rows($db);
// }

// function tambahnilaiCluster($post, $get, $key)
// {
//     global $db;

//     $query = "INSERT INTO nilai_cluster VALUES 
//     (
//       " . $key . ", 
//       " . $get['id_cluster'] . ",
//        '',
//        $post[$key]
//     )";

//     mysqli_query($db, $query);
//     return mysqli_affected_rows($db);
// }

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


// function simpanhasilakhir($centroids, $clusters, $history, $id_user, $dateReport, $kelurahan, $data, $atribut, $actualIterations)
// {
//     global $db;

//     // Normalisasi tanggal_laporan hanya menjadi tanggal tanpa waktu
//     $dateReport = date('Y-m-d', strtotime($dateReport));

//     // Cek jika laporan dengan user_id, tanggal_laporan, dan jumlah_iterasi sudah ada
//     $query = "SELECT id FROM laporan WHERE user_id = '$id_user' AND DATE(tanggal_laporan) = '$dateReport' AND jumlah_iterasi = '$actualIterations'";
//     $result = mysqli_query($db, $query);

//     if (mysqli_num_rows($result) == 0) {
//         // Jika laporan belum ada, masukkan laporan baru
//         $query = "INSERT INTO laporan (user_id, tanggal_laporan, jumlah_iterasi) VALUES ('$id_user', '$dateReport', '$actualIterations')";
//         if (mysqli_query($db, $query)) {
//             $id_laporan = mysqli_insert_id($db);
//         } else {
//             echo "Error inserting into laporan: " . mysqli_error($db) . "<br>";
//             return;
//         }
//     } else {
//         // Jika laporan sudah ada, ambil ID laporan yang ada
//         $row = mysqli_fetch_assoc($result);
//         $id_laporan = $row['id'];
//     }

//     // Masukkan data ke tabel laporan_hasil_akhir
//     foreach ($clusters as $clusterId => $clusterData) {
//         foreach ($clusterData as $dataIndex) {
//             $nama_kelurahan = $kelurahan[$dataIndex]['nama_kelurahan'];
//             $nama_cluster = 'Cluster ' . ($clusterId + 1);

//             // Cek apakah data laporan_hasil_akhir sudah ada
//             $query = "SELECT id FROM laporan_hasil_akhir WHERE id_laporan = '$id_laporan' AND nama_kelurahan = '$nama_kelurahan' AND nama_cluster = '$nama_cluster'";
//             $result = mysqli_query($db, $query);

//             if (mysqli_num_rows($result) == 0) {
//                 // Jika belum ada, masukkan data ke tabel laporan_hasil_akhir
//                 $query = "INSERT INTO laporan_hasil_akhir (id_laporan, nama_kelurahan, nama_cluster) VALUES ('$id_laporan', '$nama_kelurahan', '$nama_cluster')";
//                 if (mysqli_query($db, $query)) {
//                     $id_laporan_hasil_akhir = mysqli_insert_id($db);
//                 } else {
//                     echo "Error inserting into laporan_hasil_akhir: " . mysqli_error($db) . "<br>";
//                     return;
//                 }
//             } else {
//                 // Jika sudah ada, ambil ID laporan_hasil_akhir yang ada
//                 $row = mysqli_fetch_assoc($result);
//                 $id_laporan_hasil_akhir = $row['id'];
//             }

//             // Masukkan data ke tabel laporan_hasil_akhir_atribut
//             foreach ($data[$dataIndex] as $attrIndex => $value) {
//                 $nama_atribut = $atribut[$attrIndex]['nama_atribut'];
//                 $nilai = number_format($value);

//                 // Cek apakah data atribut sudah ada
//                 $query = "SELECT id FROM laporan_hasil_akhir_atribut WHERE id_laporan_hasil_akhir = '$id_laporan_hasil_akhir' AND nama_atribut = '$nama_atribut' AND nilai = '$nilai'";
//                 $result = mysqli_query($db, $query);

//                 if (mysqli_num_rows($result) == 0) {
//                     // Jika belum ada, masukkan data ke tabel laporan_hasil_akhir_atribut
//                     $query = "INSERT INTO laporan_hasil_akhir_atribut (id_laporan_hasil_akhir, nama_atribut, nilai) VALUES ('$id_laporan_hasil_akhir', '$nama_atribut', '$nilai')";
//                     if (!mysqli_query($db, $query)) {
//                         echo "Error inserting into laporan_hasil_akhir_atribut: " . mysqli_error($db) . "<br>";
//                         return;
//                     }
//                 }
//             }
//         }
//     }
// }

// function simpanhasilakhir($centroids, $clusters, $history, $id_user, $dateReport, $kelurahan, $data, $atribut, $actualIterations)
// {
//     global $db;

//     // Normalisasi tanggal_laporan hanya menjadi tanggal tanpa waktu
//     $dateReport = date('Y-m-d', strtotime($dateReport));

//     // Cek jika laporan dengan user_id, tanggal_laporan, dan jumlah_iterasi sudah ada
//     $query = "SELECT id FROM laporan WHERE user_id = '$id_user' AND DATE(tanggal_laporan) = '$dateReport' AND jumlah_iterasi = '$actualIterations'";
//     $result = mysqli_query($db, $query);

//     if (mysqli_num_rows($result) == 0) {
//         // Jika laporan belum ada, masukkan laporan baru
//         $query = "INSERT INTO laporan (user_id, tanggal_laporan, jumlah_iterasi) VALUES ('$id_user', '$dateReport', '$actualIterations')";
//         if (mysqli_query($db, $query)) {
//             $id_laporan = mysqli_insert_id($db);
//         } else {
//             return 0; // Gagal memasukkan ke tabel laporan
//         }
//     } else {
//         // Jika laporan sudah ada, ambil ID laporan yang ada
//         $row = mysqli_fetch_assoc($result);
//         $id_laporan = $row['id'];
//     }

//     // Masukkan data ke tabel laporan_hasil_akhir
//     foreach ($clusters as $clusterId => $clusterData) {
//         foreach ($clusterData as $dataIndex) {
//             $nama_kelurahan = $kelurahan[$dataIndex]['nama_kelurahan'];
//             $nama_cluster = 'Cluster ' . ($clusterId + 1);

//             // Cek apakah data laporan_hasil_akhir sudah ada
//             $query = "SELECT id FROM laporan_hasil_akhir WHERE id_laporan = '$id_laporan' AND nama_kelurahan = '$nama_kelurahan' AND nama_cluster = '$nama_cluster'";
//             $result = mysqli_query($db, $query);

//             if (mysqli_num_rows($result) == 0) {
//                 // Jika belum ada, masukkan data ke tabel laporan_hasil_akhir
//                 $query = "INSERT INTO laporan_hasil_akhir (id_laporan, nama_kelurahan, nama_cluster) VALUES ('$id_laporan', '$nama_kelurahan', '$nama_cluster')";
//                 if (mysqli_query($db, $query)) {
//                     $id_laporan_hasil_akhir = mysqli_insert_id($db);
//                 } else {
//                     return 0; // Gagal memasukkan ke tabel laporan_hasil_akhir
//                 }
//             } else {
//                 // Jika sudah ada, ambil ID laporan_hasil_akhir yang ada
//                 $row = mysqli_fetch_assoc($result);
//                 $id_laporan_hasil_akhir = $row['id'];
//             }

//             // Masukkan data ke tabel laporan_hasil_akhir_atribut
//             foreach ($data[$dataIndex] as $attrIndex => $value) {
//                 $nama_atribut = $atribut[$attrIndex]['nama_atribut'];
//                 $nilai = number_format($value);

//                 // Cek apakah data atribut sudah ada
//                 $query = "SELECT id FROM laporan_hasil_akhir_atribut WHERE id_laporan_hasil_akhir = '$id_laporan_hasil_akhir' AND nama_atribut = '$nama_atribut' AND nilai = '$nilai'";
//                 $result = mysqli_query($db, $query);

//                 if (mysqli_num_rows($result) == 0) {
//                     // Jika belum ada, masukkan data ke tabel laporan_hasil_akhir_atribut
//                     $query = "INSERT INTO laporan_hasil_akhir_atribut (id_laporan_hasil_akhir, nama_atribut, nilai) VALUES ('$id_laporan_hasil_akhir', '$nama_atribut', '$nilai')";
//                     if (!mysqli_query($db, $query)) {
//                         return 0; // Gagal memasukkan ke tabel laporan_hasil_akhir_atribut
//                     }
//                 }
//             }
//         }
//     }

//     return 1; // Seluruh proses berhasil
// }

function simpanhasilakhir($centroids, $clusters, $history, $id_user, $dateReport, $kelurahan, $data, $atribut, $actualIterations)
{
    global $db;

    $dateReport = date('Y-m-d', strtotime($dateReport));

    // Escape input
    $id_user = mysqli_real_escape_string($db, $id_user);
    $dateReport = mysqli_real_escape_string($db, $dateReport);
    $actualIterations = mysqli_real_escape_string($db, $actualIterations);

    // Cek laporan
    $query = "SELECT id FROM laporan WHERE user_id = '$id_user' AND DATE(tanggal_laporan) = '$dateReport' AND jumlah_iterasi = '$actualIterations'";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO laporan (user_id, tanggal_laporan, jumlah_iterasi) VALUES ('$id_user', '$dateReport', '$actualIterations')";
        if (mysqli_query($db, $query)) {
            $id_laporan = mysqli_insert_id($db);
        } else {
            error_log("Gagal insert ke laporan: " . mysqli_error($db));
            return 0;
        }
    } elseif ($result) {
        $row = mysqli_fetch_assoc($result);
        $id_laporan = $row['id'];
    } else {
        error_log("Gagal query laporan: " . mysqli_error($db));
        return 0;
    }

    // Proses laporan_hasil_akhir
    foreach ($clusters as $clusterId => $clusterData) {
        foreach ($clusterData as $dataIndex) {
            $nama_kelurahan = mysqli_real_escape_string($db, $kelurahan[$dataIndex]['nama_kelurahan']);
            $nama_cluster = mysqli_real_escape_string($db, 'Cluster ' . ($clusterId + 1));

            $query = "SELECT id FROM laporan_hasil_akhir WHERE id_laporan = '$id_laporan' AND nama_kelurahan = '$nama_kelurahan' AND nama_cluster = '$nama_cluster'";
            $result = mysqli_query($db, $query);

            if ($result && mysqli_num_rows($result) == 0) {
                $query = "INSERT INTO laporan_hasil_akhir (id_laporan, nama_kelurahan, nama_cluster) VALUES ('$id_laporan', '$nama_kelurahan', '$nama_cluster')";
                if (mysqli_query($db, $query)) {
                    $id_laporan_hasil_akhir = mysqli_insert_id($db);
                } else {
                    error_log("Gagal insert ke laporan_hasil_akhir: " . mysqli_error($db));
                    return 0;
                }
            } elseif ($result) {
                $row = mysqli_fetch_assoc($result);
                $id_laporan_hasil_akhir = $row['id'];
            } else {
                error_log("Gagal query laporan_hasil_akhir: " . mysqli_error($db));
                return 0;
            }

            // Proses atribut
            foreach ($data[$dataIndex] as $attrIndex => $value) {
                $nama_atribut = mysqli_real_escape_string($db, $atribut[$attrIndex]['nama_atribut']);
                $nilai = mysqli_real_escape_string($db, number_format($value, is_float($value) ? 2 : 0, '.', ''));

                $query = "SELECT id FROM laporan_hasil_akhir_atribut WHERE id_laporan_hasil_akhir = '$id_laporan_hasil_akhir' AND nama_atribut = '$nama_atribut' AND nilai = '$nilai'";
                $result = mysqli_query($db, $query);

                if ($result && mysqli_num_rows($result) == 0) {
                    $query = "INSERT INTO laporan_hasil_akhir_atribut (id_laporan_hasil_akhir, nama_atribut, nilai) VALUES ('$id_laporan_hasil_akhir', '$nama_atribut', '$nilai')";
                    if (!mysqli_query($db, $query)) {
                        error_log("Gagal insert atribut: " . mysqli_error($db));
                        return 0;
                    }
                } elseif (!$result) {
                    error_log("Gagal query atribut: " . mysqli_error($db));
                    return 0;
                }
            }
        }
    }

    return 1;
}


function deleteReport($id)
{
    global $db;
    mysqli_query($db, "DELETE FROM laporan WHERE id = $id");
    return mysqli_affected_rows($db);
}
