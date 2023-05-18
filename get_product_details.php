<?php
// get_product_details.php

// Check if the prodid parameter is provided
if (!isset($_GET['prodid'])) {
    // Handle the error, such as returning an error response or redirecting
    exit("Product ID is missing.");
}

$prodid = $_GET['prodid'];

// Connect to the database
$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";
$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

// Fetch the product details from the database
$query = "SELECT * FROM Products WHERE prodid = '$prodid'";
$result = mysqli_query($dlink, $query);

if (!$result) {
    // Handle the error, such as returning an error response or redirecting
    exit("Error retrieving product details.");
}

// Check if a product with the given prodid exists
if (mysqli_num_rows($result) > 0) {
    // Fetch the product details as an associative array
    $productDetails = mysqli_fetch_assoc($result);

    // Rename the fields to match the form field names
    $productDetails['description'] = $productDetails['productdesc'];
    $productDetails['image'] = $productDetails['productimage'];

    // Return the product details as a JSON response
    header('Content-Type: application/json');
    echo json_encode($productDetails);
} else {
    // Handle the case when the product is not found, such as returning an error response or redirecting
    exit("Product not found.");
}

mysqli_close($dlink);
?>