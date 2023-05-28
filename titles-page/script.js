function toAboutus(){
    window.location.href = "../about-developers-page/index.html";
}

function toHome(){
    window.location.href = "../home/index.html";
}

function logOut() {
    swal({
        title: "Are you sure you want to logout?",
        text: "You will be redirected to the login page",
        buttons: true,
        dangerMode: true,
    })
    .then((willLogout) => {
        if (willLogout) {
        window.location.href = "../sign-in/index.html";
        } else {
        swal({
            title: "Logout cancelled!"
        });
        }
    });
}