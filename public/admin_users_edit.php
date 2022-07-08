<?php
  $PAGE_TITLE = "Add/Edit Users";
  $ADDITIONAL_STYLESHEETS = '';
  $ADDITIONAL_SCRIPTS = '';
  include '../protected/adminTop.php';

  // Check permission
  if (
    ! Users_DAO::haveRole(@$USER['roles'], USERS_ROLE_USERS_ADD)
    and ! Users_DAO::haveRole(@$USER['roles'], USERS_ROLE_USERS_EDIT)
  ) {
    include '../protected/no_permission.php';
    exit;
  }
  ?>

<div class="p-5 admin_users_edit">
  <form class="form-horizontal">

    <div class="form-group row">
      <label for="name" class="col-sm-2 col-form-label">Username</label>
      <div class="col-sm-10">
        <input type="text" class="name form-control" id="name" placeholder="Enter username" autocomplete="new-password">
      </div>
    </div>

    <div class="form-group row">
      <label for="code" class="col-sm-2 col-form-label">Access Code</label>
      <div class="col-sm-10">
        <input type="text" class="code form-control" id="code" placeholder="Up to 8 random characters" autocomplete="new-password">
      </div>
    </div>

    <div class="form-group row">
      <label for="password" class="col-sm-2 col-form-label" autocomplete="new-password">Password:</label>
      <div class="col-sm-10">
        <?php
          if (isset($_GET['userKey'])) {
            echo '<input type="password" class="password form-control" id="password" placeholder="Password (Leave blank if not amending)" autocomplete="new-password">';
          } else {
            echo '<input type="password" class="password form-control" id="password" placeholder="Password (Minimum 8 characters)" autocomplete="new-password">';
          }
        ?>
      </div>
    </div>

    <div class="form-group row">
      <label for="roles" class="col-sm-2 col-form-label">Roles:</label>
      <div class="col-sm-10">
        <div class="rolesFlex">
          <?php
            foreach ($USERS_ROLE_DESCRIPTION as $key => $description) {
              if (Users_DAO::haveRole($USER['roles'], $key)){
                echo '<label><input type="checkbox" value="'.$key.'"> '.$description.'</label>';
              }
            }
          ?>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-10">
        <button type="button" class="back btn btn-info">Back</button>
        <?php
          if (! isset($_GET['userKey'])) {
            echo '<button type="button" class="create btn btn-primary">Create New User</button>';
          } else {
            echo '<button type="button" class="edit btn btn-warning">Save</button>';
          }
        ?>
      </div>
    </div>

  </form>
</div>


<script src="js/page/admin_users_edit.js"></script>
</body>
</html>
