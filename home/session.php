<?php 

    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: ../index.html");
        exit();
    }else{
        echo <<<HTML
            <script>
                window.location.href = "index.html"
            </script>
        HTML;
    }

?>