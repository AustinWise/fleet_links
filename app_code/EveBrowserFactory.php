<?php
require_once('config.php');
require_once('EveBrowser.php');
require_once('OfflineEveBrowser.php');

class EveBowserFactory {
	static function Get() {
		global $cfg;

		if ($cfg['eve']['useFakeIGB'])
			return new OfflineEveBrowser();
		return new EveBrowser();
	}
}
?>