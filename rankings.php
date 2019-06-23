<html>
	<head>
		<title>Awesome Battleships Rankings</title>
		<style>


body
{
	background-image: url("resources/images/background.jpg");
	background-size: 100% 100%;
}

table
{
	margin-left: auto;
	margin-right: auto;
	margin-top: 42px;
	min-width: 400px;
	border-collapse: collapse;
	text-align: center;
	font-size: 16pt;
	font-family: Arial, Helvetica, sans-serif;
}

tr:nth-child(even)
{
	background: rgba(221, 221, 255, 0.5);
}

tr:nth-child(odd)
{
	background: rgba(187, 187, 255, 0.5);
}

td
{
	border: 2px solid black;
	padding: 5px;
}

td.user
{
	border-right: 0;
	font-weight: bold;
}

td.wins
{
	border-left: 0;
}


		</style>
	</head>
	<body>



<?php
include_once("stats.php");
include_once("errorPage.php");

$html = stats_toHTML();
if (is_array($html))
	exit(errorPage($html["error"]));
echo $html;
?>



	</body>
</html>
