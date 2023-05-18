<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>

<head>
    <meta charset="UTF-8">
    <title>Menu - Yay&#33;Koffee Website Template</title>
    <link rel="stylesheet" type="text/css" href="css/customerorders.css">
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
                            echo '<li><a href="#" onclick="openFormPopup()">Open Chat</a></li>';
                        } elseif ($_COOKIE['type'] == 'customer') {
                            echo '<li><a href="menu.php">Menu</a></li>';
                            echo '<li><a href="cart.php">Cart</a></li>';
                            echo '<li><a href="myorders.php">My Orders</a></li>';
                            echo '<li><a href="#" onclick="openFormPopup()">Open Chat</a></li>';
                        }
                    }
                    ?>

                </ul>
            </div>
            <div id="body">
                <div id="figure">
                    <img src="images/headline-menu.jpg" alt="Image">
                    <span>Cust Orders</span>
                </div>
                <div>
                    <!-- PHP HERE FOR CUSTOMER ORDERS -->
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

                    // Retrieve the user ID based on the email from the user table
                    $userQuery = "SELECT userid, usertype FROM user WHERE email='$customerEmail'";
                    $userResult = mysqli_query($dlink, $userQuery);
                    $user = mysqli_fetch_assoc($userResult);
                    $userId = $user['userid'];
                    $userType = $user['usertype'];

                    // Check if the user is an admin
                    if (isset($_COOKIE['type']) && $_COOKIE['type'] == 'admin') {

                        // Start or resume the session
                        session_start();

                        // Get the status parameter from the URL, defaulting to empty
                        $status = isset($_GET['status']) ? $_GET['status'] : '';

                        // Get the selected date from the calendar input
                        if (isset($_GET['date'])) {
                            $_SESSION['selectedDate'] = $_GET['date'];
                        } elseif (!isset($_SESSION['selectedDate'])) {
                            $_SESSION['selectedDate'] = null;
                        }

                        // Retrieve the counts for each status and date combination
                        $pendingCountQuery = "SELECT COUNT(*) AS pendingCount FROM Purchase WHERE status='Pending' " . ($_SESSION['selectedDate'] ? "AND DATE(date)='{$_SESSION['selectedDate']}'" : "");
                        $acceptedCountQuery = "SELECT COUNT(*) AS acceptedCount FROM Purchase WHERE status='Accepted' " . ($_SESSION['selectedDate'] ? "AND DATE(date)='{$_SESSION['selectedDate']}'" : "");
                        $completedCountQuery = "SELECT COUNT(*) AS completedCount FROM Purchase WHERE status='Completed' " . ($_SESSION['selectedDate'] ? "AND DATE(date)='{$_SESSION['selectedDate']}'" : "");
                        $returnRefundCountQuery = "SELECT COUNT(*) AS returnRefundCount FROM Purchase WHERE status='Return/Refund' " . ($_SESSION['selectedDate'] ? "AND DATE(date)='{$_SESSION['selectedDate']}'" : "");

                        // Execute count queries
                        $pendingCountResult = mysqli_query($dlink, $pendingCountQuery);
                        $pendingCountRow = mysqli_fetch_assoc($pendingCountResult);
                        $pendingCount = $pendingCountRow['pendingCount'];

                        $acceptedCountResult = mysqli_query($dlink, $acceptedCountQuery);
                        $acceptedCountRow = mysqli_fetch_assoc($acceptedCountResult);
                        $acceptedCount = $acceptedCountRow['acceptedCount'];

                        $completedCountResult = mysqli_query($dlink, $completedCountQuery);
                        $completedCountRow = mysqli_fetch_assoc($completedCountResult);
                        $completedCount = $completedCountRow['completedCount'];

                        $returnRefundCountResult = mysqli_query($dlink, $returnRefundCountQuery);
                        $returnRefundCountRow = mysqli_fetch_assoc($returnRefundCountResult);
                        $returnRefundCount = $returnRefundCountRow['returnRefundCount'];

                        // Retrieve orders from the Purchase table based on the status and date
                        $query = "SELECT * FROM Purchase";

                        // Add the date filter if a date parameter is provided
                        if ($_SESSION['selectedDate']) {
                            $query .= " WHERE DATE(date) = '{$_SESSION['selectedDate']}'";
                        }

                        // Add the status filter if a status parameter is provided
                        if (!empty($status)) {
                            if (strpos($query, 'WHERE') !== false) {
                                $query .= " AND status='$status'";
                            } else {
                                $query .= " WHERE status='$status'";
                            }
                        }

                        $result = mysqli_query($dlink, $query);


                        // Display the table with the orders and ability to change the status
                        if ($result && mysqli_num_rows($result) > 0) {
                            echo '<table id="customerorders-table">';
                            echo '<tr>';
                            echo '<th><a href="customerorders.php?status=pending' . ($_SESSION['selectedDate'] ? '&date=' . $_SESSION['selectedDate'] : '') . '">Pending (' . $pendingCount . ')</a></th>';
                            echo '<th><a href="customerorders.php?status=accepted' . ($_SESSION['selectedDate'] ? '&date=' . $_SESSION['selectedDate'] : '') . '">Accepted (' . $acceptedCount . ')</a></th>';
                            echo '<th><a href="customerorders.php?status=completed' . ($_SESSION['selectedDate'] ? '&date=' . $_SESSION['selectedDate'] : '') . '">Completed (' . $completedCount . ')</a></th>';
                            echo '<th><a href="customerorders.php?status=return/refund' . ($_SESSION['selectedDate'] ? '&date=' . $_SESSION['selectedDate'] : '') . '">Return/Refund (' . $returnRefundCount . ')</a></th>';
                            echo '<tr><th>Product</th><th>Quantity</th><th>Description</th><th>Total</th><th>Date Ordered</th><th>Status</th></tr>';
                            echo '</tr>';

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
                                echo '<td>';
                                echo '<form method="POST" action="update_status.php">';
                                echo '<input type="hidden" name="userid" value="' . $row['userid'] . '">';
                                echo '<input type="hidden" name="prodid" value="' . $row['prodid'] . '">';
                                echo '<input type="hidden" name="quantity" value="' . $row['quantity'] . '">';
                                echo '<input type="hidden" name="date" value="' . $row['date'] . '">';
                                echo '<select name="new_status" onchange="this.form.submit()">';
                                echo '<option value="Pending"' . ($row['status'] == 'Pending' ? ' selected' : '') . '>Pending</option>';
                                echo '<option value="Accepted"' . ($row['status'] == 'Accepted' ? ' selected' : '') . '>Accepted</option>';
                                echo '<option value="Completed"' . ($row['status'] == 'Completed' ? ' selected' : '') . '>Completed</option>';
                                echo '<option value="Return/Refund"' . ($row['status'] == 'Return/Refund' ? ' selected' : '') . '>Return/Refund</option>';
                                echo '</select>';
                                echo '</form>';
                                echo '</td>';
                                echo '</tr>';

                                mysqli_free_result($productResult);
                            }

                            echo '<tr><td></td><td colspan="2">Total Cost:</td><td id="total_cost">$' . number_format($totalCost, 2) . '</td>';
                            echo '</table>';
                        } else {
                            echo '<table id="customerorders-table">';
                            echo '<tr><th><a href="customerorders.php?status=pending">Pending (' . $pendingCount . ')</a></th><th><a href="customerorders.php?status=accepted">Accepted (' . $acceptedCount . ')</a></th><th><a href="customerorders.php?status=completed">Completed (' . $completedCount . ')</a></th><th><a href="customerorders.php?status=return/refund">Return/Refund (' . $returnRefundCount . ')</a></th></tr>';
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

                        // Close the result set and the database connection
                        mysqli_free_result($result);
                    } elseif ($_COOKIE['type'] == 'customer') {
                        // If the user is not an admin, display an error message or redirect them to the appropriate page
                        echo 'You do not have permission to access this page.';
                    }

                    // Close the result set and the database connection for the user query
                    mysqli_free_result($userResult);
                    mysqli_close($dlink);
                    ?>
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
                        <!-- <li>
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
                        </li> -->
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