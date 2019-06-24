<?php
session_start();
include_once("fileData.php");
include_once("../whoami.php");

if (array_key_exists("submit", $_POST) and $_POST["submit"] == "Chat")
{
	$current_user = whoami();
	if ($current_user === FALSE)
		$current_user = "Anonymous";
	if (load_data("chatroom.txt", $messages) === FALSE)
		echo "Error loading chatroom." . PHP_EOL;
	else
	{
		$messages[] =
		[
			"login" => $current_user,
			"time" => time(),
			"msg" => $_POST["msg"]
		];
		if (save_data("chatroom.txt", $messages) === FALSE)
			echo "Error saving chatroom." . PHP_EOL;
		else
			echo "OK";
	}
	header("Location: /game/chatroom.php");
	exit();
}
?>


<html>
	<body>
		<div class="chat">


<?php
$current_user = whoami();
if (load_data("chatroom.txt", $messages) === FALSE)
	echo "Error loading chatroom.";
else
	foreach($messages as $message)
	{
		if (
			!array_key_exists("login", $message) or
			!array_key_exists("time", $message) or
			!array_key_exists("msg", $message)
		)
			continue;
		$user = $message["login"];
		$dt = new DateTime();
		date_default_timezone_set("America/Los_Angeles");
		$date_str = date("H:i", $message["time"]);
		$text = $message["msg"];
		if ($user == $current_user)
			echo "<div class=\"chat-msg, me\"><b>" . $user . "</b> (" . $date_str . "): " . $text . "</div><br />";
		else
			echo "<div class=\"chat-msg\"><b>" . $user . "</b> (" . $date_str . "): " . $text . "</div><br />\n";
	}
?>


		</div>
		<br/>
		<div class="speak">
		</div>
		<form action="/game/chatroom.php" method="post">
			<input type="text" name="msg" value="" />
			<input type="submit" name="submit" value="Chat" />
		</form>
	</body>
</html>
