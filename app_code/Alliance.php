<?php
require_once('DataManager.php');
require_once('Utilities.php');

class Alliance {
	function __construct() {
	}
	
	public $Id;
	public $Name;

	private $inDatabase = 0;
		
	// fills a Alliance object with data from mysql
	private static function fill(&$a, $id, $name) {
		$a->Id = (int)$id;
		$a->Name = $name;
		$a->inDatabase = 1;
	}

	// returns the alliance with the given id.
	// throws an exception if it's not found.
	public static function Get($id) {
		if (!is_int($id)) {
			throw new Exception("id was not an int.");
		}
		
		$conn = DataManager::GetConnection();
		$stmt = $conn->prepare('SELECT id, name FROM alliance WHERE id = ?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($id, $name);
		
		if (!$stmt->fetch()) {
			throw new Exception("Alliance not found.");
		}

		$a = new Alliance();
		Alliance::fill($a, $id, $name);

		$stmt->close();
		
		return $a;
	}
	
	// Returns array contain all the Alliances.
	public static function GetAll() {
		$query = DataManager::Query("SELECT id, name FROM alliance");
		$items = array();
		while ($assoc = $query->fetch_assoc()) {
			$a = new Alliance();
			Alliance::fill($a, $assoc['id'], $assoc['name']);
			$items[] = $a;
		}
		$query->close();
		return $items;
	}
	
	// Make sure an alliance is already in the database.
	public static function EnsureAlliance($id, $name) {
		$a;
		try {
			$a = Alliance::Get($id);
		}
		catch (Exception $ex) {
			$a = new Alliance();
			$a->Id = $id;
			$a->Name = $name;
			$a->Save();
		}
		return $a;
	}
	
	public static function GetAlliancesOtherThanMineWithFleets($myAllianceId) {
		if (!is_int($myAllianceId)) {
			throw new Exception("myAllianceId was not an int.");
		}
		
		$conn = DataManager::GetConnection();
		$stmt = $conn->prepare('SELECT id, name FROM alliance WHERE id in (SELECT allianceId FROM fleet WHERE added > ?) AND id != ?');
		$stmt->bind_param('si', DataManager::FormatTimestampForSql(LastDowntimeMidpoint()), $myAllianceId);
		$stmt->execute();
		$stmt->bind_result($id, $name);
		
		$alliances = array();
		while ($stmt->fetch()) {
			$a = new Alliance();
			Alliance::fill($a, $id, $name);
			$alliances[] = $a;
		}

		$stmt->close();

		return $alliances;
	}
	
	public function ActiveFleets() {
		return Fleet::GetFleetsForAlliance($this->Id);
	}
	
	// Returns TRUE if the Aliiance record was added or updated in
	// the database, FALSE otherwise.
	// Update not implemented yet.
	public function Save() {
		if (!$this->Validate())
			throw new Exception('Alliance not valid; unable to save.');
		$conn = DataManager::GetConnection();
		if ($this->inDatabase == 0) {
			$stmt = $conn->prepare('INSERT INTO alliance (id, name) VALUES (?, ?)');
			$stmt->bind_param('is', $this->Id, $this->Name);
			$stmt->execute();
			$rows = $stmt->affected_rows;
			$stmt->close();
			if ($rows === 1) {
				$this->inDatabase = 1;
				return TRUE;
			}
			else
				return FALSE;
		}
		else {
			$stmt = $conn->prepare('UPDATE alliance SET name=? WHERE id=?');
			$stmt->bind_param('si', $this->Name, $this->Id);
			$stmt->execute();
			$rows = $stmt->affected_rows;
			$stmt->close();
			if ($rows === 1)
				return TRUE;
			else
				return FALSE;
		}
		return FALSE;

	}
	
	// Returns TRUE if the Alliance is valid and ready to be
	// saved to the database.
	public function Validate() {
		if (!isset($this->Id) || !is_int($this->Id))
			return FALSE;
		if (isset($this->Name) && is_string($this->Name)) {
			$strlen = strlen($this->Name);
			if ($strlen < 1 || $strlen > 50)
				return FALSE;
		}
		else {
			return FALSE;
		}
		return TRUE;
	}
}
?>
