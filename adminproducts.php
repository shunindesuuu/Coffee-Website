<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>
<style>
    /* Styling for the pop-up form */
    #popup-container {
        display: none;
        position: fixed;
        z-index: 9990;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent black background */
    }

    #popup-container.open {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: rgba(38, 38, 40, 0.92);
        color: #fff;
        max-width: 500px;
        margin: auto;
        padding: 20px;
        box-sizing: border-box;
        text-align: center;
        position: relative;
        top: 50%;
        transform: translateY(30%);
    }

    .modal-content input {
        height: 40px;
        border-radius: 4px;
    }

    .modal-content .btn-primary {
        width: 175px;
        background-color: #ef9919;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .modal-content .btn-primary:hover {
        background-color: #0073b7;
    }

    .logorow {
        text-align: center;
    }

    .close {
        margin-right: 10px;
        margin-top: 5px;
        color: #fff;
        opacity: 0.8;
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 24px;
        cursor: pointer;
    }

    .close:hover {
        color: #efefef;
    }

    /* Container for each item */
    .item-container {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }

    /* Product name */
    .item-container a {
        color: #333;
        text-decoration: none;
        font-weight: bold;
    }

    /* Product image */
    .item-container img {
        width: 200px;
        height: auto;
        margin-bottom: 5px;
    }

    /* Product quantity */
    .item-container p {
        display: inline;
        margin-right: 10px;
    }

    /* Out of stock message */
    .item-container p.out-of-stock {
        color: red;
    }

    /* Category */
    .category {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .category-link {
        text-decoration: none;
        color: #333;
    }

    .product-quantity out-of-stock {
        color: red;
    }

    #new-category-btn {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin-bottom: 10px;
        margin-top: 10px;
        cursor: pointer;
    }

    #new-category-btn:hover {
        background-color: #45a049;
    }

    #new-category-btn:focus {
        outline: none;
    }

    .edit-category-btn {
        background-color: #007bff;
        border: none;
        color: white;
        padding: 5px 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        margin-right: 10px;
        margin-left: 10px;
        cursor: pointer;
    }

    .edit-category-btn:hover {
        background-color: #0056b3;
    }

    .edit-category-btn:focus {
        outline: none;
    }

    /* CSS for the "Delete" button */
    .delete-category-btn {
        background-color: #dc3545;
        border: none;
        color: white;
        padding: 5px 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        cursor: pointer;
    }

    .delete-category-btn:hover {
        background-color: #c82333;
    }

    .delete-category-btn:focus {
        outline: none;
    }
</style>

<head>
    <meta charset="UTF-8">
    <title>Products - Yay&#33;Koffee Website Template</title>
    <link rel="stylesheet" type="text/css" href="css/adminproducts.css">
    <script src="message.js"></script>

</head>


<body>
    <div id="page">
        <div>
            <div id="header">
                <a href="index.php"><img src="images/logo.png" alt="Image"></a>
                <ul>
                    <li>
                        <a href="index.php?user=logged_in">Home</a>
                        <?php if (!isset($_COOKIE['email'])): ?>
                        <?php else: ?>
                        <li>
                            <a>Welcome,
                                <?php echo $_COOKIE['type'] . '  ' . $_COOKIE['email'] . '' ?>
                            </a>
                        </li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php endif ?>
                    </li>
                    <!-- <li>
                        <a href="cart.php">Cart</a>
                    </li> -->
                    <?php
                    if (isset($_COOKIE['type'])) {
                        if ($_COOKIE['type'] == 'admin') {
                            echo '<li><a href="adminproducts.php">Products</a></li>';
                            echo '<li><a href="customerorders.php">Cust Orders</a></li>';
                            echo '<li><a href="calendar.php">Calendar</a></li>';
                        } elseif ($_COOKIE['type'] == 'customer') {
                            echo '<li><a href="adminproducts.php">Products</a></li>';
                            echo '<li><a href="cart.php">Cart</a></li>';
                            echo '<li><a href="myorders.php">My Orders</a></li>';
                        }
                    }
                    ?>
                    <!-- <li>
                        <a href="locations.html">Locations</a>
                    </li>
                    <li>
                        <a href="blog.html">Blog</a>
                    </li>
                    <li>
                        <a href="about.html">About Us</a>
                    </li> -->
                </ul>
            </div>
            <div id="body">
                <div id="figure">
                    <img src="images/headline-menu.jpg" alt="Image">
                    <span>Menu</span>
                </div>
                <div>
                    <a href="adminproducts.php" class="whatshot"></a>
                    <div>
                        <ul>
                            <?php
                            // Check if the user is logged in and has the usertype of "admin"
                            if (!isset($_COOKIE['type']) || $_COOKIE['type'] !== 'admin') {
                                header("Location: index.php?action=login&#login_form");
                                exit();
                            }
                            $hostname = "localhost";
                            $database = "Shopee";
                            $db_login = "root";
                            $db_pass = "";
                            $dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

                            // Check if a category filter is set
                            if (isset($_GET['category'])) {
                                $category_filter = $_GET['category'];
                                $query = "SELECT * FROM Products WHERE prodcat='$category_filter' ORDER BY prodid";
                            } else {
                                $query = "SELECT * FROM Products ORDER BY prodcat, prodid";
                            }

                            $result = mysqli_query($dlink, $query);
                            $current_cat = '';

                            echo '<ul>';

                            // Button for the category
                            while ($row = mysqli_fetch_assoc($result)) {
                                if ($current_cat != $row['prodcat']) {
                                    echo '<div class="category" id="category-' . $row['prodcat'] . '">';
                                    echo '<a class="category-link" href="?category=' . $row['prodcat'] . '">' . $row['prodcat'] . '</a>';
                                    echo '<button class="edit-category-btn" onclick="editCategoryName(\'' . $row['prodcat'] . '\')">Edit</button>';
                                    echo '<button class="delete-category-btn" onclick="confirmDeleteCategory(\'' . $row['prodcat'] . '\')">Delete</button>';
                                    echo '</div>';
                                    $current_cat = $row['prodcat'];
                                }

                                echo '<li>';

                                echo '<a><img id="product-image" src="' . $row['productimage'] . '" alt="' . $row['productname'] . '"></a>';
                                echo '<select class="product-options" onchange="handleProductOptionChange(' . $row['prodid'] . ', this)">
                                <option value="" selected>--------</option> <!-- Make the empty value option selected -->
                                <option value="edit">Edit</option>
                                <option value="insert">Insert</option>
                                <option value="delete">Delete</option>
                                </select>';
                                echo '<div id="product-details">';

                                // Display product name, price, and quantity
                                if ($row['quantity'] > 0) {
                                    echo '<p class="product-quantity" style="display: inline;">Quantity: ' . $row['quantity'] . '</p>';
                                    echo '<a id="product-name">' . $row['productname'] . '</a>';
                                    echo '<p class="product-price">$' . $row['curprice'] . '</p>'; // Display product quantity
                                } else {
                                    echo '<p class="product-quantity out-of-stock" style="display: inline; color: red; font-weight: bold;">Out of Stock</p>';
                                }

                                echo '</div>';
                                echo '</li>';
                            }

                            // Add "New Category" button
                            echo '<div class="category">';
                            echo '<button id="new-category-btn" onclick="createNewCategory()">New Category</button>';
                            echo '</div>';

                            echo '</ul>';

                            mysqli_close($dlink);
                            ?>

                            <script>
                                // Function to open the form popup
                                function openFormPopup() {
                                    document.getElementById('popup-container').style.display = 'flex';
                                }

                                // Function to close the form popup
                                function closeFormPopup() {
                                    document.getElementById('popup-container').style.display = 'none';
                                }

                                // Function to handle the change event of the product option select element
                                function handleProductOptionChange(prodid, selectElement) {
                                    var value = selectElement.value;

                                    if (value === "insert") {
                                        handleInsertProduct(prodid, selectElement);
                                    } else if (value === "delete") {
                                        handleDeleteProduct(prodid);
                                    } else if (value === "edit") {
                                        handleProductEdit(prodid);
                                    }
                                }

                                // Function to handle the "insert" product option
                                function handleInsertProduct(prodid, selectElement) {
                                    // Retrieve the category of the selected product
                                    var prodcat = selectElement.getAttribute("data-category");

                                    // Make an AJAX request to insert a new product
                                    var xhr = new XMLHttpRequest();
                                    xhr.open("POST", "insert_product.php", true);
                                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                    xhr.onreadystatechange = function () {
                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                            // Insertion completed successfully
                                            // Reload the page
                                            location.reload();
                                        }
                                    };
                                    xhr.send("prodid=" + prodid + "&category=" + prodcat + "&option=insert");
                                }

                                // Function to handle the "delete" product option
                                function handleDeleteProduct(prodid) {
                                    var confirmationMessage = "Are you sure you want to delete this product (prodid = " + prodid + ")?";

                                    if (confirm(confirmationMessage)) {
                                        // Make an AJAX request to delete the product
                                        var xhr = new XMLHttpRequest();
                                        xhr.open("POST", "delete_product.php", true);
                                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                        xhr.onreadystatechange = function () {
                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                // Product deleted successfully, reload the page
                                                location.reload();
                                            }
                                        };
                                        xhr.send("prodid=" + prodid);
                                    } else {
                                        // Reset the select value to the default option
                                        selectElement.value = "";
                                    }
                                }

                                // Function to handle the "edit" product option
                                function handleProductEdit(prodid) {
                                    // Show the edit form popup
                                    document.getElementById('popup-container').style.display = 'block';

                                    // Set the prodid value in the edit form
                                    document.getElementById('prodid').value = prodid;

                                    // Update the heading with the prodid
                                    var heading = document.getElementById('popup-heading');
                                    heading.textContent = 'Edit Product ' + prodid;

                                    // Retrieve the product details using AJAX
                                    var xhr = new XMLHttpRequest();
                                    xhr.open("GET", "get_product_details.php?prodid=" + prodid, true);
                                    xhr.onreadystatechange = function () {
                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                            var productDetails = JSON.parse(xhr.responseText);

                                            // Set the values of the form fields
                                            document.getElementById('current-image').src = productDetails.productimage;
                                            document.getElementById('productname').value = productDetails.productname;
                                            document.getElementById('description').value = productDetails.description;
                                            document.getElementById('quantity').value = productDetails.quantity;
                                            document.getElementById('curprice').value = productDetails.curprice;
                                            document.getElementById('prodcat').value = productDetails.prodcat;
                                        }
                                    };
                                    xhr.send();
                                }

                                // Function to edit the category name
                                function editCategoryName(categoryId) {
                                    var categoryElement = document.getElementById('category-' + categoryId);
                                    var categoryLink = categoryElement.querySelector('.category-link');
                                    var editButton = categoryElement.querySelector('.edit-category-btn');

                                    if (categoryLink.style.display === 'none') {
                                        // Already in edit mode, save changes
                                        var inputElement = categoryElement.querySelector('input');
                                        var newCategoryName = inputElement.value;

                                        if (newCategoryName.trim() !== '') {
                                            // Send an AJAX request to update the category name
                                            var xhr = new XMLHttpRequest();
                                            xhr.open('POST', 'update_category.php', true);
                                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                            xhr.onload = function () {
                                                if (xhr.status === 200) {
                                                    // Reload the page after updating the category name
                                                    location.reload();
                                                } else {
                                                    // Display error message if updating category name failed
                                                    console.log('Error updating category name: ' + xhr.responseText);
                                                }
                                            };
                                            xhr.send('categoryId=' + encodeURIComponent(categoryId) + '&newCategoryName=' + encodeURIComponent(newCategoryName));
                                        }
                                    } else {
                                        // Enter edit mode
                                        categoryLink.style.display = 'none';
                                        editButton.innerText = 'Save';

                                        var inputElement = document.createElement('input');
                                        inputElement.type = 'text';
                                        inputElement.value = categoryLink.innerText;

                                        categoryElement.appendChild(inputElement);
                                    }
                                }

                                // Function to create a new category
                                function createNewCategory() {
                                    // Send an AJAX request to create a new category
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', 'create_category.php', true);
                                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                    xhr.onload = function () {
                                        if (xhr.status === 200) {
                                            // Reload the page after creating a new category
                                            location.reload();
                                        }
                                    };
                                    xhr.send();
                                }

                                // Function to confirm and delete a category
                                function confirmDeleteCategory(categoryId) {
                                    var confirmationMessage = "Are you sure you want to delete this category (" + categoryId + ") and its products?";

                                    var confirmation = confirm(confirmationMessage);
                                    if (confirmation) {
                                        // Send an AJAX request to delete the category
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST', 'update_category.php', true);
                                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                        xhr.onload = function () {
                                            if (xhr.status === 200) {
                                                // Reload the page after deleting the category
                                                location.reload();
                                            } else {
                                                // Display error message if deleting category failed
                                                console.log('Error deleting category: ' + xhr.responseText);
                                            }
                                        };
                                        xhr.send('categoryId=' + encodeURIComponent(categoryId) + '&newCategoryName=');
                                    }
                                }
                            </script>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div> -->
    <!-- Add the popup container -->
    <div id="popup-container" style="display: none;">
        <div id="popup-window">
            <div class="modal-content">
                <button type="button" class="close" onclick="closeFormPopup()">&times;</button>
                <div>
                    <div class="row text-center">
                        <h1 id="popup-heading">Edit Product</h1>
                        <hr>
                        <p>Update the product details below:</p>
                    </div>
                    <br>
                    <form action="update_product.php" method="post" id="edit-form" enctype="multipart/form-data"
                        onsubmit="handleProductUpdate(event)">
                        <input type="hidden" id="prodid" name="prodid">
                        <div class="row">

                            <div class="col-md-6">
                                <label for="prodcat">Product Category:</label>
                                <input class="form-control" name="prodcat" id="prodcat" placeholder="Product Category">
                            </div>
                            <div class="col-md-6">
                                <label for="productname">Product Name:</label>
                                <input class="form-control" name="productname" id="productname"
                                    placeholder="Product Name">
                            </div>
                            <div class="col-md-6">
                                <label for="description">Description:</label>
                                <input class="form-control" name="description" id="description"
                                    placeholder="Description">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="quantity">Quantity:</label>
                                <input class="form-control" name="quantity" id="quantity" placeholder="Quantity"
                                    type="number" min="0">
                            </div>
                            <div class="col-md-6">
                                <label for="curprice">Current Price:</label>
                                <input class="form-control" name="curprice" id="curprice" placeholder="Current Price">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <img id="current-image" src="' . $row['productimage'] . '" width="100">
                        </div>
                        <div class="col-md-6">
                            <label for="image">Product Image:</label>
                            <input type="file" name="image" id="image" accept="image/*">
                        </div>

                </div>
                <br>
                <center>
                    <input type="submit" class="btn btn-primary" name="submit" value="Save">
                </center>
                </form>
                <br>
            </div>
        </div>
    </div>
    </div>
</body>

</html>