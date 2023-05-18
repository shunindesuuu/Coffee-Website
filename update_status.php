<?php
$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";
$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

if (isset($_POST['userid']) && isset($_POST['prodid']) && isset($_POST['quantity']) && isset($_POST['date']) && isset($_POST['new_status'])) {
    $userid = $_POST['userid'];
    $prodid = $_POST['prodid'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];
    $newStatus = $_POST['new_status'];

    // Update the status of the purchase
    $updateQuery = "UPDATE Purchase SET status='$newStatus' WHERE userid='$userid' AND prodid='$prodid' AND quantity='$quantity' AND date='$date'";
    $updateResult = mysqli_query($dlink, $updateQuery);

    if ($updateResult) {
        // Get the recipient (customer's user ID)
        $recipientQuery = "SELECT userid FROM user WHERE userid = '$userid'";
        $recipientResult = mysqli_query($dlink, $recipientQuery);

        if ($recipientResult && mysqli_num_rows($recipientResult) > 0) {
            $row = mysqli_fetch_assoc($recipientResult);
            $recipient = $row['userid'];

            // Insert a message into the messages table
            $message = "Admin has changed the order status into $newStatus";
            $insertQuery = "INSERT INTO messages (sender, recipient, message, timestamp) VALUES ('Admin', '$recipient', '$message', NOW())";
            $insertResult = mysqli_query($dlink, $insertQuery);

            if ($insertResult) {
                mysqli_close($dlink);
                header("Location: customerorders.php");
                exit();
            } else {
                echo "Error inserting message: " . mysqli_error($dlink);
            }
        } else {
            echo "Error retrieving recipient: " . mysqli_error($dlink);
        }
    } else {
        echo "Error updating status: " . mysqli_error($dlink);
    }
} else {
    echo "Invalid parameters. Please provide userid, prodid, quantity, date, and new_status.";
}

mysqli_close($dlink);
?>