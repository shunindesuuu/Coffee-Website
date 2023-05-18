<?php
$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";
$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

// Retrieve the prodid, category, and option from the AJAX request
$prodid = $_POST['prodid'];
$category = $_POST['category'];
$option = $_POST['option'];

if ($option === 'insert') {
    // Retrieve the details of the placeholder product based on the selected category
    $query = "SELECT * FROM Products WHERE prodid = '$prodid'";
    $result = mysqli_query($dlink, $query);
    $selectedProduct = mysqli_fetch_assoc($result);

    if ($selectedProduct) {
        // Retrieve the category of the selected product
        $selectedCategory = $selectedProduct['prodcat'];

        // Insert the new product into the database with the same category
        $query = "INSERT INTO Products (prodcat, productname, productdesc, productimage, productlink, quantity, lastprice, curprice) VALUES ('$selectedCategory', 'New Product', 'New Product Description', 'images/newproduct.jpg', 'menu.php', 0, 0, 0)";
        $insertResult = mysqli_query($dlink, $query);

        if ($insertResult) {
            // Get the ID of the newly inserted product
            $newProductID = mysqli_insert_id($dlink);

            // Retrieve the details of the newly inserted product
            $query = "SELECT * FROM Products WHERE prodid = '$newProductID'";
            $newProductResult = mysqli_query($dlink, $query);
            $newProductRow = mysqli_fetch_assoc($newProductResult);

            // Display the newly created product
            echo '<li>';
            echo '<a href="#"><img id="product-image" src="' . $newProductRow['productimage'] . '" alt="' . $newProductRow['productname'] . '"></a>';
            echo '<div id="product-details">';
            echo '<p class="product-quantity out-of-stock" style="display: inline;">Out of Stock</p>';
            echo '<a id="product-name" href="cart.php?prodid=' . $newProductRow['prodid'] . '">' . $newProductRow['productname'] . '</a>';
            echo '<p class="product-price">$' . $newProductRow['curprice'] . '</p>';
            echo '</div>';
            echo '</li>';
        } else {
            // Failed to insert product
            echo "Error inserting product: " . mysqli_error($dlink);
        }
    } else {
        // Selected product not found
        echo "Selected product not found.";
    }
} else {
    // Invalid option
    echo "Invalid option.";
}

mysqli_close($dlink);
?>