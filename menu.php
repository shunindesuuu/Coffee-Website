<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>

<head>
	<meta charset="UTF-8">
	<title>Menu - Yay&#33;Koffee Website Template</title>
	<link rel="stylesheet" type="text/css" href="css/menu.css">

</head>
<style>
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
</style>

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
					<span>Menu</span>
				</div>
				<div>
					<a href="menu.php" class="whatshot"></a>
					<div>
						<ul>
							<?php
							// Check if the user is logged in and has the usertype of "admin"
							if (!isset($_COOKIE['type']) || $_COOKIE['type'] !== 'customer') {
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

							while ($row = mysqli_fetch_assoc($result)) {
								if ($current_cat != $row['prodcat']) {
									echo '<div class="category" id="category-' . $row['prodcat'] . '">';
									echo '<a class="category-link" href="?category=' . $row['prodcat'] . '">' . $row['prodcat'] . '</a>';
									echo '</div>';
									$current_cat = $row['prodcat'];
								}

								echo '<li>';
								echo '<a><img id="product-image" src="' . $row['productimage'] . '" alt="' . $row['productname'] . '"></a>';
								echo '<div id="product-details">';

								// Display product name, price, and quantity
								if ($row['quantity'] > 0) {
									echo '<p class="product-quantity" style="display: inline;">Quantity: ' . $row['quantity'] . '</p>';
									echo '<a id="product-name" href="cart.php?prodid=' . $row['prodid'] . '">' . $row['productname'] . '</a>';
									echo '<p class="product-price">$' . $row['curprice'] . '</p>'; // Display product quantity
								} else {
									echo '<p class="product-quantity out-of-stock" style="display: inline; color:red; font-weight:bold;">Out of Stock</p>';
								}

								echo '</div>';
								echo '</li>';
							}

							echo '</ul>';

							mysqli_close($dlink);
							?>
						</ul>
					</div>
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