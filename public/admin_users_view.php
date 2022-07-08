<?php
  $PAGE_TITLE = "View Users";
  $ADDITIONAL_STYLESHEETS = '';
  $ADDITIONAL_SCRIPTS = '';
  include '../protected/adminTop.php';

  // Check permission
  if (! Users_DAO::haveRole(@$USER['roles'], USERS_ROLE_USERS_VIEW)) {
    include '../protected/no_permission.php';
    exit;
  }
?>

<div class="p-5">
  <span class="addUser clickable"><i class="fa fa-plus-circle"></i>&nbsp;Add User</span>

  <table class="table table-hover">
    <thead>
      <th>Name</th>
      <th>Access Link</th>
      <th>Roles</th>
      <th></th>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<script src="js/page/admin_users_view.js"></script>
</body>
</html>
