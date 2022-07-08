<?php
  class Users_edit extends Ajax_Processor {
    function process() {
      global $USER, $db;

      // Check if logged in
      if (!isset($USER)) {
        $this->errorMsg[] = 'Not logged in';
        return false;
      }

      // Check permission
      if (! Users_DAO::haveRole($USER['roles'], USERS_ROLE_USERS_EDIT)) {
        $this->errorMsg[] = 'No permission';
        return false;
      }

      // Make sure all required fields are present
      $passed = $this->validateFields([
        'userKey',
        'name',
        'code',
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
      $details = [
        'name' => $this->input['name'],
        'userCode' => $this->input['code'],
        'roles' => $this->input['roles'],
      ];
      if ($this->input['password'])
        $details['password'] = $this->input['password'];

      $result = $users->edit($this->input['userKey'], $details);
      if ($result === false) {
        $this->errorMsg[] = 'Database error';
        return false;
      }

      return true;
    }
  }
?>
