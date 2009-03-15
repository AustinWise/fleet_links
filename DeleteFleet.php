<?php
require_once('app_code/EveBrowserFactory.php');
require_once('app_code/Utilities.php');
require_once('app_code/Fleet.php');
require_once('app_code/DataManager.php');

$brow = EveBowserFactory::Get();
if (!($brow->IsIGB() && $brow->IsTrusted()))
	RedirectResponse('index.php');

if (!isset($_GET['id']))
	RedirectResponse('index.php');

$id = (GetGet('id'));

$f;
try {
	$f = Fleet::Get($id);
}
catch (Exception $ex) {
	RedirectResponse('index.php');
}

// you are only allowed to delete your own alliances fleet.
if ($f->AllianceId != $brow->AllianceId())
	RedirectResponse('index.php');

// delete the fleet if the button was pressed.
if (isset($_POST['btnDelete'])) {
	$f->Delete();
	RedirectResponse('index.php');	
}

DataManager::CloseConnection();

?>

<html>

<head>
	<title>Fleet Links - Delete Fleet</title>
</head>

<body>
	<h1><a href="index.php">Fleet Links</a></h1>
	<h2>Delete Fleet</h2>
    <form action="DeleteFleet.php?id=<?php echo $id; ?>" method="post">
        Name: <?php echo htmlspecialchars($f->Name); ?><br>
        Alliance: <?php echo htmlspecialchars($brow->AllianceName()); ?><br>
        <font color="red">WARNING: Don't be a dick and delete someone's fleet without confirming that it's dead.</font><br>
        <input type="submit" id="btnDelete" name="btnDelete" value="Delete">
    </form>
    <hr>
    <small>Feel free contact <a href="showinfo:1376//1164427832">WoogyDude</a> in game or via <a href="http://www.goonfleet.com/member.php?u=22552">GoonFleet private message</a> if you have any questions or comments.</small>
</body>

</html>
