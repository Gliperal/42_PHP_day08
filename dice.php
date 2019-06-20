<?php

function rollD6()
{
	return rand(1, 6);
}

function rollManyD6($n)
{
	$total = 0;
	while ($n > 0)
	{
		$total += rollD6();
		$n--;
	}
	return $total;
}

?>
