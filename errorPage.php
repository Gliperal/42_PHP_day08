<?php

function errorPage($error)
{
	echo "<html><body>";
	echo "<b>ERROR: </b>" . $error . "<br/>";
	echo '<button onclick="history.go(-1);">Go Back</button>';
	echo "</body></html>";
}

?>
