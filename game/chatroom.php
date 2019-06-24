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
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
	background-color: #ffffff;
  margin: 0 auto;
  max-width: 800px;
  padding: 0 20px;
}

.container {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
}

.darker {
  border-color: #ccc;
  background-color: #ddd;
}

.container::after {
  content: "";
  clear: both;
  display: table;
}

.container img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.container img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}
</style>
</head>
	<body>
	<img  style="height: 250pt; width: 250pt" src="../resources/images/chat_room.png"><br>
		<div class="chat" style="height: 200px; width: 50%">


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
			echo "<div class='container'><b>" . $user . "</b> (" . $date_str . "): " . $text . "</div><br />";
		else
			echo "<div class='container darker'><b>" . $user . "</b> (" . $date_str . "): " . $text . "</div><br />\n";
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
