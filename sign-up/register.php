<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Validate form data
  if (empty($email) || empty($username) || empty($password)) {
    echo "Please fill in all the fields.";
  } else {
    // Check if email or username already exists
    $userData = file_get_contents("users.txt");
    $users = explode("\n", $userData);
    $emailExists = false;
    $usernameExists = false;
    foreach ($users as $user) {
      $user_data = explode(",", $user);
      $existing_email = $user_data[0];
      $existing_username = $user_data[1];
      if ($email === $existing_email) {
        $emailExists = true;
        break;
      }
      if ($username === $existing_username) {
        $usernameExists = true;
        break;
      }
    }

    if ($emailExists) {
      echo <<<HTML
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script>
        swal({
          title: "Registration Failed!",
          text: "Email is already registered.",
          icon: "error",
          button: "OK",
        });
      </script>
HTML;
    } else if ($usernameExists) {
      echo <<<HTML
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script>
        swal({
          title: "Registration Failed!",
          text: "Username is already taken.",
          icon: "error",
          button: "OK",
        });
      </script>
HTML;
    } else {
      // Store user data in a file
      $userData = "$username,$password, $email\n";
      file_put_contents("users.txt", $userData, FILE_APPEND);

      echo <<<HTML
      <script>
        window.location.href = 'clone-page/index.html';
      </script>
      HTML;
      }
    }
}
?>