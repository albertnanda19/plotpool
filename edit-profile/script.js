function toHome(){
    window.location.href = "../home/index.html";
}
function toTitles(){
    window.location.href = "../titles-page/index.html";
}

function logout() {
    swal({
        title: "Are you sure you want to logout?",
        text: "You will be redirected to the login page",
        buttons: true,
        dangerMode: true,
    })
    .then((willLogout) => {
        if (willLogout) {
            // Mengirim permintaan POST ke file PHP untuk logout
            fetch('../logout.php', {
                method: 'POST'
            })
            .then(response => {
                // Mengarahkan pengguna ke halaman login atau halaman tujuan setelah logout
                window.location.href = "../index.html";
            })
            .catch(error => {
                console.log(error);
                swal("Error", "Failed to logout", "error");
            });
        } else {
            swal("Logout cancelled!");
        }
    });
}

let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                var status = response.status;

                // Tampilkan status di dalam elemen span
                document.getElementById('user-status').innerHTML = status;
            }
        };
        xhr.open("GET", "get_status.php", true); // Ganti "get_status.php" dengan URL yang sesuai
        xhr.send();
