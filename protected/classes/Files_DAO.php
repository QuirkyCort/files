<?php
  class Files_DAO extends Db_DAO {
    function __construct($db) {
      parent::__construct($db, 'files');
    }

// Returns a random string
    function randString($length) {
      $result = '';
      $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
      $charactersLength = strlen($characters);
      for ($i=0; $i<$length; $i++ ) {
        $result .= $characters[rand(0, $charactersLength - 1)];
      }
      return $result;
    }

// Create a random userKey
    function createKey() {
      $this->SELECT = 'fileKey';
      $this->WHERE = 'fileKey = ?';
      do {
        $key = $this->randString(10);
        $result = $this->fetch([$key]);
      } while ($result != FALSE);

      return $key;
    }

// Add new file
    function add($classCode, $userName, $fileName, $size, $teacher, $data, $selfGenID, $share=0) {
      $data = [
        'fileKey' => $this->createKey(),
        'classCode' => $classCode,
        'userName' => $userName,
        'fileName' => $fileName,
        'size' => $size,
        'date' => time(),
        'share' => $share,
        'teacher' => $teacher,
        'data' => $data,
        'selfGenID' => $selfGenID
      ];

      return $this->insertAutoEntry($data);
    }

// Get file details
    function get($fileKey) {
      $this->SELECT = '*';
      $this->WHERE = 'fileKey = ?';
      $match = [
        $fileKey
      ];

      return $this->fetch($match);
    }

// Get all file details
    function getAll($classCode) {
      $this->SELECT = '*';
      $this->WHERE = 'classCode = ?';
      $match = [
        $classCode
      ];
      $this->ORDER = 'date DESC';

      return $this->fetchAll($match);
    }

// Edit file's details
    function edit($fileKey, $details) {
      $this->WHERE = 'fileKey = ?';
      $match = [
        $fileKey
      ];

      return $this->updateAutoEntry($details, $match);
    }

// Delete file
    function deleteFile($fileKey) {
      $this->WHERE = 'fileKey = ?';
      $match = [
        $fileKey
      ];

      return $this->delete($match);
    }

  }
?>
