<?php
  class Files_delete extends Ajax_Processor {
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
        'classCode',
        'fileKey'
      ]);
      if (! $passed) {
        return $passed;
      }

      // Validate each field
      $classes = new Classes_DAO($db);
      $class = $classes->getByCode($this->input['classCode']);

      if (! $class) {
        $this->errorMsg[] = 'Invalid Class';
        return false;
      }

      $teacher = false;
      if ($class['userCode'] == $this->input['userCode']) {
        $teacher = true;
      }

      if (! $passed) {
        return $passed;
      }

      // Process
      $files = new Files_DAO($db);
      $file = $files->get($this->input['fileKey']);
      if ($file === false) {
        $this->errorMsg[] = 'Invalid File';
        return false;
      }

      if ($file['classCode'] != $this->input['classCode']) {
        $this->errorMsg[] = 'File and Class mismatched';
        return false;
      }

      if (! $teacher && $file['selfGenID'] != $this->input['selfGenID']) {
        $this->errorMsg[] = 'Only teacher and owner of file can delete';
        return false;
      }

      $result = $files->deleteFile($this->input['fileKey']);
      if ($result === false) {
        $this->errorMsg[] = 'Database error';
        return false;
      }
      unlink(FILES_DIR . $file['data']);

      return true;
    }
  }
?>
