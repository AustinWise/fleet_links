<?php

class Default_Model_Fleet {
  protected $_id;
  protected $_name;
  protected $_allianceId;
  
  protected $_mapper;
  
  public function __construct() {
  }
  
  public function __set($name, $value) {
    $method = 'set' . $name;
    if ('mapper' == $name || !method_exists($this, $method)) {
      throw Exception('Invalid property specified');
    }
    $this->$method($value);
  }
  
  public function __get($name) {
    $method = 'get' . $name;
    if ('mapper' == $name || !method_exists($this, $method)) {
      throw Exception('Invalid property specified');
    }
    $this->$method();
  }
  
  public function setId($int) {
    $this->_id = (string) $int;
    return $this;
  }
  
  public function getId() {
    return $this->_id;
  }
  
  public function setAllianceId($int) {
    $this->_allianceId = (int) $int;
    return $this;
  }
  
  public function getAllianceId() {
    return $this->_allianceId;
  }
  
  public function setName($text) {
    $this->_name = (string) $text;
    return $this;
  }
  
  public function getName() {
    return $this->_name;
  }
  
  public function setMapper($mapper) {
    $this->_mapper = $mapper;
    return $this;
  }
  
  public function getMapper() {
    if ($this->_mapper === null) {
      $this->setMapper(new Default_Model_FleetMapper());
    }
    return $this->_mapper;
  }
  
  
  public function save() {
    $this->getMapper()->save($this);
  }
  
  public function find($id) {
    $this->getMapper()->find($id, $this);
    return $this;
  }
  
  public function fetchAll() {
    return $this->getMapper()->fetchAll();
  }
  
}

?>