<?php
require_once('EveBrowserFactory.php');
$brow = EveBowserFactory::Get();
if ($brow->IsIGB() && !$brow->IsTrusted())
	$brow->RequireTrust();


?>

<html>
<head>
   <title>test</title>
</head>
<body>


</body>
</html>