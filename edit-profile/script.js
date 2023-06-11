function toHome(){
    window.location.href = "../home/index.php";
}
function toTitles(){
    window.location.href = "../titles-page/index.html";
}

function logout() {
    swal({
        title: "Apakah anda yakin ingin logout?",
        text: "Anda akan diarahkan ke halaman login",
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
            swal("Logout dibatalkan!");
        }
    });
}

let xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        var status = response.status;
        document.getElementById('user-status').innerHTML = status;
        }
    };
    xhr.open("GET", "get_status.php", true); 
    xhr.send();
