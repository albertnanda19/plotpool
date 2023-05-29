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

        // Delete the old profile picture from the database and folder
        // Connect to the database
        $koneksi = mysqli_connect("localhost", "root", "", "database");

        // Check the database connection
        if (mysqli_connect_errno()) {
            echo "Koneksi database gagal: " . mysqli_connect_error();
            exit();
        }

        // Prepare the query to retrieve the old profile picture path
        $query = "SELECT profile_photo FROM users WHERE username = '$username'";

        // Execute the query
        $result = mysqli_query($koneksi, $query);

        // Check if the query is successful and if a profile picture exists
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $oldProfilePicture = $row['profile_photo'];

            // Delete the old profile picture file from the folder
            if (!empty($oldProfilePicture) && file_exists($oldProfilePicture)) {
                unlink($oldProfilePicture);
            }
        }

        // Prepare the query to update the profile picture path
        $query = "UPDATE users SET profile_photo = '$uploadFilePath' WHERE username = '$username'";

        // Execute the query
        $result = mysqli_query($koneksi, $query);

        // Check if the update is successful
        if ($result) {
            // Move the uploaded file to the destination directory
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                echo "Foto profil berhasil diperbarui.";
            } else {
                echo 'Terjadi kesalahan saat mengunggah file.';
            }
        } else {
            echo "Terjadi kesalahan: " . mysqli_error($koneksi);
        }

        // Close the database connection
        // mysqli_close($koneksi);

        $status = $_POST['user-status'];

        // Connect to the database
        // $koneksi = mysqli_connect("localhost", "root", "", "database");

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
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
        // Function to handle profile picture upload
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

        // Function to delete the profile picture
        function deleteProfilePicture() {
            const imgElement = document.getElementById('foto-profil');
            const confirmation = confirm("Apakah Anda yakin ingin menghapus foto profil?");

            if (confirmation) {
                imgElement.src = "img/no-profile.png";

                // Send an AJAX request to delete the profile picture from the database and folder
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_profile_pictures.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                        } else {
                            console.error("Terjadi kesalahan saat menghapus foto profil.");
                        }
                    }
                };
                xhr.send();
            }
        }
    </script>
</head>
<body>
    <style>
        .input-profile-picture{
            display: none;
        }
    </style>
    <form method="POST" enctype="multipart/form-data">
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
            <input type="file" id="profile-picture-input" class="input-profile-picture" name="profile_picture" onchange="handleFileInput(event)">
        </div>
        <input type="button" value="Unggah Foto" onclick="uploadProfilePicture()">
        <input type="button" value="Hapus Foto" onclick="deleteProfilePicture()">
        <input type="submit" value="Simpan">
    </form>
    <div class="user-status">
        <input type="text" id="kirim-status" name="user-status" placeholder="Status">
        <button type="submit">Kirim</button>
    </div>
    <div class="status"><span id='user-status'></span></div>
</body>
</html>

<!-- =====================================BARU========================================================== -->

