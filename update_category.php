<?php
$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";
$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

// Retrieve the category ID and new category name from the request
$categoryId = $_POST['categoryId'];
$newCategoryName = $_POST['newCategoryName'];

// Check if the category name is empty
if (empty($newCategoryName)) {
    // Delete the category and its corresponding products
    $deleteQuery = "DELETE FROM Products WHERE prodcat = '$categoryId'";
    $deleteResult = mysqli_query($dlink, $deleteQuery);

    if ($deleteResult) {
        // Delete successful
        echo "Category and its products deleted successfully";
    } else {
        // Error deleting category and products
        echo "Error deleting category and its products: " . mysqli_error($dlink);
    }
} else {
    // Update the category name in the database
    $updateQuery = "UPDATE Products SET prodcat = '$newCategoryName' WHERE prodcat = '$categoryId'";
    $updateResult = mysqli_query($dlink, $updateQuery);

    if ($updateResult) {
        // Update successful
        echo "Category name updated successfully";
    } else {
        // Error updating category name
        echo "Error updating category name: " . mysqli_error($dlink);
    }
}

mysqli_close($dlink);
?>