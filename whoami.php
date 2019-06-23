<?php
session_start();
function whoami()
{
	if ($_SESSION["loggued_on_user"])
		return ($_SESSION["loggued_on_user"] . "\n");
	else
		return false;
}

?>