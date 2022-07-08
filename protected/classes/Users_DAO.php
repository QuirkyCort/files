<?php
  class Users_DAO extends Db_DAO {
    function __construct($db) {
      parent::__construct($db, 'users');
    }

// Add new user
    function add($name, $code, $password, $roles) {
      $data = [
        'name' => $name,
        'userCode' => $code,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'roles' => $roles
      ];

      return $this->insertAutoEntry($data);
    }

// Check if password is correct
    function checkPassword($username, $password) {
      $this->SELECT = '*';
      $this->WHERE = 'name = ?';
      $match = [
        $username
      ];
      $result = $this->fetch($match);

      if ($result == FALSE)
        return FALSE;

      if (password_verify($password,$result['password']))
        return $result;
      else
        return FALSE;
    }

// Get user details
    function get($userKey) {
      $this->SELECT = '*';
      $this->WHERE = 'userKey = ?';
      $match = [
        $userKey
      ];

      return $this->fetch($match);
    }

// Check if name is in use
    function nameExist($name) {
      $this->SELECT = 'userKey';
      $this->WHERE = 'name = ?';
      $match = [
        $name
      ];

      return $this->fetch($match);
    }

// Check if code is in use
    function codeExist($code) {
      $this->SELECT = 'userKey';
      $this->WHERE = 'userCode = ?';
      $match = [
        $code
      ];

      return $this->fetch($match);
    }

// Get all user details
    function getAll() {
      $this->SELECT = '*';
      $this->WHERE = '';

      return $this->fetchAll([]);
    }

// Edit user's details
    function edit($userKey, $details) {
      $this->WHERE = 'userKey = ?';
      $match = [
        $userKey
      ];
      if (isset($details['password'])) {
        $details['password'] = password_hash($details['password'], PASSWORD_DEFAULT);
      }

      return $this->updateAutoEntry($details, $match);
    }

// Delete user
    function deleteUser($userKey) {
      $this->WHERE = 'userKey = ?';
      $match = [
        $userKey
      ];

      return $this->delete($match);
    }

// Check if role is present in roles string
    static function haveRole($roles, $match) {
      foreach (str_split($roles,2) as $role) {
        if ($role == $match || $role == USERS_ROLE_ALL)
          return true;
      }

      return false;
    }
  }
?>
