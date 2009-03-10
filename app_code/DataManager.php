<?php
require_once ("config.php");

class DataManager {
	public static function GetConnection() {
		global $cfg;
		$server = $cfg['db']['server'];
		$username = $cfg['db']['username'];
		$password = $cfg['db']['password'];
		$database = $cfg['db']['database'];
		$conn = mysql_connect($server, $username, $password);
		mysql_select_db($database, $conn);
		return $conn;
	}
	
	public static function Query($query) {
		return mysql_query($query, DataManager::GetConnection());
	}
	
	public static function QueryAndFetch($query) {
		return mysql_fetch_assoc(Query($query));
	}
	
	public static function EscapeString($str) {
		return mysql_real_escape_string($str, DataManager::GetConnection());
	}
}
?>
