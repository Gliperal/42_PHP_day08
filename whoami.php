<?php
// Removed session_start from whoami
// If this causes issues, include a new session_start() in the page calling this function
//session_start();
function whoami()
{
	if (!array_key_exists("loggued_on_user", $_SESSION))
		return FALSE;
	if ($_SESSION["loggued_on_user"])
		return ($_SESSION["loggued_on_user"]);
	else
		return false;
}

?>
