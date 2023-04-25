<?php
  class Classes_DAO extends Db_DAO {
    function __construct($db) {
      parent::__construct($db, 'classes');
    }

// Add new class
    function add($userCode, $classCode, $description) {
      $data = [
        'userCode' => $userCode,
        'classCode' => $classCode,
        'description' => $description
      ];

      return $this->insertAutoEntry($data);
    }

// Get class details
    function get($classKey) {
      $this->SELECT = '*';
      $this->WHERE = 'classKey = ?';
      $match = [
        $classKey
      ];

      return $this->fetch($match);
    }

// Get class details
    function getByCode($classCode) {
      $this->SELECT = '*';
      $this->WHERE = 'classCode = ?';
      $match = [
        $classCode
      ];

      return $this->fetch($match);
    }

// Get all class details
    function getAll($userCode) {
      $this->SELECT = '*';
      $this->WHERE = 'userCode = ?';
      $match = [
        $userCode
      ];

      return $this->fetchAll($match);
    }

// Edit class's details
    function edit($classKey, $details) {
      $this->WHERE = 'classKey = ?';
      $match = [
        $classKey
      ];

      return $this->updateAutoEntry($details, $match);
    }

// Delete class
    function deleteClass($classKey) {
      $this->WHERE = 'classKey = ?';
      $match = [
        $classKey
      ];

      return $this->delete($match);
    }

// Check if property is present in property string
    static function haveProperty($properties, $property) {
      if (strpos($properties, $property) === false) {
        return false;
      } else {
        return true;
      }
    }

    function addProperty($classKey, $property) {
      $this->SELECT = '*';
      $this->WHERE = 'classKey = ?';
      $match = [
        $classKey
      ];
      $result = $this->fetch($match);

      $details = [
        'properties' => $result['properties'] . $property
      ];

      return $this->updateAutoEntry($details, $match);
    }

    function removeProperty($classKey, $property) {
      $this->SELECT = '*';
      $this->WHERE = 'classKey = ?';
      $match = [
        $classKey
      ];
      $result = $this->fetch($match);

      $details = [
        'properties' => str_replace($property, '', $result['properties'])
      ];

      return $this->updateAutoEntry($details, $match);
    }

  }
?>
