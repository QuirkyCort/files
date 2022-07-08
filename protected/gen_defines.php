<?php
  // Prep list of globals before it gets polluted by other variables
  $defaultGlobals = array_keys($GLOBALS);
  
  include 'defines.php';

  $globals = [];
  foreach (array_keys($GLOBALS) as $key) {
    if (
      ! in_array($key, $defaultGlobals)
      and ! in_array($key, ['defaultGlobals', 'globals', 'key'])
    ) {
      $globals[] = $key;
    }
  }

  $f = fopen('../public/js/common/defines.js', 'w');

  fwrite($f, "// Auto generated. DO NOT EDIT.\n");
  
  // Constants first
  fwrite($f, "\n// CONSTANTS.\n");
  $constants = get_defined_constants(True);
  
  foreach ($constants['user'] as $key => $value) {
    if (gettype($value) == 'string') {
      $str = 'var ' . $key . " = '" . $value . "';\n";
    } else {
      $str = 'var ' . $key . " = " . $value . ";\n";
    }
    fwrite($f, $str);
  }
  
  // Globals next
  fwrite($f, "\n// GLOBALS.\n");
  foreach ($globals as $key) {
    $str = 'var ' . $key . " = {\n";
    foreach ($GLOBALS[$key] as $key2 => $value) {
      $str .= "  '" . $key2 . "': ";
      if (gettype($value) == 'string') {
        $str .= "'" . $value . "',\n";
      } else {
        $str .= $value . ",\n";
      }
    }
    $str = substr($str, 0, -2) . "\n";
    $str .= "};\n";
    fwrite($f, $str);
  }
  
  fclose($f);
?>