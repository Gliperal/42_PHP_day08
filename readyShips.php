<!--form class="radio" action="active.php" method="post"-->
<form class="radio">

<?php
include_once("GameMaster.class.php");
include_once("Ship.class.php");
include_once("ImperialIronclad.class.php");
session_start();
$game = $_SESSION['master'];
foreach($game->getReadyShips() as $ship)
{
	$name = $ship->getName();
	if ($ship instanceof ImperialIronclad)
		$type = 'Imperial Ironclad';
	echo '<input type="radio" name="active-select" value='.$name.'>'.$type.':<br /> '.$name.'<br /></input><br />';
}
?>

</form>
<button id="active-submit">Activate Ship</button>
