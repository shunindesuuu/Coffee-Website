<?php
$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";
$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

// Check if the prodid parameter is present
if (isset($_POST['prodid'])) {
    $productId = $_POST['prodid'];

    // Delete the product from the database
    $query = "DELETE FROM Products WHERE prodid='$productId'";
    $result = mysqli_query($dlink, $query);

    if ($result) {
        // Product deleted successfully
        echo "Product deleted successfully";
    } else {
        // Failed to delete product
        echo "Error deleting product: " . mysqli_error($dlink);
    }
} else {
    // No prodid parameter provided
    echo "Invalid request";
}

mysqli_close($dlink);
?>
