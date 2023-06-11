<?php 
    session_start();

    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        header("Location: ../index.html");
        exit();
    }

    $username = $_SESSION['username'];

    $koneksi = new mysqli("localhost", "root", "", "database");

    if(isset($_GET["id"]))
    {
        $id = $_GET["id"];

        if($koneksi->connect_error)
        {
            die("Koneksi gagal: ".$koneksi->connect_error);
        }

        $sql = "SELECT * FROM novel WHERE id = '$id'";
        $hasil = $koneksi->query($sql);

        if($hasil->num_rows > 0)
        {
            $row = $hasil->fetch_assoc();
            $judul = $row["judul"];
            $sampul = $row["sampul"];
            $genre = $row["genre"];
            $rating_usia = $row["rating_usia"];
            $tahun_terbit = $row["tahun_terbit"];
            $sinopsis = $row["sinopsis"];
            $file_pdf = $row["file_pdf"];
        }
    }

    $query = "SELECT status FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $status = $row['status'];
    } else {
        $response = array('error' => 'Failed to retrieve status: ' . mysqli_error($koneksi));
        echo json_encode($response);
    }

    mysqli_close($koneksi);
?>



<!DOCTYPE html>
<html lang="en">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="script.js"></script>

<head>
    <link rel="stylesheet" href="dist/sweetalert2.min.css">
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
    <script src="script.js"></script>
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
                    <img src="img/no-profile.png" alt="img/no-profile.png">
                    <div class="nama-user">
                        <div class="nama" id="username-id"><?php echo $username; ?></div>
                        <div class="status"><?php echo $status; ?></div>
                    </div>
                </div>
                <i class='bx bx-log-out cursor-pointer' id="log-out" onclick="logOut()"></i>
            </div>
        </div>    
    </div>

    <div class="halaman-review">
        <section class="gambaran-novel">
            <div class="novel-content ">
                <!-- <img src="img/popular-title-novel-bg-1.jpeg" alt="" class="novel-review-bg">  -->
                <figure class="novel-img">
                    <img src="sampul/<?php echo $sampul; ?>" alt="No Image">
                </figure>
                <div class="info-detail-novel">
                    <div class="info-novel">
                        <h1 class="h1 judul-novel"><?php echo $judul ?></h1>

                        <div class="detail-rating">
                            <div class="info-rating">
                                <div class="rating rating-fill"><?php echo $rating_usia; ?></div>
                            </div>

                            <div class="info-genre">
                                <a href="#"><?php echo $genre; ?></a>
                            </div>

                            <div class="tahun-rilis">
                                <div>
                                    <ion-icon name="calender-outline" ></ion-icon>
                                    <time datetime="2021"><?php echo $tahun_terbit; ?></time>
                                </div>
                            </div>
                        </div>
                        <p class="sinopsis"><?php echo $sinopsis; ?></p>
                        <div class="detail-action">
                            <button class="share">
                                <a href="#">
                                    <ion-icon name="share-social"></ion-icon>
                                    <span>Share</span>
                                </a>
                            </button>
                            <button class="btn btn-download">
                                <a href="#">
                                    <ion-icon name="download-outline"></ion-icon>
                                    <span>Download</span>
                                </a>
                            </button>
                        </div>
                        <a href="#" class="tombol-read">
                            <span onclick="toRead()">Read Now</span>
                            <ion-icon name="play"></ion-icon>
                        </a>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="script.js"></script>

</body>
</html>