<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Validate form data
  if (empty($username) || empty($password)) {
    echo "Please fill in all the fields.";
  } else {
    // Read user data from file
    $users = file("sign-up/users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $authenticated = false;
    
    foreach ($users as $user) {
      list($storedUsername, $storedPassword) = explode(",", $user);
      
      if ($username === $storedUsername && $password === $storedPassword) {
        $authenticated = true;
        break;
      }
    }
    
    if ($authenticated) {
      // Start session and set username
      session_start();
      $_SESSION['username'] = $username;
      
      // Redirect to welcome page
      header("Location: home/index.php");
      exit;
    } else {
      echo "Invalid username or password.";
    }
  }
}
?>
