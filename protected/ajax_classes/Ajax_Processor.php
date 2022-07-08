<?php
  abstract class Ajax_Processor {
    public $errorMsg;
    public $input;
    public $output;

    function __construct($input) {
      $this->input = $input;
    }
    
    function validateFields($fields) {
      $passed = true;
      
      foreach ($fields as $field) {
        if (! isset($this->input[$field])) {
          $this->errorMsg[] = $field.' missing';
          $passed = false;
        }
      }
      
      return $passed;
    }
    
    function getErrorMsg() {
      return $this->errorMsg;
    }
    
    function getOutput() {
      return $this->output;
    }
    
    abstract function process();
  }
?>
