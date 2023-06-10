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
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Nunito:ital,wght@0,400;0,700;1,400&family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="img/logo-shortcut.png" >
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
                    <!-- <img src="img/no-profile.png" onclick="direct()" alt="img/no-profile.png"> -->
                    <?php
                        if (isset($profilePicture) && !empty($profilePicture)) {
                            echo '<img id="foto-profil" src="' . $profilePicture . '" alt="" class="foto-profil">';
                        } else {
                            echo '<img id="foto-profil" src="img/no-profile.png" alt="" class="foto-profil">';
                        }
                    ?>
                    <div class="nama-user">
                        <div class="nama" id="username-id"><div class="nama"><?php echo $username; ?></div></div>
                        <div class="status"><?php echo $status; ?></div>
                    </div>
                </div>
                <i class='bx bx-log-out cursor-pointer' id="log-out" onclick="logout()" ></i>
            </div>
        </div>
    </div>

    <div class="halaman-home">
        <h3>Popular Title</h3>
        <section class="popular-title">
            <div class="popular-title-box">
                <figure class="popular-title-img">
                    <img src="img/popular-title-novel-1.jpeg" alt="">
                </figure>
                <div class="popular-title-konten">
                    <div class="info-popular-title">
                        <h1 class="h1 judul-popular-title">Tentang Kamu</h1>

                        <div class="detail-rating">
                            <div class="info-genre">
                                <a class="genre genre-fill" href="#">Fiksi</a>
                            </div>
                        </div>

                        <p class="sinopsis">
                            Tentang Kamu, merupakan novel karya Tere Liye. Novel tersebut menceritakan kisah hidup wanita dari ...
                        </p>

                        <div class="detail-action">
                            <button class="btn btn-read" onclick="seeMore()">
                                    <ion-icon name="play"></ion-icon>
                                    <span>See More</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="novels-latest-update">
            <div class="latest-update">
                <div class="latest-bar">
                    <h4>Latest Update</h4>
                </div>
            </div>
            <div class="novels-grid">
                <div class="novels-card">
                    <div class="novels-head">
                        <a href=""><img src="img/latest-novel-1.jpg" alt="" class="card-img"></a>
                        <div class="novels-overlay">
                            <div class="novels-bookmark">
                                <ion-icon name="bookmark-outline"></ion-icon>
                            </div>

                            <div class="novels-rating">
                                <ion-icon name="star-outline"></ion-icon>
                                <span>6.4</span>
                            </div>
                        </div>
                    </div>

                    <div class="novels-body">
                        <h3 class="novels-title">Twist of Fate</h3>

                        <div class="novels-info">
                            <span class="genre">Adult Romance</span>
                            <span class="year">2021</span>
                        </div>
                    </div>
                </div>

                <div class="novels-card">
                    <div class="novels-head">
                        <img src="img/latest-novel-2.jpg" alt="" class="card-img">

                        <div class="novels-overlay">
                            <div class="novels-bookmark">
                                <ion-icon name="bookmark-outline"></ion-icon>
                            </div>

                            <div class="novels-rating">
                                <ion-icon name="star-outline"></ion-icon>
                                <span>6.4</span>
                            </div>

                            <!-- <div class="novels-read">
                                <ion-icon name="play-circle-outline"></ion-icon>
                            </div> -->
                        </div>
                    </div>

                    <div class="novels-body">
                        <h3 class="novels-title">Alita @Heart</h3>

                        <div class="novels-info">
                            <span class="genre">Adult Romance</span>
                            <span class="year">2021</span>
                        </div>
                    </div>
                </div>

                <div class="novels-card">
                    <div class="novels-head">
                        <img src="img/latest-novel-3.jpg" alt="" class="card-img">

                        <div class="novels-overlay">
                            <div class="novels-bookmark">
                                <ion-icon name="bookmark-outline"></ion-icon>
                            </div>

                            <div class="novels-rating">
                                <ion-icon name="star-outline"></ion-icon>
                                <span>6.4</span>
                            </div>

                            <!-- <div class="novels-read">
                                <ion-icon name="play-circle-outline"></ion-icon>
                            </div> -->
                        </div>
                    </div>

                    <div class="novels-body">
                        <h3 class="novels-title">Clement</h3>

                        <div class="novels-info">
                            <span class="genre">Adult Romance</span>
                            <span class="year">2021</span>
                        </div>
                    </div>
                </div>

                <div class="novels-card">
                    <div class="novels-head">
                        <img src="img/latest-novel-4.jpg" alt="" class="card-img">

                        <div class="novels-overlay">
                            <div class="novels-bookmark">
                                <ion-icon name="bookmark-outline"></ion-icon>
                            </div>

                            <div class="novels-rating">
                                <ion-icon name="star-outline"></ion-icon>
                                <span>6.4</span>
                            </div>

                            <!-- <div class="novels-read">
                                <ion-icon name="play-circle-outline"></ion-icon>
                            </div> -->
                        </div>
                    </div>

                    <div class="novels-body">
                        <h3 class="novels-title">Twist of Fate</h3>

                        <div class="novels-info">
                            <span class="genre">Adult Romance</span>
                            <span class="year">2021</span>
                        </div>
                    </div>
                </div>

                <div class="novels-card">
                    <div class="novels-head">
                        <img src="img/latest-novel-5.jpg" alt="" class="card-img">

                        <div class="novels-overlay">
                            <div class="novels-bookmark">
                                <ion-icon name="bookmark-outline"></ion-icon>
                            </div>

                            <div class="novels-rating">
                                <ion-icon name="star-outline"></ion-icon>
                                <span>6.4</span>
                            </div>

                            <!-- <div class="novels-read">
                                <ion-icon name="play-circle-outline"></ion-icon>
                            </div> -->
                        </div>
                    </div>

                    <div class="novels-body">
                        <h3 class="novels-title">Twist of Fate</h3>

                        <div class="novels-info">
                            <span class="genre">Adult Romance</span>
                            <span class="year">2021</span>
                        </div>
                    </div>
                </div>

                <div class="novels-card">
                    <div class="novels-head">
                        <img src="img/latest-novel-6.jpg" alt="" class="card-img">

                        <div class="novels-overlay">
                            <div class="novels-bookmark">
                                <ion-icon name="bookmark-outline"></ion-icon>
                            </div>

                            <div class="novels-rating">
                                <ion-icon name="star-outline"></ion-icon>
                                <span>6.4</span>
                            </div>

                            <!-- <div class="novels-read">
                                <ion-icon name="play-circle-outline"></ion-icon>
                            </div> -->
                        </div>
                    </div>

                    <div class="novels-body">
                        <h3 class="novels-title">Twist of Fate</h3>

                        <div class="novels-info">
                            <span class="genre">Adult Romance</span>
                            <span class="year">2021</span>
                        </div>
                    </div>
                </div>

                <div class="novels-card">
                    <div class="novels-head">
                        <img src="img/latest-novel-7.jpg" alt="" class="card-img">

                        <div class="novels-overlay">
                            <div class="novels-bookmark">
                                <ion-icon name="bookmark-outline"></ion-icon>
                            </div>

                            <div class="novels-rating">
                                <ion-icon name="star-outline"></ion-icon>
                                <span>6.4</span>
                            </div>

                            <!-- <div class="novels-read">
                                <ion-icon name="play-circle-outline"></ion-icon>
                            </div> -->
                        </div>
                    </div>

                    <div class="novels-body">
                        <h3 class="novels-title">Twist of Fate</h3>

                        <div class="novels-info">
                            <span class="genre">Adult Romance</span>
                            <span class="year">2021</span>
                        </div>
                    </div>
                </div>

                <div class="novels-card">
                    <div class="novels-head">
                        <img src="img/latest-novel-8.jpg" alt="" class="card-img">

                        <div class="novels-overlay">
                            <div class="novels-bookmark">
                                <ion-icon name="bookmark-outline"></ion-icon>
                            </div>

                            <div class="novels-rating">
                                <ion-icon name="star-outline"></ion-icon>
                                <span>6.4</span>
                            </div>

                            <!-- <div class="novels-read">
                                <ion-icon name="play-circle-outline"></ion-icon>
                            </div> -->
                        </div>
                    </div>

                    <div class="novels-body">
                        <h3 class="novels-title">Twist of Fate</h3>

                        <div class="novels-info">
                            <span class="genre">Adult Romance</span>
                            <span class="year">2021</span>
                        </div>
                    </div>
                </div>

                <div class="novels-card">
                    <div class="novels-head">
                        <img src="img/latest-novel-9.jpg" alt="" class="card-img">

                        <div class="novels-overlay">
                            <div class="novels-bookmark">
                                <ion-icon name="bookmark-outline"></ion-icon>
                            </div>

                            <div class="novels-rating">
                                <ion-icon name="star-outline"></ion-icon>
                                <span>6.4</span>
                            </div>
                        </div>
                    </div>

                    <div class="novels-body">
                        <h3 class="novels-title">Twist of Fate</h3>

                        <div class="novels-info">
                            <span class="genre">Adult Romance</span>
                            <span class="year">2021</span>
                        </div>
                    </div>
                </div>

                <div class="novels-card">
                    <div class="novels-head">
                        <img src="img/latest-novel-10.jpg" alt="" class="card-img">

                        <div class="novels-overlay">
                            <div class="novels-bookmark">
                                <ion-icon name="bookmark-outline"></ion-icon>
                            </div>

                            <div class="novels-rating">
                                <ion-icon name="star-outline"></ion-icon>
                                <span>6.4</span>
                            </div>

                            <!-- <div class="novels-read">
                                <ion-icon name="play-circle-outline"></ion-icon>
                            </div> -->
                        </div>
                    </div>

                    <div class="novels-body">
                        <h3 class="novels-title">Twist of Fate</h3>

                        <div class="novels-info">
                            <span class="genre">Adult Romance</span>
                            <span class="year">2021</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <script>
        function direct()
        {
            window.location.href = '../edit-profile/index.php';
        }
    </script>
</body>
</html>