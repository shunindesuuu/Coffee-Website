<?php
$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";

// Connect to the database
$db_link = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

// Get the logged-in user's role (admin or customer) - Modify this according to your authentication logic
$userRole = $_COOKIE['type'];

// Insert chat messages
if ($userRole === "admin") {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && isset($_POST['recipient'])) {
    $newMessage = mysqli_real_escape_string($db_link, $_POST['message']);
    $recipient = mysqli_real_escape_string($db_link, $_POST['recipient']);
    $timestamp = date("Y-m-d H:i:s");

    $query = "INSERT INTO messages (sender, recipient, message, timestamp) VALUES ('Admin', '$recipient', '$newMessage', '$timestamp')";
    $insertResult = mysqli_query($db_link, $query);

    if (!$insertResult) {
      echo "Error inserting chat message: " . mysqli_error($db_link);
    }
  }
} else {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $newMessage = mysqli_real_escape_string($db_link, $_POST['message']);
    $email = $_COOKIE['email']; // Replace 'email' with the appropriate cookie name that stores the customer's email

    // Retrieve the customer's ID based on the email
    $userIdQuery = "SELECT userid FROM user WHERE email = '$email'";
    $userIdResult = mysqli_query($db_link, $userIdQuery);

    if ($userIdResult && mysqli_num_rows($userIdResult) > 0) {
      $userIdRow = mysqli_fetch_assoc($userIdResult);
      $userId = $userIdRow['userid'];

      $recipient = 'Admin';
      $timestamp = date("Y-m-d H:i:s");

      $query = "INSERT INTO messages (sender, recipient, message, timestamp) VALUES ('$userId', '$recipient', '$newMessage', '$timestamp')";
      $insertResult = mysqli_query($db_link, $query);

      if (!$insertResult) {
        echo "Error inserting chat message: " . mysqli_error($db_link);
      }
    } else {
      // Handle the case when the customer's ID is not found
      echo "Error retrieving customer's ID.";
      mysqli_close($db_link);
      exit();
    }
  }
}

// Retrieve chat messages based on user role and recipient
if ($userRole === "admin") {
  $query = "SELECT * FROM messages";
} else {
  $email = $_COOKIE['email']; // Replace 'email' with the appropriate cookie name that stores the customer's email

  // Retrieve the customer's ID based on the email
  $userIdQuery = "SELECT userid FROM user WHERE email = '$email'";
  $userIdResult = mysqli_query($db_link, $userIdQuery);

  if ($userIdResult && mysqli_num_rows($userIdResult) > 0) {
    $userIdRow = mysqli_fetch_assoc($userIdResult);
    $userId = $userIdRow['userid'];

    // Retrieve messages where the sender is 'Admin' and the recipient is the customer's ID or vice versa
    $query = "SELECT * FROM messages WHERE (sender = 'Admin' AND recipient = '$userId') OR (sender = '$userId' AND recipient = 'Admin') ORDER BY timestamp ASC";
  } else {
    // Handle the case when the customer's ID is not found
    echo "Error retrieving customer's ID.";
    mysqli_close($db_link);
    exit();
  }
}

$result = mysqli_query($db_link, $query);

if ($result) {
  $rows = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  mysqli_free_result($result);
} else {
  echo "Error retrieving chat messages: " . mysqli_error($db_link);
}

mysqli_close($db_link);

// Send the data as a JSON response
header('Content-Type: application/json');
echo json_encode($rows);
?>