<?php
// clear_register_uid.php: Menghapus UID yang telah diambil

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parking_db";

// Mendapatkan UID dari permintaan POST
$input = json_decode(file_get_contents('php://input'), true);
$uid = $input['uid'];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menghapus UID spesifik dari register_uid
$sql = "DELETE FROM register_uid WHERE uid='$uid'";
if ($conn->query($sql) === TRUE) {
    echo "UID berhasil dihapus.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
