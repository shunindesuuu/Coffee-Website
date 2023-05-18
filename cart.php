<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>

<head>
    <meta charset="UTF-8">
    <title>Menu - Yay&#33;Koffee Website Template</title>
    <link rel="stylesheet" type="text/css" href="css/cart.css">
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
                            echo '<li><a href="calendar.php">Calendar</a></li>';
                        } elseif ($_COOKIE['type'] == 'customer') {
                            echo '<li><a href="menu.php">Menu</a></li>';
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
                    <span>Cart</span>
                </div>
                <div>
                    <!-- PHP CODE HERE FOR CART -->
                    <?php
                    // Initialize cart array if not yet created
                    if (!isset($_COOKIE['cart'])) {
                        setcookie('cart', serialize(array()), time() + (86400 * 30), "/"); // 30 days
                    }

                    // Check if product ID is passed through GET
                    if (isset($_GET['prodid'])) {
                        // Add product to cart array
                        $prodid = $_GET['prodid'];
                        $cart = unserialize($_COOKIE['cart']);

                        if (isset($cart[$prodid])) {
                            $cart[$prodid]['quantity']++;
                        } else {
                            // Get product details from database
                            $hostname = "localhost";
                            $database = "Shopee";
                            $db_login = "root";
                            $db_pass = "";
                            $dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

                            $query = "SELECT * FROM Products WHERE prodid='$prodid'";
                            $result = mysqli_query($dlink, $query);
                            $product = mysqli_fetch_assoc($result);

                            // Add product to cart array
                            $cart[$prodid] = array(
                                'image' => $product['productimage'],
                                'name' => $product['productname'],
                                'description' => $product['productdesc'],
                                'price' => $product['curprice'],
                                'quantity' => 1
                            );
                        }

                        setcookie('cart', serialize($cart), time() + (86400 * 30), "/"); // 30 days
                        header("Location: {$_SERVER['PHP_SELF']}");
                        exit;
                    }

                    // Check if product ID is passed through POST (delete action)
                    if (isset($_POST['delete'])) {
                        // Remove selected products from cart array
                        $delete_items = $_POST['delete'];
                        $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

                        foreach ($delete_items as $prodid) {
                            if (isset($cart[$prodid])) {
                                unset($cart[$prodid]);
                            }
                        }

                        setcookie('cart', serialize($cart), time() + (86400 * 30), "/"); // 30 days
                        header("Location: {$_SERVER['PHP_SELF']}");
                        exit;
                    }

                    // Place order functionality
                    if (isset($_POST['place_order'])) {
                        $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array(); // Check if the cart cookie is set
                        $customerEmail = $_COOKIE['email']; // Assuming you have stored the customer's email in a cookie
                    
                        foreach ($cart as $prodid => $product) {
                            if (isset($_POST['purchase']) && in_array($prodid, $_POST['purchase'])) {
                                $quantity = $_POST['quantity'][$prodid];

                                if (empty($quantity)) {
                                    echo '<script>alert("This item is out of stock.");</script>';
                                    echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '";</script>';
                                    exit;
                                }

                                // Update the quantity in the database
                                $hostname = "localhost";
                                $database = "Shopee";
                                $db_login = "root";
                                $db_pass = "";
                                $dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");
                                $query = "UPDATE Products SET quantity = quantity - $quantity WHERE prodid='$prodid'";
                                mysqli_query($dlink, $query);

                                // Remove purchased product from the cart
                                unset($cart[$prodid]);

                                // Insert the purchase record into the Purchase table
                                $date = date('Y-m-d H:i:s');
                                $status = 'Pending'; // Set the initial status as Pending
                    
                                // Retrieve the user ID from the Users table
                                $userQuery = "SELECT userid FROM user WHERE email='$customerEmail'";
                                $userResult = mysqli_query($dlink, $userQuery);
                                $user = mysqli_fetch_assoc($userResult);
                                $userId = $user['userid'];

                                $insertQuery = "INSERT INTO Purchase (userid, prodid, quantity, date, status) VALUES ('$userId', '$prodid', '$quantity', '$date', '$status')";
                                mysqli_query($dlink, $insertQuery);

                            }
                        }

                        // Save updated cart after placing the order
                        setcookie('cart', serialize($cart), time() + (86400 * 30), "/"); // 30 days
                    
                        // Display alert message using JavaScript
                        echo '<script>alert("Thank you for purchasing ' . $customerEmail . '!");</script>';
                        echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '";</script>';
                        exit;
                    }
                    function updatePrice($prodid, $price)
                    {
                        $quantity = $_POST['quantity'][$prodid];
                        $subtotal = $price * $quantity;
                        // Update the quantity and subtotal in the cart array
                        $cart = unserialize($_COOKIE['cart']);
                        $cart[$prodid]['quantity'] = $quantity;
                        $cart[$prodid]['subtotal'] = $subtotal;

                        // Save the updated cart array to the cookie
                        setcookie('cart', serialize($cart), time() + (86400 * 30), '/'); // Adjust the expiration time as needed
                    }
                    // Display cart table
                    $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array(); // Check if the cart cookie is set
                    if (empty($cart)) {
                        echo '<p>Your cart is empty. Start shopping <a href="menu.php">here</a>.</p>';
                    } else {
                        echo '<form method="post">';
                        echo '<table id="cart-table">';
                        echo '<tr><th></th><th id="product-header">Product</th><th></th><th id="description-header">Description</th><th id="name-header">Name</th><th id="quantity-header">Quantity</th><th id="price-header">Price</th><th id="action-header">Action</th></tr>';
                        $total_price = 0;

                        if (!empty($cart)) { // Check if the cart is not empty
                            // Establish a database connection
                            $hostname = "localhost";
                            $database = "Shopee";
                            $db_login = "root";
                            $db_pass = "";
                            $dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

                            foreach ($cart as $prodid => $product) {
                                // Get the available quantity of the product from the database
                                $query = "SELECT quantity FROM Products WHERE prodid='$prodid'";
                                $result = mysqli_query($dlink, $query);

                                if ($result) {
                                    $row = mysqli_fetch_assoc($result);
                                    $availableQuantity = $row['quantity'];

                                    // Update price based on selected quantity
                                    if (isset($_POST['quantity'][$prodid])) {
                                        $quantity = $_POST['quantity'][$prodid];
                                    } else {
                                        $quantity = $product['quantity'];
                                    }

                                    $subtotal = $product['price'] * $quantity;
                                    $total_price += $subtotal;
                                    echo '<tr id="cart-row-' . $prodid . '">';
                                    echo '<td><input type="checkbox" name="purchase[]" value="' . $prodid . '" id="checkbox-' . $prodid . '"></td>';
                                    echo '<td><img src="' . $product['image'] . '" alt="' . $product['name'] . '" id="image-' . $prodid . '"></td>';
                                    echo '<td></td>';
                                    echo '<td>' . $product['description'] . '</td>';
                                    echo '<td>' . $product['name'] . '</td>';
                                    echo '<td>';
                                    // For seeing all of the total quantity in the database
                                    echo '<select name="quantity[' . $prodid . ']" onchange="this.form.submit()" id="quantity-' . $prodid . '">';

                                    for ($i = 1; $i <= $availableQuantity; $i++) {
                                        echo '<option value="' . $i . '"';
                                        if ($i == $quantity) {
                                            echo ' selected';
                                        }
                                        echo '>' . $i . '</option>';
                                    }

                                    echo '</select>';
                                    echo '</td>';
                                    echo '<td>$' . number_format($subtotal, 2) . '</td>';
                                    echo '<td><button type="submit" name="delete[]" value="' . $prodid . '" id="delete-' . $prodid . '">Delete</button></td>';
                                    echo '</tr>';
                                }
                            }

                            // Close the result set
                            mysqli_free_result($result);

                            // Close the database connection
                            mysqli_close($dlink);
                        }

                        echo '<tr><td></td><td></td><td colspan="4">Total Price:</td><td id="total_price">$' . number_format($total_price, 2) . '</td>';
                        echo '</table>';
                        echo '<button type="submit" name="place_order" id="place_order">Place Order</button>';
                        echo '</form>';
                    }

                    ?>
                    <!-- END OF PHP -->
                </div>
            </div>
            <div id="footer">
                <div>
                    <a href="index.html"><img src="images/logo2.png" alt="Image"></a>
                    <p class="footnote">
                        &copy; Yay&#33;Koffee 2011.<br>All Rights Reserved.
                    </p>
                </div>
                <div class="section">
                    <ul>
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li class="current">
                            <a href="menu.html">Menu</a>
                        </li>
                        <li>
                            <a href="locations.html">Locations</a>
                        </li>
                        <li>
                            <a href="blog.html">Blog</a>
                        </li>
                        <li>
                            <a href="about.html">About Us</a>
                        </li>
                    </ul>
                    <div id="connect">
                        <a href="http://freewebsitetemplates.com/go/facebook/" target="_blank"
                            id="facebook">Facebook</a>
                        <a href="http://freewebsitetemplates.com/go/twitter/" target="_blank" id="twitter">Twitter</a>
                        <a href="http://freewebsitetemplates.com/go/googleplus/" target="_blank"
                            id="googleplus">Google+</a>
                        <a href="index.html" id="rss">RSS</a>
                    </div>
                    <p>
                        This website template has been designed by <a href="http://www.freewebsitetemplates.com/">Free
                            Website Templates</a> for you, for free. You can replace all this text with your own text.
                        You can remove any link to our website from this website template, you&#39;re free to use this
                        website template without linking back to us. If you&#39;re having problems editing this website
                        template, then don&#39;t hesitate to ask for help on the <a
                            href="http://www.freewebsitetemplates.com/forums/">Forums</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>