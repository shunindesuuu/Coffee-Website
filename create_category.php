<?php
$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";
$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

// Create a new category
$newCategory = "New Category";
$query = "INSERT INTO Products (prodcat, productname, productdesc, productimage, productlink, quantity, lastprice, curprice) VALUES ('$newCategory', 'New Product', 'New Product Description', 'images/newproduct.jpg', 'menu.php', 0, 0, 0)";

// Execute the query
$result = mysqli_query($dlink, $query);

if ($result) {
    // Get the ID of the newly inserted product
    $newProductID = mysqli_insert_id($dlink);

    // Display the newly created category and placeholder product
    echo '<div class="category" id="category-' . $newCategory . '">';
    echo '<a class="category-link" href="?category=' . $newCategory . '">' . $newCategory . '</a>';
    echo '</div>';

    echo '<li>';
    echo '<a href="#"><img id="product-image" src="images/newproduct.jpg" alt="New Product"></a>';
    echo '<div id="product-details">';
    echo '<p class="product-quantity out-of-stock" style="display: inline;">Out of Stock</p>';
    echo '<a id="product-name" href="cart.php?prodid=' . $newProductID . '">New Product</a>';
    echo '<p class="product-price">$0</p>';
    echo '</div>';
    echo '</li>';
} else {
    // Failed to create category
    echo "Error creating category: " . mysqli_error($dlink);
}

mysqli_close($dlink);
?>
