<?php
  class Files_getAll extends Ajax_Processor {
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
        'selfGenID'
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

      // Process
      $files = new Files_DAO($db);
      $results = $files->getAll($this->input['classCode']);
      if ($results === false) {
        $this->errorMsg[] = 'Database error';
        return false;
      }

      $this->output['teachers'] = [];
      $this->output['students'] = [];
      foreach ($results as $result) {
        if ($result['teacher']) {
          $this->output['teachers'][] = [
            'fileKey' => $result['fileKey'],
            'fileName' => $result['fileName'],
            'userName' => $result['userName'],
            'date' => $result['date']
          ];
        } else {
          if (
            $teacher
            || $result['share']
            || $result['selfGenID'] == $this->input['selfGenID']
          ) {
            if ($result['selfGenID'] == $this->input['selfGenID']) {
              $own = true;
            } else {
              $own = false;
            }
            $this->output['students'][] = [
              'fileKey' => $result['fileKey'],
              'fileName' => $result['fileName'],
              'userName' => $result['userName'],
              'date' => $result['date'],
              'share' => $result['share'],
              'own' => $own
            ];
          }
        }
      };

      return true;
    }
  }
?>
