<?php
// get_register_uid.php: Mengembalikan UID terbaru untuk pendaftaran

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parking_db";

header('Content-Type: application/json');

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['uid' => '']);
    exit;
}

// Menggunakan 'id' sebagai acuan pengurutan
$sql = "SELECT uid FROM register_uid ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

$data = ['uid' => ''];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $data['uid'] = $row['uid'];
}

echo json_encode($data);

$conn->close();
?>
