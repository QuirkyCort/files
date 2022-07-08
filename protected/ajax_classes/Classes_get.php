<?php
  class Classes_get extends Ajax_Processor {
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
        'classCode'
      ]);
      if (! $passed) {
        return $passed;
      }

      // Validate each field
      //if (iconv_strlen(trim($this->input['search'])) < 3 and iconv_strlen(trim($this->input['search'])) > 0) {
        //$this->errorMsg[] = 'Search string too short';
        //$passed = false;
      //}
      //if (! $passed) {
        //return $passed;
      //}

      $classes = new Classes_DAO($db);
      $result = $classes->getByCode($this->input['classCode']);
      if ($result === false) {
        $this->errorMsg[] = 'Class not found';
        return false;
      }

      $this->output['class'] = [
        'description' => $result['description']
      ];

      return true;
    }
  }
?>
