<?php
// get_logs.php: Mengambil dan menampilkan semua data pemindaian RFID

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parking_db";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil semua data dari tabel rfid_data
$sql = "SELECT id, plat_nomor, uid, username, timestamp, status FROM rfid_data ORDER BY timestamp DESC";
$result = $conn->query($sql);

$data = array(); // Array untuk menyimpan hasil

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row; // Masukkan setiap baris hasil ke dalam array data
    }

    // Mengatur header agar output dalam bentuk JSON dan mengembalikan hasil
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo json_encode([]); // Mengembalikan array kosong jika tidak ada data
}

$conn->close();
?>
