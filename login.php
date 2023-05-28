<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Validate form data
  if (empty($username) || empty($password)) {
    echo "Please fill in all the fields.";
  } else {
    // Connect to MySQL
    $conn = new mysqli("localhost", "root", "", "database");

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    
    // Execute SQL statement
    $stmt->execute();

    // Check if a row is returned
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
      // Start session and set username
      session_start();
      $_SESSION['username'] = $username;

      // Redirect to welcome page
      header("Location: home/index.php");
      exit;
    } else {
      echo "Invalid username or password.";
    }

    // Close database connection
    $stmt->close();
    $conn->close();
  }
}
?>
