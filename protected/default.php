<?php
  ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
  ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);

  session_start();
  date_default_timezone_set('Asia/Singapore');

  include 'defines.php';

  // Open db
  $db = new PDO('sqlite:'.__DIR__.'/db.sq3');

  // Custom class autoloader
  $AUTOLOAD_CLASSES = [];
  foreach (glob(__DIR__.'/classes/*.php') as $path) {
    $AUTOLOAD_CLASSES[] = basename($path, '.php');
  }

  spl_autoload_register(function ($class_name) {
    global $AUTOLOAD_CLASSES;
    if (in_array($class_name, $AUTOLOAD_CLASSES))
      require __DIR__.'/classes/'.$class_name . '.php';
  });

  // Set CSRF if it doesn't exists
  if (!isset($_SESSION['CSRF'])) {
    $_SESSION['CSRF'] = base64_encode(random_bytes(24));
  }

  // Load user's details if available
  $users = new Users_DAO($db);
  if (isset($_SESSION['userKey'])) {
    $USER = $users->get($_SESSION['userKey']);
    if ($USER === false) {
      unset($USER);
    }
  }
