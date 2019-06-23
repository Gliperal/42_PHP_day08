<?php

include_once("whoami.php");

function validateUser($expected)
{
	$user = whoami();
	if ($user != $expected)
		return FALSE;
	return TRUE;
}

?>
