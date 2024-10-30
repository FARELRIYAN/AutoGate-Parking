<?php
// register_card.php: Menangani penyimpanan kartu baru ke database

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parking_db";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah data dikirimkan melalui POST
if (isset($_POST['uid'], $_POST['username'], $_POST['status'], $_POST['plat_nomor'], $_POST['kelas'])) {
    $uid = $_POST['uid'];
    $username_input = $_POST['username'];
    $status = $_POST['status'];
    $plat_nomor = $_POST['plat_nomor'];
    $kelas = $_POST['kelas'];


    // Cek apakah UID dan username tidak kosong
    if (!empty($uid) && !empty($username_input)) {
        // Escape input untuk keamanan
        $uid = $conn->real_escape_string($uid);
        $username_input = $conn->real_escape_string($username_input);
        $status = $conn->real_escape_string($status);
        $plat_nomor = $conn->real_escape_string($plat_nomor);
        $kelas = $conn->real_escape_string($kelas);

        // Periksa apakah UID sudah terdaftar
        $sql_check = "SELECT * FROM rfid_data WHERE uid='$uid'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            echo "UID sudah terdaftar.";
        } else {
            // Masukkan data ke dalam tabel
            $sql_insert = "INSERT INTO rfid_data (uid, username, timestamp, status, plat_nomor, kelas) 
                           VALUES ('$uid', '$username_input', NOW(), '$status', '$plat_nomor', '$kelas')";

            if ($conn->query($sql_insert) === TRUE) {
                echo "Kartu RFID baru berhasil didaftarkan.";
            } else {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        }
    } else {
        echo "UID atau Username tidak boleh kosong.";
    }
} else {
    echo "Form tidak lengkap. Pastikan semua input sudah diisi.";
}

// Tutup koneksi
$conn->close();
?>
