<?php
// upload.php: Menangani pemindaian RFID untuk akses dan pendaftaran

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parking_db";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah UID ada dalam POST request
if (isset($_POST['uid']) && !empty($_POST['uid'])) {
    $uid = $conn->real_escape_string($_POST['uid']);
    error_log("UID received: " . $uid);

    // Periksa mode pendaftaran dari tabel settings
    $sql_mode = "SELECT registration_mode FROM settings WHERE id=1";
    $result_mode = $conn->query($sql_mode);
    $registration_mode = 0; // Default: Mode pendaftaran nonaktif
    if ($result_mode->num_rows > 0) {
        $row_mode = $result_mode->fetch_assoc();
        $registration_mode = $row_mode['registration_mode'];
    }

    if ($registration_mode) {
        // Cek apakah UID sudah ada di tabel register_uid
        $sql_check = "SELECT * FROM register_uid WHERE uid='$uid'";
        $result_check = $conn->query($sql_check);
    
        if ($result_check->num_rows == 0) {
            // UID belum ada, masukkan ke tabel register_uid
            $sql_insert = "INSERT INTO register_uid (uid) VALUES ('$uid')";
            if ($conn->query($sql_insert) === TRUE) {
                // Nonaktifkan mode pendaftaran setelah UID dimasukkan
                $sql_disable = "UPDATE settings SET registration_mode=0 WHERE id=1";
                $conn->query($sql_disable);
                echo "UID berhasil didaftarkan.";
            } else {
                echo "Gagal memasukkan UID: " . $conn->error;
            }
        } else {
            echo "UID sudah terdaftar.";
        }
    } else {
        // Mode pendaftaran tidak aktif, proses akses biasa
        $sql = "SELECT * FROM rfid_data WHERE uid='$uid'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status = $row['status'];
            if ($status == 'allowed') {
                echo "open"; // Mengubah respons menjadi "open" jika akses diizinkan
            } elseif ($status == 'blocked') {
                echo "blocked"; // Mengirimkan respons blocked jika pengguna diblokir
            } else {
                echo "Access denied.";
            }

            // Catat akses di tabel access_logs
            $sql_log = "INSERT INTO access_logs (uid, access_time, status) VALUES ('$uid', NOW(), '$status')";
            $conn->query($sql_log);
        } else {
            echo "Access denied.";
        }
    }
} else {
    echo "No UID provided.";
}

$conn->close();
?>
