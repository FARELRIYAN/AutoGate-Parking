<?php
// register.php: Form pendaftaran kartu baru
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New RFID Card</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        /* Header */
        header {
            background-color: #4f9fff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
        }

        /* Sidebar Styling */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 60px;
            left: 0;
            background-color: #f0f8ff;
            padding-top: 20px;
            transition: 0.3s;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: #333;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #ddd;
        }

        .sidebar .profile-section {
            padding: 10px;
            background-color: #e6f7ff;
            margin-bottom: 15px;
        }

        .profile-section img {
            width: 60px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .profile-section h3 {
            margin: 0;
            font-size: 16px;
        }

        .profile-section p {
            font-size: 14px;
            color: #777;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px; /* Menambahkan margin sesuai lebar sidebar */
            padding: 20px;
            margin-top: 60px;
            transition: margin-left 0.3s; /* Smooth transition saat sidebar disembunyikan */
        }

        /* Footer */
        footer {
            background-color: #4f9fff;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
        }

        /* Search Bar */
        .search-bar {
            margin: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4f9fff;
            color: white;
        }

        /* Time display */
        .time-display {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%; 
            max-width: 500px; 
        }

        .btn-primary {
            margin-top: 20px; 
        }

        /* Sidebar collapse button */
        #sidebarCollapse {
            position: absolute;
            top: 20px;
            left: 260px;
            background-color: #4f9fff;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: left 0.3s;
        }

    </style>
    <script>
        let lastUID = '';

        function startRegistration() {
            fetch('start_registration.php')
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                })
                .catch(error => console.error('Error starting registration:', error));
        }

        function checkRegisterUID() {
            fetch('get_register_uid.php')
                .then(response => response.json())
                .then(data => {
                    if (data.uid && data.uid !== lastUID) {
                        document.getElementById('uid').value = data.uid;
                        lastUID = data.uid;
                        clearRegisterUID(data.uid);
                    }
                })
                .catch(error => console.error('Error fetching register UID:', error));
        }

        function clearRegisterUID(uid) {
            fetch('clear_register_uid.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ uid: uid })
            })
            .then(response => response.text())
            .then(data => {
                console.log('Register UID cleared:', data);
            })
            .catch(error => console.error('Error clearing register UID:', error));
        }

        window.onload = function() {
            startRegistration();
            setInterval(checkRegisterUID, 1000); // Mengecek setiap 1 detik
        };

        // Sidebar collapse functionality
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                if ($('#sidebar').hasClass('active')) {
                    $('.main-content').css('margin-left', '0'); /* Konten full saat sidebar disembunyikan */
                    $('#sidebarCollapse').css('left', '10px'); /* Geser tombol collapse */
                } else {
                    $('.main-content').css('margin-left', '260px'); /* Kembalikan margin saat sidebar muncul */
                    $('#sidebarCollapse').css('left', '260px'); /* Kembalikan tombol collapse */
                }
            });
        });
    </script>
</head>
<body>
    <!-- Header Section -->
    <header>
        RFID Parking System Register Card
    </header>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="index.html">Dashboard</a>
        <a href="register.php">Register Card</a>
    </div>

    <!-- Sidebar Collapse Button -->
    <button id="sidebarCollapse">Collapse</button>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Register New RFID Card</h2>

        <div class="container">
            <form action="register_card.php" method="post">
                <div class="form-group">
                    <label for="uid">UID:</label>
                    <input type="text" id="uid" name="uid" class="form-control" required readonly>
                </div>

                <div class="form-group">
                    <label for="plat_nomor">Plat Nomor:</label>
                    <input type="text" id="plat_nomor" name="plat_nomor" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="username">Nama:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="kelas">Kelas:</label>
                    <select id="kelas" name="kelas" class="form-control">
                        <option>XI PPLG 1</option>
                        <option>XI PPLG 2</option>
                        <option>XI PPLG 3</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" class="form-control">
                        <option value="allowed">Izinkan</option>
                        <option value="denied">Tolak</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>

    
</body>
</html>
