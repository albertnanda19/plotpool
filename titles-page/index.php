<?php
    session_start();

    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        header("Location: ../index.html");
        exit();
    }

    $username = $_SESSION['username'];
    $koneksi = mysqli_connect("localhost", "root", "", "database");
    if (mysqli_connect_errno()) {
        echo "Koneksi database gagal: " . mysqli_connect_error();
        exit();
    }
    $query_profilePicture = "SELECT profile_photo FROM users WHERE username = '$username'";
    $hasil_profilePicture = mysqli_query($koneksi, $query_profilePicture);
    
    if($hasil_profilePicture && mysqli_num_rows($hasil_profilePicture) > 0)
    {
        $row_profilePicture = mysqli_fetch_assoc($hasil_profilePicture);
        $profilePicture = $row_profilePicture['profile_photo']; 
    }else{
        $profilePicture = '';
    }

    $query_status = "SELECT status FROM users WHERE username = '$username'";
    $hasil_status = mysqli_query($koneksi, $query_status);

    if($hasil_status)
    {
        $row_status = mysqli_fetch_assoc($hasil_status);
        $status = $row_status['status'];
    }else{
        $response = array('error' => 'Gagal menampilkan status: '.mysqli_error($koneksi));
        echo json_encode($response);
    }
    mysqli_close($koneksi); 
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlotPool</title>
    <script src="script.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Nunito:ital,wght@0,400;0,700;1,400&family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="img\logo-shortcut.png" >
</head>
<body>
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
                <a href="../home/index.php">
                    <i class='bx bx-home'></i>
                    <span class="nama-menu" onclick="toHome()">Home</span>
                </a>
            </li>
            <li>
                <a href="../titles-page/index.php">
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
                <a href="../about-developers-page/index.php">
                    <i class="fa fa-info"></i>
                    <span class="nama-menu">About Us</span>
                </a>
            </li>
        </ul>
        
        <div class="info-profil">
            <div class="profil">
                <div class="detail-profil">
                    <?php
                        if (isset($profilePicture) && !empty($profilePicture)) {
                            echo '<img id="foto-profil" src="' . $profilePicture . '" alt="" class="foto-profil">';
                        } else {
                            echo '<img id="foto-profil" src="img/no-profile.png" alt="" class="foto-profil">';
                        }
                    ?>
                    <div class="nama-user">
                        <div class="nama"><?php echo $username; ?></div>
                        <div class="status"><?php echo $status; ?></div>
                    </div>
                </div>
                <i class='bx bx-log-out cursor-pointer' id="log-out" onclick="logOut()"></i>
            </div>
        </div>
    </div>

    <div class="halaman-novels">
        <div class="kumpulan-novels">
            <div class="novel-card">
                <img src="img/novel-1.jpg" alt="">
                <div>
                    <h2>When I Look Into Your Eyes</h2>
                    <h3>by Netty Virgiantini</h3>
                    <!-- <p>
                        “Aku benar-benar bingung. Mengapa ibuku selalu menghindariku dan ketakutan setiap kali melihat mataku? Mengapa Arya dan Arga justru jatuh cinta padaku karena penasaran dengan mataku? Dan mengapa ada laki-laki yang tiba-tiba marah dan mengusirku hanya karena mataku? Sudah kucoba becermin berkali-kali, mengamati bayangan mataku sendiri yang memantul dalam cermin, tidak ada yang aneh. Memang sih, bentuknya lumayan bundar, tapi masih wajar. Menurutku, tidak ada yang mencurigakan dari mataku. Ternyata, ada kisah yang tersembunyi dalam sepasang mataku. Kisah pedih yang membuat hidup ibuku terbelenggu trauma masa lalu. Selama ini, semua sengaja menyembunyikan misteri itu dariku. Akhirnya aku tahu, serapat apa pun manusia menyembunyikan kebenaran, Tuhan akan selalu punya cara untuk menunjukkannya….”
                    </p> -->
                    <button>Read Now</button>
                </div>
            </div>

            <div class="novel-card">
                <img src="img/novel-1.jpg" alt="">
                <div>
                    <h2>When I Look Into Your Eyes</h2>
                    <h3>by Netty Virgiantini</h3>
                    <button>Read Now</button>
                </div>
            </div>

            <div class="novel-card">
                <img src="img/novel-1.jpg" alt="">
                <div>
                    <h2>When I Look Into Your Eyes</h2>
                    <h3>by Netty Virgiantini</h3>
                    <!-- <p>
                        “Aku benar-benar bingung. Mengapa ibuku selalu menghindariku dan ketakutan setiap kali melihat mataku? Mengapa Arya dan Arga justru jatuh cinta padaku karena penasaran dengan mataku? Dan mengapa ada laki-laki yang tiba-tiba marah dan mengusirku hanya karena mataku? Sudah kucoba becermin berkali-kali, mengamati bayangan mataku sendiri yang memantul dalam cermin, tidak ada yang aneh. Memang sih, bentuknya lumayan bundar, tapi masih wajar. Menurutku, tidak ada yang mencurigakan dari mataku. Ternyata, ada kisah yang tersembunyi dalam sepasang mataku. Kisah pedih yang membuat hidup ibuku terbelenggu trauma masa lalu. Selama ini, semua sengaja menyembunyikan misteri itu dariku. Akhirnya aku tahu, serapat apa pun manusia menyembunyikan kebenaran, Tuhan akan selalu punya cara untuk menunjukkannya….”
                    </p> -->
                    <button>Read Now</button>
                </div>
            </div>

            <div class="novel-card">
                <img src="img/novel-1.jpg" alt="">
                <div>
                    <h2>When I Look Into Your Eyes</h2>
                    <h3>by Netty Virgiantini</h3>
                    <button>Read Now</button>
                </div>
            </div>

            <div class="novel-card">
                <img src="img/novel-1.jpg" alt="">
                <div>
                    <h2>When I Look Into Your Eyes</h2>
                    <h3>by Netty Virgiantini</h3>
                    <!-- <p>
                        “Aku benar-benar bingung. Mengapa ibuku selalu menghindariku dan ketakutan setiap kali melihat mataku? Mengapa Arya dan Arga justru jatuh cinta padaku karena penasaran dengan mataku? Dan mengapa ada laki-laki yang tiba-tiba marah dan mengusirku hanya karena mataku? Sudah kucoba becermin berkali-kali, mengamati bayangan mataku sendiri yang memantul dalam cermin, tidak ada yang aneh. Memang sih, bentuknya lumayan bundar, tapi masih wajar. Menurutku, tidak ada yang mencurigakan dari mataku. Ternyata, ada kisah yang tersembunyi dalam sepasang mataku. Kisah pedih yang membuat hidup ibuku terbelenggu trauma masa lalu. Selama ini, semua sengaja menyembunyikan misteri itu dariku. Akhirnya aku tahu, serapat apa pun manusia menyembunyikan kebenaran, Tuhan akan selalu punya cara untuk menunjukkannya….”
                    </p> -->
                    <button>Read Now</button>
                </div>
            </div>

            <div class="novel-card">
                <img src="img/novel-1.jpg" alt="">
                <div>
                    <h2>When I Look Into Your Eyes</h2>
                    <h3>by Netty Virgiantini</h3>
                    <button>Read Now</button>
                </div>
            </div>

            <div class="novel-card">
                <img src="img/novel-1.jpg" alt="">
                <div>
                    <h2>When I Look Into Your Eyes</h2>
                    <h3>by Netty Virgiantini</h3>
                    <!-- <p>
                        “Aku benar-benar bingung. Mengapa ibuku selalu menghindariku dan ketakutan setiap kali melihat mataku? Mengapa Arya dan Arga justru jatuh cinta padaku karena penasaran dengan mataku? Dan mengapa ada laki-laki yang tiba-tiba marah dan mengusirku hanya karena mataku? Sudah kucoba becermin berkali-kali, mengamati bayangan mataku sendiri yang memantul dalam cermin, tidak ada yang aneh. Memang sih, bentuknya lumayan bundar, tapi masih wajar. Menurutku, tidak ada yang mencurigakan dari mataku. Ternyata, ada kisah yang tersembunyi dalam sepasang mataku. Kisah pedih yang membuat hidup ibuku terbelenggu trauma masa lalu. Selama ini, semua sengaja menyembunyikan misteri itu dariku. Akhirnya aku tahu, serapat apa pun manusia menyembunyikan kebenaran, Tuhan akan selalu punya cara untuk menunjukkannya….”
                    </p> -->
                    <button>Read Now</button>
                </div>
            </div>

            <div class="novel-card">
                <img src="img/novel-1.jpg" alt="">
                <div>
                    <h2>When I Look Into Your Eyes</h2>
                    <h3>by Netty Virgiantini</h3>
                    <button>Read Now</button>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="script.js"></script>

</body>
</html>