<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>

<head>
    <meta charset="UTF-8">
    <title>Yay&#39;Koffee Website Template</title>
    <link rel="stylesheet" type="text/css" href="css/calendar.css">
</head>

<body>
    <div id="page">
        <div>
            <div id="header">
                <a href="index.php"><img src="images/logo.png" alt="Image"></a>
                <ul>
                    <li class="current">
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
                    <li>
                        <?php
                        if (isset($_COOKIE['type'])) {
                            if ($_COOKIE['type'] == 'admin') {
                                echo '<li><a href="adminproducts.php">Products</a></li>';
                                echo '<li><a href="customerorders.php">Cust Orders</a></li>';
                                echo '<li><a href="calendar.php">Calendar</a></li>';
                            } elseif ($_COOKIE['type'] == 'customer') {
                                echo '<li><a href="menu.php">Menu</a></li>';
                                echo '<li><a href="cart.php">Cart</a></li>';
                                echo '<li><a href="myorders.php">My Orders</a></li>';
                            }
                        }
                        ?>
                    </li>
                </ul>
            </div>
            <div id="calendar">
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

                // Create a connection to the database
                $dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

                // Get the current year and month
                $year = date('Y');
                $month = date('m');

                // Get the number of days in the current month
                $num_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                // Get the name of the current month
                $month_name = date('F', mktime(0, 0, 0, $month, 1, $year));

                // Retrieve the count of purchases for each day in the current month
                $query = "SELECT DAY(date) AS day, COUNT(*) AS orderCount FROM Purchase WHERE YEAR(date) = $year AND MONTH(date) = $month GROUP BY DAY(date)";
                $result = mysqli_query($dlink, $query);

                // Create an empty array to store the order counts
                $orderCounts = array();

                // Store the order counts in the array
                while ($row = mysqli_fetch_assoc($result)) {
                    $orderCounts[$row['day']] = $row['orderCount'];
                }

                echo "<table width='80%' border='1'>";
                echo "<caption>$month_name $year</caption>";
                echo "<tr>";
                echo "<th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>";
                echo "</tr>";
                echo "<tr>";

                // Get the index of the first day of the month (0 = Sunday, 1 = Monday, etc.)
                $first_day_index = (int) date('w', strtotime("$year-$month-01"));

                // Print blank cells for the days before the first day of the month
                for ($i = 0; $i < $first_day_index; $i++) {
                    echo "<td></td>";
                }

                // Print the cells for the days of the month
                for ($day = 1; $day <= $num_days; $day++) {
                    // Start a new row at the beginning of each week
                    if (($day + $first_day_index - 1) % 7 === 0) {
                        echo "</tr><tr>";
                    }

                    // Get the order count for the current day
                    $orderCount = isset($orderCounts[$day]) ? $orderCounts[$day] : 0;

                    // Highlight the current day
                    $class = ($day == date('d')) ? 'current-day' : '';

                    // Create the link to customerorders.php if there are orders on that day
                    $link = ($orderCount > 0) ? "<a href='customerorders.php?date=$year-$month-$day'>$day ($orderCount)</a>" : $day;

                    // Display the day with the link (if applicable)
                    echo "<td align='center' class='$class'>$link</td>";
                }

                // Print blank cells for the days after the last day of the month
                $last_day_index = ($first_day_index + $num_days - 1) % 7;
                for ($i = $last_day_index; $i < 6; $i++) {
                    echo "<td></td>";
                }

                echo "</tr>";
                echo "</table>";

                // Close the database connection
                mysqli_close($dlink);
                ?>
            </div>
            </ul>
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
    </div>
    </div>
</body>

</html>