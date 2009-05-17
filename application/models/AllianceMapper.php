<?php

class Default_Model_AllianceMapper {
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
      $this->setDbTable('Default_Model_DbTable_Alliance');
    }
    return $this->_dbTable;
  }
  
  public function save(Default_Model_Alliance $alliance) {
    $data = array(
      'name' => $alliance->getName()
    );
    
    if (($id = $alliance->getId()) === null) {
      $this->getDbTable()->insert($data);
    }
    else {
      $this->getDbTable()->update($data, array('id = ?' => $id));
    }
  }
  
  public function find($id, Default_Model_Alliance $alliance) {
    $result = $this->getDbTable()->find($id);
    if (count($result) == 0) {
      return;
    }
    $row = $result->current();
    $guestbook->setId($row->id)
              ->setName($row->name);
  }
  
  public function fetchAll() {
    $resultSet = $this->getDbTable()->fetchAll();
    $entries = array();
    foreach ($resultSet as $row) {
      $entry = new Default_Model_Alliance();
      $entry->setId($row->id)
            ->setName($row->name);
      $entries[] = $entry;
    }
    return $entries;
  }
  
}

?>