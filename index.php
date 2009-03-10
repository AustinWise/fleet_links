<?php
require_once('app_code/EveBrowserFactory.php');
require_once('app_code/Fleet.php');
$brow = EveBowserFactory::Get();
if ($brow->IsIGB() && !$brow->IsTrusted())
	$brow->RequireTrust();


?>

<html>
<head>
   <title>test</title>
</head>
<body>

<?php

$all = Fleet::GetAll();

foreach ($all as $f) {
	echo $f->Id . '<br>';
}

/*
date_default_timezone_set('UTC');
$time = time();
echo $time;
echo '<br>';
$di = date('c', $time);
echo $di;
*/
?>

</body>
</html>