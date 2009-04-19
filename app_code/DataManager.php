<?php
require_once ("config.php");

class DataManager {
  private static $instance = null;
  public static function GetInstance() {
    if (self::$instance == null) {
      self::$instance = new DataManager();
    }
    return self::$instance;
  }

  private $conn;
  
  private function __construct() {
		global $cfg;
		$server = $cfg['db']['server'];
		$username = $cfg['db']['username'];
		$password = $cfg['db']['password'];
		$database = $cfg['db']['database'];
		
		$this->conn = new mysqli($server, $username, $password, $database);		
		
		if ($this->conn->connect_error)
			throw new Exception('Failed to connect to the database.');
  }
	
	public function CloseConnection() {
		if (isset($this->conn)) {
			$this->conn->close();
		}
	}
	
	public function GetConnection() {
    return $this->conn;
	}
	
	public function Query($query) {
		return $this->conn->query($query);
	}
	
	public function QueryAndFetch($query) {
		$result = $this->Query($query);
		if ($result) {
			$assoc = $result->fetch_assoc();
			$result->close();
			return $assoc;
		}
		else {
			throw new Exception('Failed to execute query.');
		}
	}
	
	public function EscapeString($str) {
		return $this->conn->real_escape_string($str);
	}
	
	public function FormatTimestampForSql($time) {
		return date('Y-m-d H:i:s', $time);
	}
}
?>
