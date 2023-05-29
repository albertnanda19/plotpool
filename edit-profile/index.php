<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
  // Redirect to the login page
    header("Location: ../index.html");
    exit();
}

// Retrieve the username from the session
$username = $_SESSION['username'];

// Upload foto profi


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the status input value
    $status = $_POST['user-status'];

    // Connect to the database
    $koneksi = mysqli_connect("localhost", "root", "", "database");

    // Check the database connection
    if (mysqli_connect_errno()) {
        echo "Koneksi database gagal: " . mysqli_connect_error();
        exit();
    }

    // Prepare the query to update the status
    $query = "UPDATE users SET status = '$status' WHERE username = '$username'";

    // Execute the query
    $result = mysqli_query($koneksi, $query);

    // Check if the update is successful
    if ($result) {
        echo "Status berhasil diperbarui.";
    } else {
        echo "Terjadi kesalahan: " . mysqli_error($koneksi);
    }

    // Close the database connection
    mysqli_close($koneksi);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlotPool</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Nunito:ital,wght@0,400;0,700;1,400&family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="img/logo-shortcut.png" >
</head>
<body>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="sidebar">
        <div class="sidebar-logo">
            <div class="logo">
                <img class="gambar-logo" src="img/logo.png" alt="">
                <div class="nama-logo">PlotPool</div>
            </div>
        </div>

        <ul class="daftar-menu">
            <li class="pencarian">
                <i class="bx bx-search"></i>
                <input type="text" placeholder="Search Novel...">
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-home'></i>
                    <span class="nama-menu" onclick="toHome()">Home</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bx bx-book-open"></i>
                    <span class="nama-menu" onclick="toTitles()">Title</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bx bx-crosshair"></i>
                    <span class="nama-menu">Random</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-info"></i>
                    <span class="nama-menu">About Us</span>
                </a>
            </li>
        </ul>

        <div class="info-profil">
            <div class="profil">
                <div class="detail-profil">
                    <img src="img/no-profile.png" alt="img/no-profile.png">
                    <div class="nama-user">
                        <div class="nama" id="username-id"><div class="nama"><span id="nama-user"></span></div></div>
                        <div class="status"><span id='user-status'></span></div>
                    </div>
                </div>
                <i class='bx bx-log-out cursor-pointer' id="log-out" onclick="logout()"></i>
            </div>
        </div>  
    </div>

    <div class="halaman-info">
        <div class="container">
            <form method="POST" action="index.php" enctype="multipart/form-data">
                <div class="profile-picture">
                    <img id="foto-profil" src="img/no-profile.png" alt="" class="foto-profil">
                    <i class="bx bx-pencil edit-photo-icon" onclick="uploadProfilePicture()"></i>
                    <input type="file" id="profile-picture-input" name="profile_picture" style="display: none;" onchange="handleFileInput(event)">
                </div>
                <input type="submit" value="Simpan">
            </form>
            <div class="user-name">
                <p><span id="side-nama-user" class="nama-user"></span></p><br>
                <!-- <input type="text" id="kirim-status" name="user-status"> -->
            </div>
            <div class="user-status">
                <input type="text" id="kirim-status" name="user-status" placeholder="Status">
                <button type="submit">Kirim</button>
            </div>
        </div>
    </div>
    </form>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nama = '<?php echo $username; ?>';
            document.getElementById('nama-user').textContent = nama;
            document.getElementById('side-nama-user').textContent = nama;
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil data status dari server menggunakan AJAX
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var status = response.status;

                    // Tampilkan status di dalam elemen span
                    document.getElementById('user-status').innerHTML = status;
                }
            };
            xhr.open("GET", "../get_status.php", true); // Ganti "get_status.php" dengan URL yang sesuai
            xhr.send();
        });

        function uploadProfilePicture() {
            // Trigger the file input
            document.getElementById('profile-picture-input').click();
        }

        // Function to handle file input change
        function handleFileInput(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
                // Display the selected image
                const imgElement = document.getElementById('foto-profil');
                imgElement.src = e.target.result;
            };

            // Read the uploaded file as a Data URL
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>