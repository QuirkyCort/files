<?php
  class Users_delete extends Ajax_Processor {
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
        'userKey'
      ]);
      if (! $passed) {
        return $passed;
      }

      // Validate each field
      if ($this->input['userKey'] == 1) {
        $this->errorMsg[] = 'Root user cannot be deleted';
        return false;
      }

      //if (! $passed) {
        //return $passed;
      //}

      // Process
      $users = new Users_DAO($db);
      $result = $users->deleteUser($this->input['userKey']);
      if ($result === false) {
        $this->errorMsg[] = 'Database error';
        return false;
      }

      return true;
    }
  }
?>
