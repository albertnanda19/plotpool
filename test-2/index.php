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

// Function to delete the profile picture file
function deleteProfilePictureFile($filePath) {
    // Check if the file exists
    if (file_exists($filePath)) {
        // Delete the file
        if (unlink($filePath)) {
            echo "Foto profil berhasil dihapus.";
        } else {
            echo "Gagal menghapus foto profil.";
        }
    } else {
        echo "File foto profil tidak ditemukan.";
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the file was uploaded without errors
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        // Get the file details
        $file = $_FILES['profile_picture'];
        $fileName = $file['name'];
        $fileTmpPath = $file['tmp_name'];

        // Get the file extension
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Generate a unique file name
        $newFileName = uniqid('', true) . '.' . $fileExtension;

        // Specify the directory to save the uploaded file
        $uploadDirectory = 'profile_photos/';

        // Create the full path to save the file
        $uploadFilePath = $uploadDirectory . $newFileName;

        // Check if the file is an image
        $imageSize = getimagesize($fileTmpPath);
        if ($imageSize === false) {
            echo 'File yang diunggah bukan gambar.';
            exit();
        }

        // Check the file size (limit to 5 MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            echo 'Ukuran file terlalu besar.';
            exit();
        }

        // Limit allowed file types (e.g., JPEG, PNG)
        $allowedFormats = ['jpg', 'jpeg', 'png'];
        if (!in_array($fileExtension, $allowedFormats)) {
            echo 'Hanya file dengan format JPG, JPEG, atau PNG yang diperbolehkan.';
            exit();
        }

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
            // Connect to the database
            $koneksi = mysqli_connect("localhost", "root", "", "database");

            // Check the database connection
            if (mysqli_connect_errno()) {
                echo "Koneksi database gagal: " . mysqli_connect_error();
                exit();
            }

            // Prepare the query to retrieve the profile picture path
            $query = "SELECT profile_photo FROM users WHERE username = '$username'";

            // Execute the query
            $result = mysqli_query($koneksi, $query);

            // Check if the query is successful and if a profile picture exists
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $profilePicture = $row['profile_photo'];

                // Delete the existing profile picture file
                if (!empty($profilePicture)) {
                    deleteProfilePictureFile($profilePicture);
                }
            }

            // Prepare the query to update the profile picture path
            $query = "UPDATE users SET profile_photo = '$uploadFilePath' WHERE username = '$username'";

            // Execute the query
            $result = mysqli_query($koneksi, $query);

            // Check if the update is successful
            if ($result) {
                echo "Foto profil berhasil diperbarui.";
            } else {
                echo "Terjadi kesalahan: " . mysqli_error($koneksi);
            }

            // Close the database connection
            mysqli_close($koneksi);
        } else {
            echo 'Terjadi kesalahan saat mengunggah file.';
        }
    }

    // Check if the user status is submitted
    if (isset($_POST['submit-status'])) {
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

    // Check if the delete profile picture button is clicked
    if (isset($_POST['delete-picture'])) {
        // Connect to the database
        $koneksi = mysqli_connect("localhost", "root", "", "database");

        // Check the database connection
        if (mysqli_connect_errno()) {
            echo "Koneksi database gagal: " . mysqli_connect_error();
            exit();
        }

        // Prepare the query to retrieve the profile picture path
        $query = "SELECT profile_photo FROM users WHERE username = '$username'";

        // Execute the query
        $result = mysqli_query($koneksi, $query);

        // Check if the query is successful and if a profile picture exists
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $profilePicture = $row['profile_photo'];

            // Delete the existing profile picture file
            if (!empty($profilePicture)) {
                deleteProfilePictureFile($profilePicture);
            }

            // Prepare the query to update the profile picture path to empty
            $query = "UPDATE users SET profile_photo = '' WHERE username = '$username'";

            // Execute the query
            $result = mysqli_query($koneksi, $query);

            // Check if the update is successful
            if ($result) {
                echo "Foto profil berhasil dihapus.";
            } else {
                echo "Terjadi kesalahan: " . mysqli_error($koneksi);
            }
        }

        // Close the database connection
        mysqli_close($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <form method="POST" enctype="multipart/form-data" id="profile-picture-form">
        <div class="profile-picture">
            <?php
            // Connect to the database
            $koneksi = mysqli_connect("localhost", "root", "", "database");

            // Check the database connection
            if (mysqli_connect_errno()) {
                echo "Koneksi database gagal: " . mysqli_connect_error();
                exit();
            }

            // Prepare the query to retrieve the profile picture path
            $query = "SELECT profile_photo FROM users WHERE username = '$username'";

            // Execute the query
            $result = mysqli_query($koneksi, $query);

            // Check if the query is successful and if a profile picture exists
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $profilePicture = $row['profile_photo'];
            } else {
                $profilePicture = '';
            }

            // Close the database connection
            mysqli_close($koneksi);
            ?>
            <?php
            if (isset($profilePicture) && !empty($profilePicture)) {
                echo '<img id="foto-profil" src="' . $profilePicture . '" alt="" class="foto-profil">';
            } else {
                echo '<img id="foto-profil" src="img/no-profile.png" alt="" class="foto-profil">';
            }
            ?>
            <input type="file" id="profile-picture-input" name="profile_picture" onchange="handleFileInput(event)">
            <button type="submit" name="submit-picture">Simpan Foto Profil</button>
            <button type="submit" name="delete-picture">Hapus Foto Profil</button>
        </div>
    </form>

    <form method="POST" id="status-form">
        <div class="user-status">
            <input type="text" id="kirim-status" name="user-status" placeholder="Status">
            <button type="submit" name="submit-status">Kirim</button>
        </div>
        <div class="status">
            Status: <span id="user-status"></span>
        </div>
    </form>

    <script>
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

        // Function to handle file input change
        function handleFileInput(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.getElementById('foto-profil');
                img.src = e.target.result;
            }

            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
