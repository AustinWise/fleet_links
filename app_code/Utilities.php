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
?>