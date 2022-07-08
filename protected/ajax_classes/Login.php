<?php
  class Login extends Ajax_Processor {
    function process() {
      global $USER, $db;

      // Check if logged in
      //if (!isset($USER)) {
        //$this->errorMsg[] = 'Not logged in';
        //return false;
      //}

      // Check permission
      //if ($USER['role'] != ROLE_ADMIN)) {
        //$this->errorMsg[] = 'No permission';
        //return false;
      //}

      // Make sure all required fields are present
      $passed = $this->validateFields([
        'name',
        'password'
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

      if (strlen($this->input['password']) == '') {
        $this->errorMsg[] = 'Incorrect Username or Password';
        return false;
      }

      $users = new Users_DAO($db);
      $result = $users->checkPassword($this->input['name'], $this->input['password']);
      if ($result === false) {
        $this->errorMsg[] = 'Incorrect Username or Password';
        return false;
      }

      $USER = $result;
      $_SESSION['userKey'] = $result['userKey'];

      return true;
    }
  }
?>
