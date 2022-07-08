<?php
  include '../../protected/default.php';
?>
<?php
  header('Content-Type: application/json');

  // Prepare list of valid ajax request processors
  $AJAX_PROCESSORS = [];
  foreach (glob('../../protected/ajax_classes/*.php') as $path) {
    $AJAX_PROCESSORS[] = basename($path, '.php');
  }
  spl_autoload_register(function ($class_name) {
    global $AJAX_PROCESSORS;
    if (in_array($class_name, $AJAX_PROCESSORS))
      require '../../protected/ajax_classes/'.$class_name . '.php';
  });

  if (!isset($_POST['input'])) {
    echo json_encode([
      'status' => 'Failed',
      'errorMsg' => 'Empty request'
    ]);
    exit;
  }

  // Decode JSON
  $inputs = json_decode($_POST['input'], true);

  // // Check origin
  // if (isset($_SERVER['HTTP_ORIGIN'])) {
  //   $parsed = parse_url($_SERVER['HTTP_ORIGIN']);
  // } else if (isset($_SERVER['HTTP_REFERER'])) {
  //   $parsed = parse_url($_SERVER['HTTP_REFERER']);
  // } else {
  //   error_log($_SERVER['PHP_SELF'].': CSRF Failed (No Origin or Referer)');
  //   echo json_encode([
  //     'status' => 'Failed',
  //     'errorMsg' => 'CSRF Failed (No Origin or Referer)'
  //   ]);
  //   exit;
  // }
  // $origin = $parsed['scheme'].'://'.$parsed['host'];
  // $parsed = parse_url(HOST);
  // $allowed = $parsed['scheme'].'://'.$parsed['host'];
  // if ($origin != $allowed) {
  //   error_log($_SERVER['PHP_SELF'].': CSRF Failed (Invalid Origin: '.$_SERVER['HTTP_ORIGIN'].')');
  //   echo json_encode([
  //     'status' => 'Failed',
  //     'errorMsg' => 'CSRF Failed (Invalid Origin)'
  //   ]);
  //   exit;
  // }

  // // Check CSRF Token
  // if (! isset($_POST['CSRF'])) {
  //   error_log($_SERVER['PHP_SELF'].': CSRF Failed (No Token)');
  //   echo json_encode([
  //     'status' => 'Failed',
  //     'errorMsg' => 'CSRF Failed (No Token)'
  //   ]);
  //   exit;
  // }

  // if ($_POST['CSRF'] != $_SESSION['CSRF']) {
  //   error_log($_SERVER['PHP_SELF'].': CSRF Failed (Token mismatch)');
  //   echo json_encode([
  //     'status' => 'Failed',
  //     'errorMsg' => 'CSRF Failed (Token mismatch)'
  //   ]);
  //   exit;
  // }

  // Run processors in order if specified
  if (isset($inputs['order'])) {
    $keys = $inputs['order'];
    $output['order'] = $keys;
  } else {
    $keys = array_keys($inputs);
  }

  // Run each requested processor
  foreach ($keys as $key) {
    if (in_array($key, $AJAX_PROCESSORS)) {
      $processor = new $key($inputs[$key]);
      // Process request
      if (! $processor->process()) {
        $output[$key]['errorMsg'] = $processor->getErrorMsg();
        $output[$key]['status'] = 'Failed';
        continue;
      }
      $output[$key] = $processor->getOutput();
      $output[$key]['status'] = 'OK';
    }
  }

  echo json_encode(@$output, JSON_PARTIAL_OUTPUT_ON_ERROR);
?>
