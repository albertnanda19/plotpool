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
      $existing_email = $user_data[2];
      $existing_username = $user_data[0];
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
      header('Location: emailExist/index.html');
    } elseif ($usernameExists) {
      header('Location: usernameExist/index.html');
    } else {
      // Save user data to file
      $userData .= $username . ',' . $password . ',' . $email . "\n";
      file_put_contents("users.txt", $userData);

      header('Location: clone-page/index.html');
    }
  }
}
?>
