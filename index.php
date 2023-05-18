<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>
<!-- Add the CSS styles -->
<style>
	.modal-container {
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

	.modal-container.open {
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.modal-content {
		background-color: #D5CEA3;
		color: #fff;
		max-width: 1000px;
		margin: auto;
		padding: 80px;
		box-sizing: border-box;
		text-align: center;
		position: relative;
		top: 10%;
	}

	.modal-content input {
		height: 40px;
		border-radius: 4px;
	}

	.modal-content button {
		padding: 8px 16px;
		background-color: #1A120B;
		border: none;
		color: white;
		border-radius: 4px;
		cursor: pointer;
	}

	.modal-content .close {
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

	/* Add the rest of your CSS styles for chatContainer, message, inputContainer, messageInput, and sendButton here */
	#chatContainer {
		height: 400px;
		overflow-y: scroll;
		padding: 10px;
	}

	.message {
		background-color: #8D7B68;
		padding: 10px;
		margin-bottom: 10px;
		border-radius: 8px;
	}

	#inputContainer {
		padding: 10px;
		background-color: #f2f2f2;
	}

	#messageInput {
		width: 70%;
		padding: 8px;
		border: none;
		border-radius: 4px;
		margin-right: 5px;
	}

	#sendButton {
		padding: 8px 16px;
		background-color: #1A120B;
		border: none;
		color: white;
		border-radius: 4px;
		cursor: pointer;
	}
</style>

<head>
	<meta charset="UTF-8">
	<title>Yay&#39;Koffee Website Template</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<!-- <script src="message.js"></script> -->
</head>

<body>
	<!-- Add the popup container -->
	<div id="popup-container" class="modal-container" style="display: none;">
		<div id="popup-window" class="modal-content">
			<button type="button" class="close" onclick="closeFormPopup()">&times;</button>
			<div id="chatContainer"></div>
			<div id="inputContainer">
				<input type="text" id="messageInput" placeholder="Type a message...">
				<button id="sendButton">Send</button>
			</div>
		</div>
	</div>
	<!-- Include the chat.js file -->

	<div id="page">
		<div>
			<div id="header">
				<a href="index.php"><img src="images/logo.png" alt="Image"></a>
				<ul>
					<li class="current">
						<a href="index.php?user=logged_in">Home</a>
						<!-- Welcome message -->
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
						<!-- For checking if Admin or customer -->
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
					</li>

					<!-- php section -->

					<div id="login_form">

						<?php
						$hostname = "localhost";
						$database = "Shopee";
						$db_login = "root";
						$db_pass = "";

						$dlink = mysql_connect($hostname, $db_login, $db_pass) or die("Could not connect");
						mysql_select_db($database) or die("Could not select database");

						// Register
						
						if ($_REQUEST['name'] != "" && $_REQUEST['email'] != "" && $_REQUEST['password'] != "" && $_REQUEST['contact'] != "" && $_REQUEST['address'] != "") {
							$query = "SELECT * FROM user WHERE email='" . $_REQUEST['email'] . "'";
							$result = mysql_query($query) or die(mysql_error());
							$num_results = mysql_num_rows($result);

							if ($num_results == 0) {
								// Check if this is the first registered user
								$query = "SELECT * FROM user";
								$result = mysql_query($query) or die(mysql_error());
								$num_results = mysql_num_rows($result);

								$user_type = 'customer';

								if ($num_results == 0) {
									// First registered user is admin
									$user_type = 'admin';
								}
								// If account is already registered
								$query = "INSERT INTO user(email, paswrd, contact, custname, address, usertype, user_date, user_ip) VALUES('" . $_REQUEST['email'] . "', '" . $_REQUEST['password'] . "', '" . $_REQUEST['contact'] . "', '" . $_REQUEST['name'] . "' ,'" . $_REQUEST['address'] . "', '" . $user_type . "', '" . date("Y-m-d h:i:s") . "', '" . $_SERVER['REMOTE_ADDR'] . "')";
								$result = mysql_query($query) or die(mysql_error());
								echo "<meta http-equiv='refresh' content='0;url=index.php?action=login&#login_form'>";
							} else {
								echo "<meta http-equiv='refresh' content='0;url=index.php?registered=user&register=true&#register'>";
								echo '<script>alert("Account Already Registered")</script>';
							}
						}

						// End of Register
						

						// Login
						if ($_REQUEST['logging_in'] == true) {
							$query = "SELECT * FROM user WHERE email='" . $_REQUEST['email'] . "' AND paswrd='" . $_REQUEST['password'] . "'";
							$result = mysql_query($query) or die(mysql_error());
							$total_results = mysql_num_rows($result);
							if ($total_results == 0) {
								echo '<meta http-equiv="refresh" content="0;url=index.php?action=register&#login_form">';
							} else {
								$row = mysql_fetch_array($result);
								setcookie("email", $row['email'], time() + 3600);
								setcookie("type", $row['usertype'], time() + 3600);
								echo '<meta http-equiv="refresh" content="0,url=index.php?user=logged_in">';
							}
						}


						// End of Login
						
						// Register Form
						
						if ($_REQUEST['action'] == 'register') {
							print('<h1>Registration Form</h1>');
							print('<form action=index.php method=post>');
							print('Enter Name<input type=text name=name><br>');
							print('Enter Email<input type=text name=email><br>');
							print('Enter Password<input type=text name=password><br>');
							print('Enter Contact<input type=text name=contact><br>');
							print('Enter Address<input type=text name=address><br>');
							print('<input type=submit value=submit>');
							print('</form>');
						}

						// End of Register Form
						
						// Login Form
						
						if ($_REQUEST['action'] == 'login') {
							print('<h1 id="login">Login</h1>');
							print('<form action=index.php?logging_in=true method=post>');
							print('Enter Email<input type=text name=email><br>');
							print("Enter Password<input type=text name=password><br>");
							print('<input type=submit value=submit name=submit>');
							print('</form>');
						}

						// End of Login Form
						?>
						<?php
						if ($_REQUEST['user'] != "logged_in") {
							echo '<li class="nav-item"><a class="nav-link" href="index.php?action=login&#login_form">Login</a></li>';
							echo '<li class="nav-item"> <a class="nav-link" href="index.php?action=register&#login_form">Register</a></li>';
						} else if ($_REQUEST['user'] == "logged_in") {
						}
						?>
					</div>

					<!-- end php section -->
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
					<img src="images/headline-home.jpg" alt="Image">
					<span id="home">Maecenas pharetra hendrerit eros sed laoreet. <a href="index.html">Find out
							why.</a></span>
				</div>
				<div id="featured">
					<span class="whatshot"><a href="menu.html">Find out more</a></span>
					<div>
						<a href="menu.html"><img src="images/coffee1.jpg" alt="Image"></a>
						<a href="menu.html"><img src="images/coffee2.jpg" alt="Image"></a>
						<a href="menu.html"><img src="images/coffee3.jpg" alt="Image"></a>
					</div>
				</div>
				<div class="section">
					<ul>
						<li>
							<a href="blog.html"><img src="images/coffee-ingredients.jpg" alt="Image"></a>
							<h2><a href="blog.html">Lorem ipsum</a></h2>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in tellus id eros
								iaculis porttitor eget ultrices mauris. Nulla sodales congue ante, id
							</p>
							<a href="blog.html" class="readmore">Read More</a>
						</li>
						<li>
							<a href="blog.html"><img src="images/black-coffee.jpg" alt="Image"></a>
							<h2><a href="blog.html">Dolor sit amet</a></h2>
							<p>
								Nulla sodales congue ante, id fermentum mi tincidunt ac. Sed eu vestibulum nisl.
								Maecenas pharetra hendrerit eros sed laoreet. Maecenas malesuada
							</p>
							<a href="blog.html" class="readmore">Read More</a>
						</li>
						<li>
							<a href="blog.html"><img src="images/chocolate.jpg" alt="Image"></a>
							<h2><a href="blog.html">Nullam quis</a></h2>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in tellus id eros
								iaculis porttitor eget ultrices mauris. Nulla sodales congue ante, id
							</p>
							<a href="blog.html" class="readmore">Read More</a>
						</li>
					</ul>
					<div>
						<ul>
							<li>
								<h3><a href="blog.html">Lorem ipsum</a></h3>
								<span>28 November 2011</span>
								<p>
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. blandit nunc. Donec in
									velit sed ante interdum condimentum pretium sit amet erat.
								</p>
								<a href="blog.html" class="readmore">Read more</a>
							</li>
							<li>
								<h3><a href="blog.html">Dolor sit amet</a></h3>
								<span>25 November 2011</span>
								<p>
									Lorem ipsum dolor sit amet, consectetur adipiscing elit.
								</p>
								<a href="blog.html" class="readmore">Read more</a>
							</li>
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
						<li class="current">
							<a href="index.html">Home</a>
						</li>
						<li>
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
	<script>
		document.addEventListener("DOMContentLoaded", function () {
			var chatContainer = document.getElementById("chatContainer");
			var messageInput = document.getElementById("messageInput");
			var sendButton = document.getElementById("sendButton");

			// Function to generate chat message HTML
			function generateMessageHTML(sender, message, timestamp, userType) {
				var userLabel = sender === "Admin" ? "" : "Customer";
				var messageElement = document.createElement("div");
				messageElement.classList.add("message");
				messageElement.innerHTML = `
		<div><strong>${userLabel} ${sender}</strong></div>
		<div>${message}</div>
		<div>${timestamp}</div>
		<button class="reply-button" data-recipient="${sender}">Reply</button>
	  `;
				return messageElement;
			}

			// Function to handle reply button click
			function handleReplyClick(event) {
				var recipient = event.target.dataset.recipient;
				var replyInput = document.getElementById("messageInput");
				replyInput.value = "";
				replyInput.placeholder = "Reply to " + recipient;
				replyInput.dataset.recipient = recipient; // Set the recipient value on the dataset
			}


			// Attach event listener to reply buttons
			chatContainer.addEventListener("click", function (event) {
				if (event.target.classList.contains("reply-button")) {
					handleReplyClick(event);
				}
			});

			// Event listener for sending a message
			sendButton.addEventListener("click", function () {
				var newMessage = messageInput.value;
				var recipient = messageInput.dataset.recipient; // Get the recipient from the messageInput dataset

				if (newMessage.trim() !== "" && recipient) {
					// Send the message to the server
					var formData = new FormData();
					formData.append("message", newMessage);
					formData.append("recipient", recipient);

					fetch("insert_chat_data.php", {
						method: "POST",
						body: formData,
					})
						.then(function (response) {
							return response.json();
						})
						.then(function (data) {
							// Successfully inserted the message, update chat messages
							getChatMessages();
						})
						.catch(function (error) {
							console.log("Error sending chat message:", error);
						});

					messageInput.value = "";
					messageInput.dataset.recipient = ""; // Clear the recipient after sending the message
				}
			});

			// Function to render chat messages
			function renderChatMessages(messages) {
				chatContainer.innerHTML = "";
				for (var i = 0; i < messages.length; i++) {
					var { sender, message, timestamp } = messages[i];
					var messageHTML = generateMessageHTML(sender, message, timestamp);
					chatContainer.appendChild(messageHTML);
				}
			}

			// Function to retrieve chat messages from the server
			function getChatMessages() {
				fetch("read_chat_data.php")
					.then(function (response) {
						return response.json();
					})
					.then(function (data) {
						renderChatMessages(data);
					})
					.catch(function (error) {
						console.log("Error fetching chat messages:", error);
					});
			}

			// Initial rendering of chat messages
			getChatMessages();
		});

		function openFormPopup() {
			document.getElementById('popup-container').style.display = 'flex';
		}

		function closeFormPopup() {
			document.getElementById('popup-container').style.display = 'none';
		}
	</script>
</body>

</html>