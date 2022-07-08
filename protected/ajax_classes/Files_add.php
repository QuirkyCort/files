<?php
  class Files_add extends Ajax_Processor {
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
        'classCode'
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

      $teacher = 0;
      if (isset($this->input['userCode'])) {
        if ($class['userCode'] == $this->input['userCode']) {
          $teacher = 1;
        } else {
          $this->errorMsg[] = 'You are not the teacher for this class';
          return false;
        }
      }

      if (! $passed) {
        return $passed;
      }

      if (isset($this->input['userName'])) {
        $userName = $this->input['userName'];
      } else {
        $userName = '';
      }

      // Process
      $files = new Files_DAO($db);
      if (!isset($_FILES['files'])) {
        $this->errorMsg[] = 'No files attached';
        return false;
      }
      for ($i=0; $i<count($_FILES['files']['name']); $i++) {
        if ($_FILES['files']['size'][$i] > 52428800) {
          $this->errorMsg[] = 'File too large (Max of 50MB)';
          return false;
        }
        $handle = fopen($_FILES['files']['tmp_name'][$i], "r");
        if ($handle == false) {
          $this->errorMsg[] = 'File too large (Max of 50MB)';
          return false;
        }

        $randName = $files->randString(10);
        copy($_FILES['files']['tmp_name'][$i], FILES_DIR . $randName);

        $result = $files->add(
          $this->input['classCode'],
          $userName,
          $_FILES['files']['name'][$i],
          $_FILES['files']['size'][$i],
          $teacher,
          $randName,
          $this->input['selfGenID']
        );
        if ($result === false) {
          $this->errorMsg[] = 'Database error';
          return false;
        }
      }

      return true;
    }
  }
?>
