<?php
  class Classes_getAll extends Ajax_Processor {
    function process() {
      global $USER, $db;

      // Check if logged in
      // if (!isset($USER)) {
      //   $this->errorMsg[] = 'Not logged in';
      //   return false;
      // }

      // Check permission
      // if (! Users_DAO::haveRole($USER['roles'], USERS_ROLE_USERS_VIEW)) {
      //   $this->errorMsg[] = 'No permission';
      //   return false;
      // }

      // Make sure all required fields are present
      $passed = $this->validateFields([
        'code'
      ]);
      if (! $passed) {
        return $passed;
      }

      // Validate each field
      //if (! $passed) {
        //return $passed;
      //}

      $users = new Classes_DAO($db);
      $results = $users->getAll($this->input['code']);
      if ($results === false) {
        $this->errorMsg[] = 'Database error';
        return false;
      }

      $this->output['classes'] = [];
      foreach ($results as $result) {
        $this->output['classes'][] = [
          'code' => $result['classCode'],
          'description' => $result['description']
        ];
      };

      return true;
    }
  }
?>
