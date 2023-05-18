<?php
$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";

$dlink = mysql_connect($hostname, $db_login, $db_pass) or die("Could not connect");
mysql_select_db($database) or die("Could not select database");

setcookie('email', "", time() - 1);
setcookie('type', "", time() - 1);
echo "<meta http-equiv='refresh' content='0;url=index.php'>";
?>

<a>Welcome, <?php echo $_COOKIE['type'] . ' , ' . $_COOKIE['email'] . '' ?></a>
