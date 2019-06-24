<?php
session_start();
require_once('auth.php');
require_once('create.php');
// to check exiting user
echo"<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='UTF-8' />
        <link href='style.css' rel='stylesheet' type='text/css' />
	</head>
	<body>
";
if ($_POST['submit'])
{
	if ($_POST['login'] &&
		$_POST['passwd'] &&
		auth($_POST['login'], $_POST['passwd'])) 
	{
		$_SESSION['logged_in_user'] = $_POST['login'];
		//DO SOMETING WHEN LOG IN IS DONE
		header("Location: game/lobbies.php"); /* Redirect browser */
		exit();
	} 
	else 
	{
		$_SESSION['logged_in_user'] = '';
		//DO SOMETHING WHEN LOG IN WAS NOT OK
		echo 	"<div class='login'>
					<img src='resources/images/ship_royale_logo.png' style='height : 70%;width: 100%'>
					<h1>" . $_POST['login'] . "is invalid</h1>
        			<form name='login.php' action='login.php' method='POST'>
           				<input type='text' name='login' value='' placeholder='Username' required='required' />
            			<input type='password' name='passwd' value='' placeholder='Password' required='required' />
            			<button type='submit' name='submit' value='submit' class='btn btn-primary btn-block btn-large'>Log In</button>
            			<br>
            			<button type='submit' name='register' value='register' class='btn btn-primary btn-block btn-large'>Register</button>
        			</form>
    			</div>";
	}
}
//THIS IS USED TO REGISTER A USER
if ($_POST['login'] &&
	$_POST['passwd'] &&
	$_POST['register'])
{
	create($_POST['login'], $_POST['passwd']);

}
	
	
?>
</body>
</html>