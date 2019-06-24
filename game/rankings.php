<html>
	<head>
		<title>Awesome Battleships Rankings</title>
		<style>
				body
				{
					background-size: 100% 100%;
					height: 100%;
					margin: 0;
					background-repeat: no-repeat;
					background-attachment: fixed;
					background-image: linear-gradient(to bottom, #000066 -2%, #9900cc 101%);
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
				.topnav {
				  overflow: hidden;
				  background-color: rgb(0, 0, 0);
				}
				
				.topnav a {
				  float: left;
				  color: #f2f2f2;
				  text-align: center;
				  padding: 14px 16px;
				  text-decoration: none;
				  font-size: 17px;
				}
				
				.topnav a:hover {
					background-color: #ddd;
  					color: black;
				}
				
				.topnav a.active {
				  background-color: rgb(255, 0, 221);
				  color: white;
				}
			</style>
	</head>
	<body>
		<div class="topnav">
			<a href="../homepage.html">Home</a>
			<a href="game.php">Lobbies</a>
			<a class="active"  href="rankings.php">Ranks</a>
			<a href="">About</a>
		</div>
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
