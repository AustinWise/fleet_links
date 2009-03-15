<?php
require_once('app_code/EveBrowserFactory.php');
require_once('app_code/Fleet.php');
require_once('app_code/Alliance.php');
$brow = EveBowserFactory::Get();
$ogb = !$brow->IsIGB();
$notTrusted = ($brow->IsIGB() && !$brow->IsTrusted());
$trusted = ($brow->IsIGB() && $brow->IsTrusted());

if ($notTrusted)
	$brow->RequireTrust();


$otherAlliances;
if ($trusted) {
	$otherAlliances = Alliance::GetAlliancesOtherThanMineWithFleets($brow->AllianceID());
}
else {
	$otherAlliances = Alliance::GetAlliancesOtherThanMineWithFleets(-1);
}

$otherFleets = array();
foreach ($otherAlliances as $a) {
	$otherFleets[$a->Id] = $a->ActiveFleets();
}


$myFleets;
if ($trusted) {
	$myFleets = Fleet::GetFleetsForAlliance($brow->AllianceId());
}


DataManager::CloseConnection();

function printAllianceFleets($name, $fleets, $myFleet = TRUE) {
	echo '<h2>' . $name . '</h2>';
	foreach ($fleets as $f) {
		echo gmdate('H:m', $f->Added);
		echo ' ';
		if ($myFleet) {
			echo '<a href="gang:' . $f->Id . '">' . htmlspecialchars($f->Name) . '</a>';
			echo ' <a href="DeleteFleet.php?id=' . $f->Id . '">[Delete]</a>';
		}
		else
			echo htmlspecialchars($f->Name);
		echo '<br />';
	}
}

?>

<html>
<head>
   <title>Fleet Links</title>
</head>
<body>
<h1>Fleet Links</h1>
<?php if ($ogb) { 
	echo '<h2><font color="red">This page is designed to be viewed in the EVE in-game browser.</font></h2>';
}
elseif ($notTrusted) { 
	echo '<h2><font color="red">You must add this site to your trusted site list. <a href="MakeTrusted.html">Instructions here.</a></font></h2>';
}
elseif ($trusted) {
	echo '<a href="AddFleet.php">Add a fleet</a><br>';
	printAllianceFleets(htmlspecialchars($brow->AllianceName()), $myFleets);
}


foreach ($otherAlliances as $a) {
	printAllianceFleets(htmlspecialchars($a->Name), $otherFleets[$a->Id], FALSE);
}

?>

	<hr>
	<small>Feel free contact <a href="showinfo:1376//1164427832">WoogyDude</a> in game or via <a href="http://www.goonfleet.com/member.php?u=22552">GoonFleet private message</a> if you have any questions or comments.</small>

</body>
</html>