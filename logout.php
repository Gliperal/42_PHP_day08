<?php
session_start();

echo"<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='UTF-8' />
		

	</head>
	<body>
		<ul>
			<li><a href='../index.php'><img src='https://github.com/hirenpateldotnex/rush00_src/blob/master/logo.png?raw=true'></a></li>
			<li><a href='../shop/shop.php'>SHOP</a></li>
		</ul>
		<br>";
if ($_POST["logout"])
{
    $name = $_SESSION["logged_in_user"];
    if ($name)
    {
        $_SESSION["logged_in_user"] = "";
        
        echo "<h1>".$name." is logged out</h1><br \>";
    }
    else
    {
        echo "<h1>Please sign in first</h1?<br />";
    }
}
?>



</body>
</html>