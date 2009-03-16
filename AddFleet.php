<?php
require_once('app_code/EveBrowserFactory.php');
require_once('app_code/Utilities.php');
require_once('app_code/Fleet.php');
require_once('app_code/Alliance.php');
require_once('app_code/DataManager.php');

$brow = EveBowserFactory::Get();
if (!($brow->IsIGB() && $brow->IsTrusted()))
	RedirectResponse('index.php');

if (isset($_POST['fleetLink']) && isset($_POST['name'])){
	$matches;
	if (preg_match('/gang:(?<id>\d+)/', GetPost('fleetLink'), $matches)) {
		$a = Alliance::EnsureAlliance($brow->AllianceId(), $brow->AllianceName());
		$f = new Fleet();
		$f->Id = $matches['id'];
		$f->AllianceId = $a->Id;
		$f->Name = GetPost('name');
		$f->Added = time();
		if ($f->Validate()) {
			$f->Save();
			
			// this seems like a good place to delete old fleets
			Fleet::DeleteOldFleets();

         DataManager::CloseConnection();
			RedirectResponse('index.php');
		}
	}
}

?>

<html>

<head>
	<title>Fleet Links - Add Fleet</title>
</head>

<body>
<h1><a href="index.php">Fleet Links</a></h1>
<h2>Add Fleet</h2>
<form action="AddFleet.php" method="post">
    Fleet alliance: <?php echo htmlspecialchars($brow->AllianceName()); ?><br>
    After setting your fleet to be self-invite for alliance, post the fleet invite to
    a channel. (<a href="PostInvite.jpg">screen shot</a>)<br>
    Right click the "Fleet Invitation (Alliance)" link and select copy.
    (<a href="CopyInvite.jpg">screen shot</a>)<br>
    Right click on this text box and select Paste:
    <input type="text" name="fleetLink" id="fleetLink"><br>
    The name of your fleet:
    <input type="text" maxlength="50" id="name" name="name"><br>
    <input type="submit" name="btnAdd" value="Add">
</form>
<hr>
<small>Feel free contact <a href="showinfo:1376//1164427832">WoogyDude</a> in game or via <a href="http://www.goonfleet.com/member.php?u=22552">GoonFleet private message</a> if you have any questions or comments.</small>
</body>

</html>
