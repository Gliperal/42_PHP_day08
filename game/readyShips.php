<!--form class="radio" action="active.php" method="post"-->
<form class="radio">

<?php
include_once("GameMaster.class.php");
include_once("Ship.class.php");
include_once("ImperialIronclad.class.php");
require_once 'getGame.php';
session_start();
$game = getGame($_GET["lobby"]);
if (is_array($game))
	Console::log_error($game["error"]);
else
	foreach($game->getReadyShips() as $ship)
	{
		$name = $ship->getName();
		if ($ship instanceof ImperialIronclad)
			$type = 'Imperial Ironclad';
		else if ($ship instanceof ImperialScout)
			$type = 'Imperial Scout';
		echo '<input type="radio" name="active-select" value='.$name.'>'.$type.':<br /> '.$name.'<br /></input><br />';
		
	}
saveGame();
?>

</form>
<button id="active-submit" style=' border: none;background-color: Transparent' ><img  style='height:	90px;width:	250px' src='/resources/images/activate.png' /></button>
