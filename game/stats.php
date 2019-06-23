<?php

include_once("fileData.php");

function stats_addWinner($winner)
{
	if (load_data("stats.txt", $stats) === FALSE)
		return ["error" => "Failed to load server files."];
	if (array_key_exists($winner, $stats))
		$stats[$winner]++;
	else
		$stats[$winner] = 1;
	if (save_data("stats.txt", $stats) === FALSE)
		return ["error" => "Failed to save server files."];
	return TRUE;
}

function stats_toHTML()
{
	if (load_data("stats.txt", $stats) === FALSE)
		return ["error" => "Failed to load server files."];
	$html = "<table cellpadding=0 cellspacing=0>";
	arsort($stats);
	foreach ($stats as $user=>$wins)
	{
		$html .= "<tr>";
		$html .= "<td class=\"user\">" . $user . "</td><td class=\"wins\">" . $wins . " wins</td>";
		$html .= "</tr>";
	}
	$html .= "</table>";
	return $html;
}

?>
