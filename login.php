<?php
session_start();
require_once("auth.php");
require_once("create.php");
// to check exiting user
echo"<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='UTF-8' />
	</head>
	<body>";
if ($_POST["submit"])
{
	if ($_POST["login"] &&
		$_POST["passwd"] &&
		auth($_POST["login"], $_POST["passwd"])) 
	{
		$_SESSION["logged_in_user"] = $_POST["login"];
		//DO SOMETING WHEN LOG IN IS DONE
		echo"<h1>DO SOMETING WHEN LOG IN IS DONE<h1>";
	} 
	else 
	{
		$_SESSION["logged_in_user"] = "";
		//DO SOMETHING WHEN LOG IN WAS NOT OK
		echo "<h1>User : ".$_POST["login"] . " does not exist</h1>";
		echo "<h1><a href='./login.html'>Pls Register<br>DO SOMETHING WHEN LOG IN WAS NOT OK</a></h1>\n";
	}
}
//THIS IS USED TO REGISTER A USER
if ($_POST["login"] &&
	$_POST["passwd"] &&
	$_POST["register"])
	create($_POST["login"], $_POST["passwd"]);
?>
</body>
</html>