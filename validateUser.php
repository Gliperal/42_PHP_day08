<?php

function validateUser($expected)
{
	$user = "Bob Cannon";
	if ($user != $expected)
		return FALSE;
	return TRUE;
}

?>
