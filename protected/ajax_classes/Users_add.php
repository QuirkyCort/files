<?php
  class Users_add extends Ajax_Processor {
    function process() {
      global $USER, $db;

      // Check if logged in
      if (!isset($USER)) {
        $this->errorMsg[] = 'Not logged in';
        return false;
      }

      // Check permission
      if (! Users_DAO::haveRole($USER['roles'], USERS_ROLE_USERS_ADD)) {
        $this->errorMsg[] = 'No permission';
        return false;
      }

      // Make sure all required fields are present
      $passed = $this->validateFields([
        'name',
        'code',
        'password',
        'roles'
      ]);
      if (! $passed) {
        return $passed;
      }

      // Validate each field
      $users = new Users_DAO($db);

      if (strlen($this->input['name']) < 3) {
        $this->errorMsg[] = 'Name is too short (min 3 chars)';
        $passed = false;
      }

      if ($users->nameExist($this->input['name'])) {
        $this->errorMsg[] = 'Name already in use';
        $passed = false;
      }

      foreach (str_split($this->input['roles'], 2) as $role) {
        if (! $users::haveRole($USER['roles'], $role)) {
          $this->errorMsg[] = 'Cannot grant role that you do not possess';
          $passed = false;
        }
      }

      if (! $passed) {
        return $passed;
      }

      // Process
      $result = $users->add(
        $this->input['name'],
        $this->input['code'],
        $this->input['password'],
        $this->input['roles']
      );
      if ($result === false) {
        $this->errorMsg[] = 'Database error';
        return false;
      }

      return true;
    }
  }
?>
