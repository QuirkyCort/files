<?php
  class Classes_delete extends Ajax_Processor {
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
        'classCode'
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
      $result = $classes->getByCode($this->input['classCode']);

      if ($result['userCode'] != $this->input['userCode']) {
        $this->errorMsg[] = 'Invalid Class';
        return false;
      }

      $files = new Files_DAO($db);
      $results = $files->getAll($this->input['classCode']);
      foreach ($results as $result) {
        if ($files->deleteFile($result['fileKey']) === false) {
          $this->errorMsg[] = 'Database error';
          return false;
        }
        unlink(FILES_DIR . $result['data']);
      }
      if ($classes->deleteClass($result['classKey']) === false) {
        $this->errorMsg[] = 'Database error';
        return false;
      }
      return true;
    }
  }
?>
