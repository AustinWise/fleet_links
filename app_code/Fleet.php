<?php
require_once('DataManager.php');
class Fleet {
	function __construct() {
	}
	
	public $Id;
	public $AllianceId;
	public $Name;
	public $Added;
	
	private $inDatabase = 0;
	
	public static function Get($id) {
		if (!is_int($id)) {
			throw new Exception("id was not an int.");
		}
		
		$assoc = DataManger::QueryAndFetch("SELECT * FROM fleet WHERE id = " . $id);
		if (!$assoc) {
			throw new Exception('Fleet not found.');
		}
		
		$f = new Fleet();
		fill($f, $assoc);
	}
	
	// fills a Fleet object with data from a call to mysql_fetch_assoc.
	private static function fill(&$f, &$assoc) {
		$f->Id = $assoc['id'];
		$f->AllianceId = $assoc['allianceId'];
		$f->Name = $assoc['name'];
		$f->Added = $assoc['added'];
		$f->inDatabase = 1;
	}

	// Returns array contain all the Fleets.
	public static function GetAll() {
		$query = DataManager::Query("SELECT * FROM fleet");
		$items = array();
		while ($assoc = mysql_fetch_assoc($query)) {
			$f = new Fleet();
			Fleet::fill($f, $assoc);
			$items[] = $f;
		}
		return $items;
	}
	
	// Returns TRUE if the Fleet record was added or updated in
	// the database, FALSE otherwise.
	// Update not implemented yet.
	public function Save() {
		if (!$this->Validate())
			return FALSE;
		if ($this->inDatabase == 0) {
			$sql = 'INSERT INTO fleet (id, allianceId, name, added) VALUES (';
			$sql .= (string)$this->Id . ', ';
			$sql .= (string)$this->AllianceId . ', ';
			$sql .= '"' . DataManager::EscapeString($this->Name) . '", ';
			date_default_timezone_set('UTC');
			$sql .= '"' . date('Y-m-d H:i:s', $this->Added) . '")';
			DataManager::Query($sql);
			if (mysql_affected_rows() === 1) {
				$this->inDatabase = 1;
				return TRUE;
			}
			else
				return FALSE;
		}
		return FALSE;
	}
	
	// Returns TRUE if the Fleet is valid and ready to be
	// saved to the database.
	public function Validate() {
		if (!isset($this->Id) || !is_int($this->Id))
			return FALSE;
		if (!isset($this->AllianceId) || !is_int($this->AllianceId))
			return FALSE;
		if (!isset($this->Added) || !is_int($this->Added))
			return FALSE;
		if (isset($this->Name) && is_string($this->Name)) {
			$strlen = strlen($this->Name);
			if ($strlen == 0 || $strlen > 50)
				return FALSE;
		}
		else {
			return FALSE;
		}
		return TRUE;
	}
}
?>
