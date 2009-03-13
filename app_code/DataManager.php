<?php
require_once ("config.php");

class DataManager {
	public static function GetConnection($createIfNull = TRUE) {
		static $conn;
		if (isset($conn) || !$createIfNull)
			return $conn;

		global $cfg;
		$server = $cfg['db']['server'];
		$username = $cfg['db']['username'];
		$password = $cfg['db']['password'];
		$database = $cfg['db']['database'];
		
		$conn = new mysqli($server, $username, $password, $database);
		
		if ($conn->connect_error)
			throw new Exception('Failed to connect to the database.');
		
		return $conn;
	}
	
	public static function CloseConnection() {
		$conn = DataManager::GetConnection(FALSE);
		if (isset($conn))
			$conn->close();
	}
	
	public static function Query($query) {
		$conn = DataManager::GetConnection();
		return $conn->query($query);
	}
	
	public static function QueryAndFetch($query) {
		$result = DataManager::Query($query);
		if ($result) {
			$assoc = $result->fetch_assoc();
			$result->close();
			return $assoc;
		}
		else {
			throw new Exception('Failed to execute query.');
		}
	}
	
	public static function EscapeString($str) {
		$conn = DataManager::GetConnection();
		return $conn->real_escape_string($str);
	}
	
	public static function FormatTimestampForSql($time) {
		return date('Y-m-d H:i:s', $time);
	}
}
?>
