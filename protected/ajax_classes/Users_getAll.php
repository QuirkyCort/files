<?php
  class Users_getAll extends Ajax_Processor {
    function process() {
      global $USER, $db;

      // Check if logged in
      if (!isset($USER)) {
        $this->errorMsg[] = 'Not logged in';
        return false;
      }

      // Check permission
      if (! Users_DAO::haveRole($USER['roles'], USERS_ROLE_USERS_VIEW)) {
        $this->errorMsg[] = 'No permission';
        return false;
      }

      // Make sure all required fields are present
      //$passed = $this->validateFields([]);
      //if (! $passed) {
        //return $passed;
      //}

      // Validate each field
      //if (! $passed) {
        //return $passed;
      //}

      $users = new Users_DAO($db);
      $results = $users->getAll();
      if ($results === false) {
        $this->errorMsg[] = 'Database error';
        return false;
      }

      $this->output['users'] = [];
      foreach ($results as $result) {
        $this->output['users'][] = [
          'userKey' => $result['userKey'],
          'name' => $result['name'],
          'code' => $result['userCode'],
          'roles' => $result['roles']
        ];
      };

      return true;
    }
  }
?>
