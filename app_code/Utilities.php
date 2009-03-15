<?php
date_default_timezone_set('UTC');

// returns a point in the middle of the last downtime.
// since the sever now starts up faster, it's not too far into downtime.
function LastDowntimeMidpoint() {
	$now = time();
	$midDowntime = gmmktime(11, 5, 0);
	if (($midDowntime - $now) > 0) {
		$datePieces = getdate($now);
		return gmmktime(11, 5, 0, gmdate("n"), gmdate("j") - 1, gmdate("Y"));
	}
	else {
		return $midDowntime;
	}
}

function RedirectResponse($path) {
//	header('Location:http://' . GetServer('HTTP_HOST') . '/' . $path);
	header('Location:' . $path);
	die(302);
}

function GetGet($name) {
	return get_magic_quotes_gpc() ? stripslashes($_GET[$name]) : $_GET[$name];
}

function GetPost($name) {
	return get_magic_quotes_gpc() ? stripslashes($_POST[$name]) : $_POST[$name];
}

function GetServer($name) {
	return get_magic_quotes_gpc() ? stripslashes($_SERVER[$name]) : $_SERVER[$name];
}
?>