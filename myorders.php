<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>

<head>
    <meta charset="UTF-8">
    <title>Menu - Yay&#33;Koffee Website Template</title>
    <link rel="stylesheet" type="text/css" href="css/myorders.css">
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
                    <span>My Orders</span>
                </div>
                <div>
                    <!-- PHP CODE HERE FOR CART -->
                    <?php
                    // Establish a database connection
                    $hostname = "localhost";
                    $database = "Shopee";
                    $db_login = "root";
                    $db_pass = "";
                    $dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

                    // Retrieve user's email from the cookie
                    $customerEmail = $_COOKIE['email'];

                    // Retrieve the user ID based on the email from the Users table
                    $userQuery = "SELECT userid FROM user WHERE email='$customerEmail'";
                    $userResult = mysqli_query($dlink, $userQuery);
                    $user = mysqli_fetch_assoc($userResult);
                    $userId = $user['userid'];

                    // Get the status parameter from the URL, defaulting to empty
                    $status = isset($_GET['status']) ? $_GET['status'] : '';

                    // Retrieve the counts for each status
                    $pendingCountQuery = "SELECT COUNT(*) AS pendingCount FROM Purchase WHERE status='Pending' AND userid='$userId'";
                    $pendingCountResult = mysqli_query($dlink, $pendingCountQuery);
                    $pendingCountRow = mysqli_fetch_assoc($pendingCountResult);
                    $pendingCount = $pendingCountRow['pendingCount'];

                    $acceptedCountQuery = "SELECT COUNT(*) AS acceptedCount FROM Purchase WHERE status='Accepted' AND userid='$userId'";
                    $acceptedCountResult = mysqli_query($dlink, $acceptedCountQuery);
                    $acceptedCountRow = mysqli_fetch_assoc($acceptedCountResult);
                    $acceptedCount = $acceptedCountRow['acceptedCount'];

                    $completedCountQuery = "SELECT COUNT(*) AS completedCount FROM Purchase WHERE status='Completed' AND userid='$userId'";
                    $completedCountResult = mysqli_query($dlink, $completedCountQuery);
                    $completedCountRow = mysqli_fetch_assoc($completedCountResult);
                    $completedCount = $completedCountRow['completedCount'];

                    $returnRefundCountQuery = "SELECT COUNT(*) AS returnRefundCount FROM Purchase WHERE status='Return/Refund' AND userid='$userId'";
                    $returnRefundCountResult = mysqli_query($dlink, $returnRefundCountQuery);
                    $returnRefundCountRow = mysqli_fetch_assoc($returnRefundCountResult);
                    $returnRefundCount = $returnRefundCountRow['returnRefundCount'];

                    // Retrieve user's orders from the Purchase table based on the status
                    $query = "SELECT * FROM Purchase WHERE userid='$userId'";
                    if (!empty($status)) {
                        $query .= " AND status='$status'";
                    }

                    $result = mysqli_query($dlink, $query);

                    // Display the table with the filtered orders
                    if ($result && mysqli_num_rows($result) > 0) {
                        echo '<table id="myorders-table">';
                        echo '<tr><th><a href="myorders.php?status=pending">Pending (' . $pendingCount . ')</a></th><th><a href="myorders.php?status=accepted">Accepted (' . $acceptedCount . ')</a></th><th><a href="myorders.php?status=completed">Completed (' . $completedCount . ')</a></th><th><a href="myorders.php?status=return/refund">Return/Refund (' . $returnRefundCount . ')</a></th></tr>';
                        echo '<tr><th>Product</th><th>Quantity</th><th>Description</th><th>Total</th><th>Date Ordered</th><th>Status</th></tr>';

                        // Initialize the total cost variable
                        $totalCost = 0;

                        while ($row = mysqli_fetch_assoc($result)) {
                            // Retrieve product details from the Products table based on the prodid
                            $productId = $row['prodid'];
                            $productQuery = "SELECT * FROM Products WHERE prodid='$productId'";
                            $productResult = mysqli_query($dlink, $productQuery);
                            $product = mysqli_fetch_assoc($productResult);

                            // Calculate the cost for the current order
                            $orderCost = $product['curprice'] * $row['quantity'];

                            // Add the order cost to the total cost
                            $totalCost += $orderCost;

                            echo '<tr>';
                            echo '<td><img src="' . $product['productimage'] . '" alt="' . $product['productname'] . '"></td>';
                            echo '<td>' . $row['quantity'] . '</td>';
                            echo '<td>' . $product['productname'] . '<br>' . $product['productdesc'] . '</td>';
                            echo '<td>$' . number_format($product['curprice'] * $row['quantity'], 2) . '</td>';
                            echo '<td>' . $row['date'] . '</td>';
                            echo '<td>' . $row['status'] . '</td>';
                            echo '</tr>';

                            mysqli_free_result($productResult);
                        }
                        echo '<tr><td></td><td colspan="2">Total Cost:</td><td id="total_cost">$' . number_format($totalCost, 2) . '</td>';
                        echo '</table>';
                    } else {

                        echo '<table id="myorders-table">';
                        echo '<tr><th><a href="myorders.php?status=pending">Pending (' . $pendingCount . ')</a></th><th><a href="myorders.php?status=accepted">Accepted (' . $acceptedCount . ')</a></th><th><a href="myorders.php?status=completed">Completed (' . $completedCount . ')</a></th><th><a href="myorders.php?status=/refund">Return/Refund (' . $returnRefundCount . ')</a></th></tr>';
                        echo '<tr><th>Product</th><th>Quantity</th><th>Description</th><th>Total</th><th>Date Ordered</th><th>Status</th></tr>';
                        echo '<tr>';
                        echo '<td><img src="' . $product['productimage'] . '" alt="' . $product['productname'] . '"></td>';
                        echo '<td>' . $row['quantity'] . '</td>';
                        echo '<td>' . $product['productname'] . '<br>' . $product['productdesc'] . '</td>';
                        echo '<td>$' . number_format($product['curprice'] * $row['quantity'], 2) . '</td>';
                        echo '<td>' . $row['date'] . '</td>';
                        echo '<td>' . $row['status'] . '</td>';
                        echo '</tr>';
                        echo '</table>';

                    }

                    // Close the result sets and the database connection
                    mysqli_free_result($result);
                    mysqli_free_result($userResult);
                    mysqli_close($dlink);
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