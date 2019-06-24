<?php
// Removed session_start from whoami
// If this causes issues, include a new session_start() in the page calling this function
//session_start();
function whoami()
{
	if (!array_key_exists("logged_in_user", $_SESSION))
		return FALSE;
	if ($_SESSION["logged_in_user"])
		return ($_SESSION["logged_in_user"]);
	else
		return false;
}

?>
