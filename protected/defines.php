<?php
  define('HOST', 'https://files.aposteriori.com.sg/');
  // define('HOST', 'http://localhost/~cort/files/');

  define('FILES_DIR', '../../protected/files/');

  define('USERS_ROLE_ALL', 'SS');
  define('USERS_ROLE_USERS_VIEW', '00');
  define('USERS_ROLE_USERS_ADD', '01');
  define('USERS_ROLE_USERS_EDIT', '02');

  $USERS_ROLE_DESCRIPTION = [
    USERS_ROLE_ALL => 'All Roles',
    USERS_ROLE_USERS_VIEW => 'View Users',
    USERS_ROLE_USERS_ADD => 'Add Users',
    USERS_ROLE_USERS_EDIT => 'Edit Users',
  ];

  define('CLASSES_PROPERTIES_AUTOSHARE', 'A');
?>
