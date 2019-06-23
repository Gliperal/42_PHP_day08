<?php

function escapeQuotes($string)
{
	return preg_replace("/\"(.*?)\"/", "&#147$1&#148", $string);
}

?>
