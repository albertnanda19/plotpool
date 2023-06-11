<?php
    // Memeriksa apakah form telah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Menghubungkan ke database
        $conn = new mysqli("localhost", "root", "", "database");

        // Memeriksa koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Mengambil data dari form
        $judul = $_POST["judul"];
        $sampul = $_FILES["sampul"]["name"];
        $genre = $_POST["genre"];
        $rating_usia = $_POST["rating_usia"];
        $tahun_terbit = $_POST["tahun_terbit"];
        $sinopsis = $_POST["sinopsis"];
        $file_pdf = $_POST["file_pdf"];

        // Memindahkan file sampul ke folder yang diinginkan
        $targetDir = "../info_novel/sampul/";
        $targetFile = $targetDir . basename($sampul);
        move_uploaded_file($_FILES["sampul"]["tmp_name"], $targetFile);

        // Menyimpan data ke dalam tabel novel
        $sql = "INSERT INTO novel (judul, sampul, genre, rating_usia, tahun_terbit, sinopsis, file_pdf)
                VALUES ('$judul', '$sampul', '$genre', '$rating_usia', $tahun_terbit, '$sinopsis', '$file_pdf')";

        if ($conn->query($sql) === TRUE) {
            echo "Data novel berhasil disimpan.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Menutup koneksi
        $conn->close();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Input Novel</title>
</head>
<body>
    <h2>Form Input Novel</h2>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <label for="judul">Judul:</label><br>
        <input type="text" id="judul" name="judul" required><br><br>
        
        <label for="sampul">Sampul:</label><br>
        <input type="file" id="sampul" name="sampul" required><br><br>
        
        <label for="genre">Genre:</label><br>
        <input type="text" id="genre" name="genre" required><br><br>
        
        <label for="rating_usia">Rating Usia:</label><br>
        <select id="rating_usia" name="rating_usia" required>
            <option value="">Pilih Rating Usia</option>
            <option value="G">G (General)</option>
            <option value="PG">PG (Parental Guidance Suggested)</option>
            <option value="PG-13">PG-13 (Parents Strongly Cautioned)</option>
            <option value="R">R (Restricted)</option>
            <option value="NC-17">NC-17 (Adults Only)</option>
        </select><br><br>
        
        <label for="tahun_terbit">Tahun Terbit:</label><br>
        <input type="number" id="tahun_terbit" name="tahun_terbit" required><br><br>
        
        <label for="sinopsis">Sinopsis:</label><br>
        <textarea id="sinopsis" name="sinopsis" required></textarea><br><br>
        
        <label for="file_pdf">Link Download PDF:</label><br>
        <input type="text" id="file_pdf" name="file_pdf" required><br><br>
        
        <input type="submit" value="Simpan">
    </form>
</body>
</html>
