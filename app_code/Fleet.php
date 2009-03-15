<?php
require_once('DataManager.php');
class Fleet {
	function __construct() {
	}
	
	// $Id is an integer, but it is stored as a string since it won't fit in int
	// and when converted to a float sometimes it does not convert correctly
	public $Id;
	public $AllianceId;
	public $Name;
	public $Added;
	
	private $inDatabase = FALSE;
	
	public static function Get($id) {
		$conn = DataManager::GetConnection();
		$stmt = $conn->prepare('SELECT id, allianceId, name, added FROM fleet WHERE id = ?');
		$stmt->bind_param('d', $id);
		$stmt->execute();
		$stmt->bind_result($id, $allianceId, $name, $added);
		
		if (!$stmt->fetch()) {
			throw new Exception("Fleet not found.");
		}

		$f = new Fleet();
		Fleet::fill($f, $id, $allianceId, $name, $added);

		$stmt->close();
		
		return $f;
	}
	
	// fills a Fleet object with data from mysql
	private static function fill(&$f, $id, $allianceId, $name, $added) {
		$f->Id = $id;
		$f->AllianceId = (int)$allianceId;
		$f->Name = $name;
		$f->Added = strtotime($added);
		$f->inDatabase = TRUE;
	}

	// Returns array contain all the Fleets.
	public static function GetAll() {
		$query = DataManager::Query("SELECT id, allianceId, name, added FROM fleet");
		$items = array();
		while ($assoc = $query->fetch_assoc()) {
			$f = new Fleet();
			Fleet::fill($f, $assoc['id'], $assoc['allianceId'], $assoc['name'], $assoc['added']);
			$items[] = $f;
		}
		$query->close();
		return $items;
	}
	
	public static function GetFleetsForAlliance($allianceId) {
		if (!is_int($allianceId)) {
			throw new Exception("allianceId was not an int.");
		}
		
		$conn = DataManager::GetConnection();
		$stmt = $conn->prepare('SELECT id, allianceid, name, added FROM fleet WHERE added > ? AND allianceid = ?');
		$stmt->bind_param('si', DataManager::FormatTimestampForSql(LastDowntimeMidpoint()), $allianceId);
		$stmt->execute();
		$stmt->bind_result($id, $allianceId, $name, $added);
		
		$fleets = array();
		while ($stmt->fetch()) {
			$f = new Fleet();
			Fleet::fill($f, $id, $allianceId, $name, $added);
			$fleets[] = $f;
		}

		$stmt->close();

		return $fleets;
	}
	
	public static function DeleteOldFleets() {
		$conn = DataManager::GetConnection();
		$stmt = $conn->prepare('DELETE FROM fleet WHERE added < ?');
		$stmt->bind_param('s', DataManager::FormatTimestampForSql(LastDowntimeMidpoint()));
		$stmt->execute();
		$stmt->close();
	}
	
	public static function DeleteFleet($id) {
		$conn = DataManager::GetConnection();
		$stmt = $conn->prepare('DELETE FROM fleet WHERE id = ?');
		$stmt->bind_param('d', $id);
		$stmt->execute();
		$stmt->close();
	}

	public function Delete() {
		if (!$this->inDatabase)
			return;
		Fleet::DeleteFleet($this->Id);
		$this->inDatabase = FALSE;
	}

	// Returns TRUE if the Fleet record was added or updated in
	// the database, FALSE otherwise.
	// Update not implemented yet.
	public function Save() {
		if (!$this->Validate())
			throw new Exception('Fleet not valid; unable to save.');
		$conn = DataManager::GetConnection();
		if (!$this->inDatabase) {
			$stmt = $conn->prepare('INSERT INTO fleet (id, allianceId, name, added) VALUES (?, ?, ?, ?)');
			$stmt->bind_param('diss', $this->Id, $this->AllianceId, $this->Name, DataManager::FormatTimestampForSql($this->Added));
			$stmt->execute();
			$rows = $stmt->affected_rows;
			$stmt->close();
			if ($rows === 1) {
				$this->inDatabase = TRUE;
				return TRUE;
			}
			else
				return FALSE;
		}
		else {
			$stmt = $conn->prepare('UPDATE fleet SET allianceId=?, name=?, added=? WHERE id=?');
			$stmt->bind_param('issd', $this->AllianceId, $this->Name, DataManager::FormatTimestampForSql($this->Added), $this->Id);
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
	
	// Returns TRUE if the Fleet is valid and ready to be
	// saved to the database.
	public function Validate() {
		if (!isset($this->Id) || !preg_match('/^\d+$/', $this->Id))
			return FALSE;
		if (!isset($this->AllianceId) || !is_int($this->AllianceId))
			return FALSE;
		if (!isset($this->Added) || !is_int($this->Added))
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
