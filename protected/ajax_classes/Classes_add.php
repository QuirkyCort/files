<?php
  class Classes_add extends Ajax_Processor {
    function process() {
      global $USER, $db;

      // Check if logged in
      // if (!isset($USER)) {
      //   $this->errorMsg[] = 'Not logged in';
      //   return false;
      // }

      // Check permission
      // if (! Users_DAO::haveRole($USER['roles'], USERS_ROLE_USERS_ADD)) {
      //   $this->errorMsg[] = 'No permission';
      //   return false;
      // }

      // Make sure all required fields are present
      $passed = $this->validateFields([
        'userCode',
        'classCode',
        'description'
      ]);
      if (! $passed) {
        return $passed;
      }

      // Validate each field
      $users = new Users_DAO($db);

      if (! $users->codeExist($this->input['userCode'])) {
        $this->errorMsg[] = 'Invalid User';
        $passed = false;
      }

      if (! $passed) {
        return $passed;
      }

      // Process
      $classes = new Classes_DAO($db);
      $result = $classes->add(
        $this->input['userCode'],
        $this->input['classCode'],
        $this->input['description']
      );
      if ($result === false) {
        $this->errorMsg[] = 'Database error';
        return false;
      }

      return true;
    }
  }
?>
