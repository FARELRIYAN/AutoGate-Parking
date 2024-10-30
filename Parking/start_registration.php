<?php
// start_registration.php: Mengaktifkan mode pendaftaran

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parking_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "UPDATE settings SET registration_mode=1 WHERE id=1";

if ($conn->query($sql) === TRUE) {
    echo "Mode pendaftaran diaktifkan.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
