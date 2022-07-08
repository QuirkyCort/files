<?php
  class Logout extends Ajax_Processor {
    function process() {
      //global $USER;

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
      //$passed = $this->validateFields([]);
      //if (! $passed) {
        //return $passed;
      //}

      // Validate each field
      //if (iconv_strlen(trim($this->input['search'])) < 3 and iconv_strlen(trim($this->input['search'])) > 0) {
        //$this->errorMsg[] = 'Search string too short';
        //$passed = false;
      //}
      //if (! $passed) {
        //return $passed;
      //}

      // Unset all of the session variables.
      $_SESSION = array();

      // If it's desired to kill the session, also delete the session cookie.
      // Note: This will destroy the session, and not just the session data!
      if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
          session_name(),
          '',
          time() - 42000,
          $params["path"],
          $params["domain"],
          $params["secure"],
          $params["httponly"]
        );
      }

      // Destroy the session.
      session_destroy();

      return true;
    }
  }
?>
