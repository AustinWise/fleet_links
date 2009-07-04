<?php

class Default_Model_FleetMapper {
  protected $_dbTable;
  
  public function setDbTable($dbTable) {
    if (is_string($dbTable)) {
      $dbTable = new $dbTable();
    }
    if (!$dbTable instanceof Zend_Db_Table_Abstract) {
      throw new Exception('Invalid table data gateway provided');
    }
    $this->_dbTable = $dbTable;
    return $this;
  }
  
  public function getDbTable() {
    if ($this->_dbTable === null) {
      $this->setDbTable('Default_Model_DbTable_Fleet');
    }
    return $this->_dbTable;
  }
  
  public function save(Default_Model_Fleet $Fleet) {
    $data = array(
      'name' => $Fleet->getName()
    );
    
    if (($id = $Fleet->getId()) === null) {
      $this->getDbTable()->insert($data);
    }
    else {
      $this->getDbTable()->update($data, array('id = ?' => $id));
    }
  }
  
  public function find($id, Default_Model_Fleet $fleet) {
    $result = $this->getDbTable()->find($id);
    if (count($result) == 0) {
      return;
    }
    $row = $result->current();
    $fleet->setId($row->id)
          ->setAllianceId($row->allianceId)
          ->setName($row->name);
  }
  
  public function fetchAll() {
    $resultSet = $this->getDbTable()->fetchAll();
    $entries = array();
    foreach ($resultSet as $row) {
      $entry = new Default_Model_Fleet();
      $entry->setId($row->id)
            ->setAllianceId($row->allianceId)
            ->setName($row->name)
            ->setMapper($this);
      $entries[] = $entry;
    }
    return $entries;
  }
  
  public function getFleetsForAlliance($allianceId) {
    $table = $this->getDbTable();
    $resultSet = $table->fetchAll($table->select()->where('allianceId = ?', $allianceId));
    $entries = array();
    foreach ($resultSet as $row) {
      $entry = new Default_Model_Fleet();
      $entry->setId($row->id)
            ->setAllianceId($row->allianceId)
            ->setName($row->name)
            ->setMapper($this);
      $entries[] = $entry;
    }
    return $entries;
  }
  
}

?>