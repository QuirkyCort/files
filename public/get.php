<?php
  include '../protected/default.php';

  session_cache_limiter('');
  header('Cache-Control: max-age=8640000');
  header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 8640000));
  header('Pragma: cache');

  header('Access-Control-Allow-Origin: *');

  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
      header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
      header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
    }

    exit(0);
  }

  // Strip away the "get" directory if needed
  $path = explode('/', $_GET['file']);
  if (count($path) == 2) {
    $filename = $path[1];
  } else {
    $filename = $path[0];
  }

  // Strip away file extension
  $filename = explode('.', $filename)[0];

  $files = new Files_DAO($db);
  $file = $files->get($filename);

  header('Content-disposition: attachment; filename="'.$file['fileName'].'"');
  header('Content-Type: binary/octet-stream');
  readfile('../protected/files/' . $file['data']);