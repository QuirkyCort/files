<?php
  class Classes_toggleAutoShare extends Ajax_Processor {
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

      if ($classes->haveProperty($result['properties'], CLASSES_PROPERTIES_AUTOSHARE)) {
        $classes->removeProperty($result['classKey'], CLASSES_PROPERTIES_AUTOSHARE);
      } else {
        $classes->addProperty($result['classKey'], CLASSES_PROPERTIES_AUTOSHARE);
      }
      return true;
    }
  }
?>
