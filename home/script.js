function seeMore(){

window.location.href = "../visit-novel/index.html";

};

function toAboutus(){
    window.location.href = "../about-developers-page/index.html";
}

function toHome(){
    window.location.href = "../home/index.html";
}

function toTitles(){
    window.location.href = "../titles-page/index.html";
}

document.getElementById("log-out").addEventListener("click", logout);

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

function direct()
{
    window.location.href = '../edit-profile/index.php';
}
