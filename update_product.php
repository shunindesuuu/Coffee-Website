<?php
$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";
$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

// Check if the connection was successful
if (!$dlink) {
    die('Failed to connect to the database: ' . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $prodid = $_POST['prodid'];
    $productname = $_POST['productname'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $curprice = $_POST['curprice'];
    $prodcat = $_POST['prodcat'];

    // Construct the update query based on the submitted form values
    $updateQuery = "UPDATE Products SET ";

    $updateFields = array();

    if (!empty($productname)) {
        $updateFields[] = "productname='$productname'";
    }
    if (!empty($description)) {
        $updateFields[] = "productdesc='$description'";
    }
    if (!empty($quantity)) {
        $updateFields[] = "quantity='$quantity'";
    }
    if (!empty($curprice)) {
        $updateFields[] = "curprice='$curprice'";
    }
    if (!empty($prodcat)) {
        $updateFields[] = "prodcat='$prodcat'";
    }

    // Check if an image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = array('image/gif', 'image/jpeg', 'image/png');
        $maxFileSize = 2000000; // 2MB

        if (in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] < $maxFileSize) {
            $imageDirectory = "images/";
            $imageFileName = $_FILES['image']['name'];
            $imagePath = $imageDirectory . $imageFileName;

            // Move the uploaded image to the desired directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                // Update the productimage column in the Products table
                $updateFields[] = "productimage='$imagePath'";
            } else {
                echo '<script>alert("Failed to move the uploaded image. Please try again.");</script>';
            }
        } else {
            echo '<script>alert("Invalid file. Please upload a GIF, JPEG, or PNG image file (max 2MB).");</script>';
        }
    }

    // Check if any fields were updated
    if (count($updateFields) > 0) {
        $updateQuery .= implode(", ", $updateFields);
        $updateQuery .= " WHERE prodid='$prodid'";

        // Execute the update query
        $result = mysqli_query($dlink, $updateQuery);

        // Check if the update was successful
        if ($result) {
            echo '<script>alert("Product updated successfully.");</script>';
        } else {
            echo '<script>alert("Failed to update product. Please try again.");</script>';
        }
    } else {
        echo '<script>alert("No fields were updated.");</script>';
    }
    // Retrieve the updated quantity value from the form submission
    $quantity = $_POST['quantity'];

    // Check if the quantity value is set to 0
    if ($quantity == 0) {
        // Perform the database update query
        $updateQuery = "UPDATE Products SET quantity = '$quantity' WHERE prodid = '$prodid'";

        // Execute the update query
        $result = mysqli_query($dlink, $updateQuery);

        // Check if the update was successful
        if ($result) {
            echo '<script>alert("Product quantity updated successfully.");</script>';
        } else {
            echo '<script>alert("Failed to update product quantity. Please try again.");</script>';
        }
    } else {
        echo '<script>alert("The quantity value is not 0. No update is performed.");</script>';
    }
}

mysqli_close($dlink);
header("Location: adminproducts.php");
exit();
?>