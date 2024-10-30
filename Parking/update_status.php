<?php
// update_status.php: Memperbarui status user (Diizinkan/Diblokir)

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parking_db";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data JSON yang dikirim dari fetch
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$status = $data['status'];

// Update status di database
$sql = "UPDATE rfid_data SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);

$response = array();

if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

$stmt->close();
$conn->close();

// Mengembalikan response dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
